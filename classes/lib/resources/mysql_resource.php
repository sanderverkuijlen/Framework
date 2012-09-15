<?php
class MysqlResource extends Resource{

    const DATE_FORMAT       = '%d-%m-%Y';
    const DATETIME_FORMAT   = '%d-%m-%Y %H:%i:%s';

    private $dbCon;
    private $encryptionKey;

    public function __construct($hostname, $dbName, $dbUser, $dbPwd, $encryptionKey){

        $this->dbCon            = new DbCon($hostname, $dbName, $dbUser, $dbPwd);
        $this->encryptionKey    = $encryptionKey;
    }

    /**
     * @param BaseMapper $mapper
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @param null $start
     * @param null $count
     * @return array[BaseModel]
     */
    public function find(BaseMapper $mapper, $filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        $values     = [
            'db_encryption_key' => $this->encryptionKey
        ];
        $select     = [];
        $join       = [];
        $where      = [];
        $orderby    = "";
        $limit      = "";

        //Select
        foreach($mapper->fields as $fieldname => $attributes){

            //N-1 and N-N associations aren't part of the select
            if($attributes['type'] === TypeEnum::ASSOCIATION_1_N || $attributes['type'] === TypeEnum::ASSOCIATION_N_N){
                continue;
            }

            if(array_key_exists('encrypted', $attributes) && $attributes['encrypted'] === true){
                $fieldSelect = "CAST(AES_DECRYPT(`".$mapper->collection."`.`".$fieldname."`, :db_encryption_key) AS CHAR)";
            }
            else{
                $fieldSelect = "`".$mapper->collection."`.`".$fieldname."`";
            }

            if($attributes['type'] === TypeEnum::DATE){
                $fieldSelect = "DATE_FORMAT(".$fieldSelect.", '".self::DATE_FORMAT."')";
            }
            elseif($attributes['type'] === TypeEnum::DATETIME){
                $fieldSelect = "DATE_FORMAT(".$fieldSelect.", '".self::DATETIME_FORMAT."')";
            }

            $select[] = $fieldSelect." AS `".$fieldname."`";
            unset($fieldSelect);
        }

        //Filters
        $this->filtersToSql($mapper, $filters, $values, $join, $where);

        //Order
        if($orderColumn){
            $orderby = "ORDER BY `".$orderColumn."` ".($orderDesc ? "DESC" : "ASC").PHP_EOL;
        }

        //Limit
        if($start && $count){
            $limit = "LIMIT ".(int) $start.", ".(int) $count.PHP_EOL;
        }

        $sql = "
            SELECT
                ".implode(",".PHP_EOL, $select)."
            FROM
                `".$mapper->collection."`

            ".implode(PHP_EOL, $join)."

            ".($where ? "WHERE ".implode(" AND ", $where) : "")."

            ".$orderby."

            ".$limit;

        $models = array();
        $res = $this->dbCon->query($sql, $values);
        while($data = $this->dbCon->fetch($res)){

            $models[] = $mapper->createObjectFromArray($data);
        }

        return $models;
    }

    /**
     * @param BaseMapper $mapper
     * @param $query
     * @param $values
     * @return array[BaseModel]
     */
    public function findByQuery(BaseMapper $mapper, $query, $values){

        $models = array();

        $res = $this->dbCon->query($query, $values);
        while($data = $this->dbCon->fetch($res)){

            $models[] = $mapper->createObjectFromArray($data);
        }

        return $models;
    }

    /**
     * @param BaseMapper $mapper
     * @param array $filters
     * @return int
     */
    public function count(BaseMapper $mapper, $filters = array()){

        $values     = [
            'db_encryption_key' => $this->encryptionKey
        ];
        $select     = [];
        $join       = [];
        $where      = [];

        //Filters
        $this->filtersToSql($mapper, $filters, $values, $join, $where);

        $sql = "
            SELECT
                COUNT(*) AS aantal
            FROM
                `".$mapper->collection."`

            ".implode(PHP_EOL, $join)."

            ".($where ? "WHERE ".implode(" AND ", $where) : "");

        $res = $this->dbCon->query($sql, $values);

        if($this->dbCon->go($res)){

            $data = $this->dbCon->fetch($res);
            return $data['aantal'];
        }

        return 0;
    }


    /**
     * @param BaseMapper $mapper
     * @param BaseModel $model
     */
    public function save(BaseMapper $mapper, BaseModel $model){

        $values = $mapper->createArrayFromObject($model);
        $values['db_encryption_key'] = $this->encryptionKey;

        $set = [];

        foreach($mapper->fields as $fieldname => $attributes){

            //If the fieldname isn't in the values it will cause an error if we don't skip it
            if(array_key_exists($fieldname, $values)){

                //Never update de primary key
                if(!array_key_exists('primary', $attributes) || $attributes['primary'] !== true){
                    $fieldSet = ":".$fieldname;

                    if($attributes['type'] === TypeEnum::DATE){
                        $fieldSet = "STR_TO_DATE(".$fieldSet.", '".self::DATE_FORMAT."')";
                    }
                    elseif($attributes['type'] === TypeEnum::DATETIME){
                        $fieldSet = "STR_TO_DATE(".$fieldSet.", '".self::DATETIME_FORMAT."')";
                    }

                    if(array_key_exists('encrypted', $attributes) && $attributes['encrypted'] === true){
                        $fieldSet = "AES_ENCRYPT(".$fieldSet.", :db_encryption_key)";

                    }

                    $set[] = "`".$mapper->collection."`.`".$fieldname."` = ".$fieldSet;
                    unset($fieldSet);
                }
            }
        }

        //If there's nothing to update, do nothing
        if(sizeof($set) > 0){

            if($model->id > 0){
                $sql = "
                    UPDATE
                        `".$mapper->collection."`
                    SET
                        ".implode(','.PHP_EOL, $set)."
                    WHERE
                        ".$mapper->collection.".id = :id";

                $this->dbCon->query($sql, $values);
            }
            else{
                $sql = "
                    INSERT INTO
                        `".$mapper->collection."`
                    SET
                        ".implode(','.PHP_EOL, $set);

                $this->dbCon->query($sql, $values);
                $model->id = $this->dbCon->getLastId();
            }
        }
    }

    /**
     * @param BaseMapper $mapper
     * @param BaseModel $model
     */
    public function delete(BaseMapper $mapper, BaseModel $model){

        $values = [
            'id' => $model->id
        ];
        $sql = "
            DELETE FROM
                `".$mapper->collection."`
            WHERE
                id = :id";

        $this->dbCon->query($sql, $values);
    }


    /**
     * @param BaseMapper $mapper
     * @param BaseModel $model
     * @param BaseModel $associate
     * @param $field
     */
    public function addAssociation(BaseMapper $mapper, BaseModel $model, BaseModel $associate, $field){

        $table      = $this->getAssociationTable($mapper, $field);

        $attributes = $mapper->fields[$field];
        $opposite   = $mapper->getOppositeAssociation($field);

        $values = [
            $attributes['column']   => $model->id,
            $opposite['column']     => $associate->id
        ];

        $sql = "
            REPLACE INTO
                `".$table."`
            SET
                `".$attributes['column']."` = :".$attributes['column'].",
                `".$opposite['column']."`   = :".$opposite['column'];

        $this->dbCon->query($sql, $values);
    }

    /**
     * @param BaseMapper $mapper
     * @param BaseModel $model
     * @param BaseModel $associate
     * @param $field
     */
    public function removeAssociation(BaseMapper $mapper, BaseModel $model, BaseModel $associate, $field){

        $table      = $this->getAssociationTable($mapper, $field);

        $attributes = $mapper->fields[$field];
        $opposite   = $mapper->getOppositeAssociation($field);

        $values = [
            $attributes['column']   => $model->id,
            $opposite['column']     => $associate->id
        ];

        $sql = "
            DELETE FROM
                `".$table."`
            WHERE
                `".$attributes['column']."` = :".$attributes['column']."
            AND
                `".$opposite['column']."`   = :".$opposite['column'];

        $this->dbCon->query($sql, $values);
    }


    /**
     * @param BaseMapper $mapper
     * @param array $filters
     * @param array $values
     * @param array $join
     * @param array $where
     */
    private function filtersToSql(BaseMapper $mapper, array $filters, array &$values, array &$join, array &$where){

        foreach($filters as $filter => $value){

            $usedModifiers  = $this->getUsedModifiers($filter);

            //Find the correct field to which we should apply this filter
            $field          = $this->getFilterField($mapper, $filter);

            //If we can't match the filter to a field we don't know what to filter, so ignore it if this happens
            if($field !== null){

                //Now that we know which field to filter and which modifiers to use we can start to compose this part of the query
                $attributes = $mapper->fields[$field];


                $fieldWhere = "`".$mapper->collection."`.`".$field."`";

                //Encrypted values have to be decrypted before they can be compared
                if(array_key_exists('encrypted', $attributes) && $attributes['encrypted'] === true){

                    $fieldWhere = "CAST(AES_DECRYPT(".$fieldWhere.", :db_encryption_key) AS CHAR)";
                }

                if($attributes['type'] === TypeEnum::ASSOCIATION_1_N){

                    $associationMapper = BaseMapper::getMapperForClass($attributes['class']);

                    $join[$associationMapper->collection] = "INNER JOIN `".$associationMapper->collection."` ON `".$mapper->collection."`.id = `".$associationMapper->collection."`.`".$attributes['column']."`";

                    $fieldWhere = "`".$associationMapper->collection."`.`id`";
                }
                elseif($attributes['type'] === TypeEnum::ASSOCIATION_N_N){

                    $associationTable = $this->getAssociationTable($mapper, $field);
                    $opposite = $mapper->getOppositeAssociation($field);

                    $join[$associationTable] = "INNER JOIN `".$associationTable."` ON `".$mapper->collection."`.id = `".$associationTable."`.`".$attributes['column']."`";

                    $fieldWhere = "`".$associationTable."`.`".$opposite['column']."`";
                }

                if(in_array(ModifierEnum::_IN, $usedModifiers)){

                    //If the _IN modifier is used then $value is expected to be an array
                    if(!is_array($value)){
                       $value = array($value);
                    }

                    $filters = array();

                    //Add all the values and map them to unique keys
                    for($i = 0; $i < sizeof($value); $i++){

                        $filters[]              = ":".$filter."_".$i;
                        $values[$filter."_".$i] = $value[$i];
                    }

                    $where[] = $fieldWhere." ".(in_array(ModifierEnum::NOT_, $usedModifiers) ? "NOT" : "")." IN (".implode(", ", $filters).")";

                    unset($filters);
                }
                else{

                    //Figure out how we should compare the filter value to the database field
                    $equals = "=";

                    if($attributes['type'] == TypeEnum::DATE || $attributes['type'] == TypeEnum::DATETIME || $attributes['type'] == TypeEnum::INT || $attributes['type'] == TypeEnum::DECIMAL){

                        // _MORE/_LESS/_OR_EQUAL modifiers
                        if(in_array(ModifierEnum::_LESS, $usedModifiers)){

                            $equals = "<";

                            if(in_array(ModifierEnum::_OR_EQUAL, $usedModifiers)){

                                $equals .= "=";
                            }
                        }
                        elseif(in_array(ModifierEnum::_MORE, $usedModifiers)){

                            $equals = ">";

                            if(in_array(ModifierEnum::_OR_EQUAL, $usedModifiers)){

                                $equals .= "=";
                            }
                        }
                    }
                    elseif($attributes['type'] == TypeEnum::TEXT || $attributes['type'] == TypeEnum::VARCHAR){

                        $equals = "LIKE";
                    }

                    //LIKE and = can be inverted, but <, <=, >, -> can't since those are seperate modifiers
                    if(in_array(ModifierEnum::NOT_, $usedModifiers) && ($equals == "LIKE" || $equals == "=")){
                        $equals    = ($equals == "LIKE" ? "NOT " : "!").$equals;
                    }

                    //Dates and Datetimes have to be correctly cast so we can be sure that mysql compares them correctlym the rest can be compared directly
                    if($attributes['type'] == TypeEnum::DATE){

                        $where[] = $fieldWhere." ".$equals." STR_TO_DATE(:".$filter.", '".self::DATE_FORMAT."')";
                    }
                    elseif($attributes['type'] == TypeEnum::DATETIME){

                        $where[] = $fieldWhere." ".$equals." STR_TO_DATE(:".$filter.", '".self::DATETIME_FORMAT."')";
                    }
                    else{

                        $where[] = $fieldWhere." ".$equals." :".$filter;
                    }

                    //Bool values are converted to 1 or 0, since that's how they're saved in the database
                    if($attributes['type'] == TypeEnum::BOOL){

                        $value = ($value ? 1 : 0);
                    }

                    $values[$filter] = $value;

                }
            }
        }
    }

    private function getUsedModifiers(&$filter){

        $usedModifiers = array();

        $modifiers = [
            'not_'      => ModifierEnum::NOT_,

            '_in'       => ModifierEnum::_IN,

            '_before'   => ModifierEnum::_LESS,
            '_after'    => ModifierEnum::_MORE,
            '_less'     => ModifierEnum::_LESS,
            '_more'     => ModifierEnum::_MORE,
            '_or_equal' => ModifierEnum::_OR_EQUAL
        ];

        //Check which (if any) modifiers should be applied to this filter
        foreach($modifiers as $modifier => $modifierId){

            $filter = str_ireplace($modifier, '', $filter, $modifierFound);
            if($modifierFound > 0){
                $usedModifiers[] = $modifierId;
            }
        }

        return $usedModifiers;
    }

    private function getFilterField(BaseMapper $mapper, $filter){


        foreach($mapper->fields as $field => $attributes){

            if($attributes['type'] === TypeEnum::ASSOCIATION_1_N || $attributes['type'] === TypeEnum::ASSOCIATION_N_N){

                $opposite = $mapper->getOppositeAssociation($field);

                if($opposite['column'] === $filter){

                    return $field;
                }
            }
            elseif($field === $filter){
                return $field;
            }
        }

        return null;
    }

    /**
     * @param BaseMapper $mapper
     * @param $field
     * @return string
     */
    private function getAssociationTable(BaseMapper $mapper, $field){

        $association    = $mapper->fields[$field];

        $parts = array(
            $association['field'],
            $field
        );

        //Sort the fields so they are always in the same order, regardless of the class from which we look at the association
        sort($parts);

        return implode('_', $parts);
    }
}

abstract class ModifierEnum{
    const NOT_      = 1;

    const _IN       = 2;

    const _LESS     = 3;
    const _MORE     = 4;
    const _OR_EQUAL = 5;
}