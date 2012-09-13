<?
abstract class BaseMapper{
    use Db;

    public $table    = '';
    public $fields   = array();


    public function __construct(){

        $this->fields['id'] = [
            'type'      => 'int',
            'primary'   => true,
            'required'  => true
        ];
    }


    /**
     * @param $id
     * @return BaseModel|null
     * @throws SqlException
     */
    public function get($id){

        return $this->findOne(array( 'id' => $id ));
    }

    /**
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @return BaseModel|null
     * @throws SqlException
     */
    public function findOne($filters = array(), $orderColumn = '', $orderDesc = false){

        $result = $this->find($filters, $orderColumn, $orderDesc, 0, 1);
        return (current($result) !== false ? current($result) : null);
    }

    /**
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @param null $start
     * @param null $count
     * @return array[BaseModel]
     * @throws SqlException
     */
    public function find($filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        $values     = [
            'db_encryption_key' => DB_ENCRYPTION_KEY
        ];
        $select     = [];
        $join       = [];
        $where      = [];
        $orderby    = "";
        $limit      = "";

        //Select
        foreach($this->fields as $fieldname => $attributes){

            //HAS_MANY associations aren't part of the select
            if($attributes['type'] === TypeEnum::ASSOCIATION_HAS_MANY){
                continue;
            }

            if(array_key_exists('encrypted', $attributes) && $attributes['encrypted'] === true){
                $fieldSelect = "CAST(AES_DECRYPT(`".$this->table."`.`".$fieldname."`, :db_encryption_key) AS CHAR)";
            }
            else{
                $fieldSelect = "`".$this->table."`.`".$fieldname."`";
            }

            if($attributes['type'] === TypeEnum::DATE){
                $fieldSelect = "DATE_FORMAT(".$fieldSelect.", '%d-%m-%Y')";
            }
            elseif($attributes['type'] === TypeEnum::DATETIME){
                $fieldSelect = "DATE_FORMAT(".$fieldSelect.", '%d-%m-%Y %H:%i:%s')";
            }

            $select[] = $fieldSelect." AS `".$fieldname."`";
            unset($fieldSelect);
        }

        //Filters
        if(isset($filters['id'])){
            $values[$this->table.'_id'] = $filters['id'];
            $where[] = "`".$this->table."`.`id` = :".$this->table."_id";
        }
        //TODO: verschillende soorten filters (encrypted, varchar (LIKE), _in, not_, _before, _after

        //Order
        if($orderColumn){
            $orderby = "ORDER BY `".$orderColumn."` ".($orderDesc ? "DESC" : "ASC").PHP_EOL;
        }

        //Limit
        if($start && $count){
            $limit = "LIMIT ".$start.", ".$count.PHP_EOL;
        }

        $sql = "
            SELECT
                ".implode(",".PHP_EOL, $select)."
            FROM
                `".$this->table."`

            ".implode(PHP_EOL, $join)."

            ".($where ? "WHERE ".implode(" AND ", $where) : "")."

            ".$orderby."

            GROUP BY `".$this->table."`.id

            ".$limit;

        $models = array();
        $res = $this->dbCon()->query($sql, $values);
        while($data = $this->dbCon()->fetch($res)){

            $models[] = $this->createObjectFromRow($data);
        }

        return $models;
    }

    /**
     * @param string $sql
     * @param array $values
     * @return array[BaseModel]
     * @throws SqlException
     */
    public function findBySql($sql, $values){
        $models = array();

        $res = $this->dbCon()->query($sql, $values);
        while($data = $this->dbCon()->fetch($res)){

            $models[] = $this->createObjectFromRow($data);
        }

        return $models;
    }


    /**
     * @abstract
     * @param BaseModel $model
     * @throws ValidationException, SQLException
     */
    public function save(BaseModel $model){

        $model->validate();

        $values = $this->createArrayFromObject($model);
        $values['db_encryption_key'] = DB_ENCRYPTION_KEY;

        $set = [];

        foreach($this->fields as $fieldname => $attributes){

            //If the fieldname isn't in the values it will cause an error if we don't skip it
            if(array_key_exists($fieldname, $values)){

                //Never update de primary key
                if(!array_key_exists('primary', $attributes) || $attributes['primary'] !== true){
                    $fieldSet = ":".$fieldname;

                    if($attributes['type'] === TypeEnum::DATE){
                        $fieldSet = "STR_TO_DATE(".$fieldSet.", '%d-%m-%Y')";
                    }
                    elseif($attributes['type'] === TypeEnum::DATETIME){
                        $fieldSet = "STR_TO_DATE(".$fieldSet.", '%d-%m-%Y %H:%i:%s')";
                    }

                    if(array_key_exists('encrypted', $attributes) && $attributes['encrypted'] === true){
                        $fieldSet = "AES_ENCRYPT(".$fieldSet.", :db_encryption_key)";

                    }

                    $set[] = "`".$this->table."`.`".$fieldname."` = ".$fieldSet;
                    unset($fieldSet);
                }
            }
        }

        //If there's nothing to update, do nothing
        if(sizeof($set) > 0){

            if($model->id > 0){
                $sql = "
                    UPDATE
                        `".$this->table."`
                    SET
                        ".implode(','.PHP_EOL, $set)."
                    WHERE
                        ".$this->table.".id = :id";

                $this->dbCon()->query($sql, $values);
            }
            else{
                $sql = "
                    INSERT INTO
                        `".$this->table."`
                    SET
                        ".implode(','.PHP_EOL, $set);

                $this->dbCon()->query($sql, $values);
                $model->id = $this->dbCon()->getLastId();
            }
        }
    }

    /**
     * @param BaseModel $model
     * @throws ValidationException, SqlException
     */
    public function delete(BaseModel $model){

        $model->validateDelete();

        $values = [
            'id' => $model->id
        ];
        $sql = "
            DELETE FROM
                `".$this->table."`
            WHERE
                id = :id";

        $this->dbCon()->query($sql, $values);
    }

    public function addAssociation(BaseModel $model, BaseModel $associate, $field){

        $table      = $this->getAssociationTable($field);

        $attributes = $this->fields[$field];
        $opposite   = $this->getOppositeAssociation($field);

        $values = [
            $attributes['field']    => $model->id,
            $opposite['field']      => $associate->id
        ];

        $sql = "
            REPLACE INTO
                `".$table."`
            SET
                `".$attributes['field']."` = :".$attributes['field'].",
                `".$opposite['field']."`   = :".$opposite['field'];

        $this->dbCon()->query($sql, $values);
    }

    public function removeAssociation(BaseModel $model, BaseModel $associate, $field){

        $table      = $this->getAssociationTable($field);

        $attributes = $this->fields[$field];
        $opposite   = $this->getOppositeAssociation($field);

        $values = [
            $attributes['field']    => $model->id,
            $opposite['field']      => $associate->id
        ];

        $sql = "
            DELETE FROM
                `".$table."`
            WHERE
                `".$attributes['field']."` = :".$attributes['field']."
            AND
                `".$opposite['field']."`   = :".$opposite['field'];

        $this->dbCon()->query($sql, $values);
    }


    /**
     * @abstract
     * @param array $data
     * @return BaseModel
     */
    abstract protected function createObjectFromRow(array $data);

    /**
     * @abstract
     * @param BaseModel $model
     * @return array
     */
    abstract protected function createArrayFromObject(BaseModel $model);


    public function getAssociationTable($field){

        $association    = $this->fields[$field];

        //echo printR($association);
        //echo printR($opposite);

        $parts = array(
            $association['associated'],
            $field
        );

        //Sort the fields so they are always in the same order, regardless of the class from which we look at the association
        sort($parts);

        return implode('_', $parts);
    }

    /**
     * @param $field
     * @return BaseMapper
     */
    protected function getOppositeAssociation($fieldName){

        $attributes = $this->fields[$fieldName];

        //If $field isn't an association do nothing
        if($attributes['type'] === TypeEnum::ASSOCIATION_HAS_ONE || $attributes['type'] === TypeEnum::ASSOCIATION_HAS_MANY){

            //If $field['class'] isn't set then we can't do anything
            if(array_key_exists('class', $attributes)){

                /* @var $mapper BaseMapper */
                $mapper = self::getMapperForClass($attributes['class']);

                //We can't do anything without a mapper
                if($mapper !== null){

                    //If $field['field'] isn't set then we can't do anything
                    if(array_key_exists($attributes['associated'], $mapper->fields)){

                        return $mapper->fields[$attributes['associated']];
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param $class
     * @return BaseMapper
     * @throws ClassNotFoundException
     */
    static public function getMapperForClass($class){

        $class = $class.'Mapper';

        if(ClassLoader::getInstance()->classExists($class)){

            return new $class;
        }

        return null; //This is just to shut up PhpStorm, ClassLoader->classExists either returns true or throws a ClassNotFoundException
    }
}

abstract class TypeEnum{
    const TEXT                      = 'text';
    const VARCHAR                   = 'varchar';
    const INT                       = 'int';
    const DECIMAL                   = 'decimal';
    const BOOL                      = 'tinyint';
    const DATE                      = 'date';
    const DATETIME                  = 'datetime';
    const ASSOCIATION_HAS_ONE       = 'association_has_one';    //1-side of an association (1-1, 1-n)
    const ASSOCIATION_HAS_MANY      = 'association_has_many';   //n-side of an association (n-n, n-1)
}
?>