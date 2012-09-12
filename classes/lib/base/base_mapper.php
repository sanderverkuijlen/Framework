<?
abstract class BaseMapper{
    use Singleton;

    protected $table    = '';
    protected $fields   = array();


    protected function init(){

        $this->fields['id'] = [
            'type'      => 'int',
            'required'  => true
        ];
    }


    /**
     * @param $id
     * @return BaseModel|null
     */
    public function get($id){

        return $this->findOne(array( 'id' => $id ));
    }

    /**
     * @param array $filters
     * @param string $order_column
     * @param bool $order_desc
     * @return BaseModel|null
     */
    public function findOne($filters = array(), $orderColumn = '', $orderDesc = false){

        $result = $this->find($filters, $orderColumn, $orderDesc, 1, 1);
        return $result[0];
    }

    /**
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @param int $start
     * @param int $amount
     * @return array[BaseModel]
     */
    public function find($filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        $values     = array();
        $select     = "";
        $join       = "";
        $where      = "";
        $orderby    = "";
        $limit      = "";

        //Select
        foreach($this->fields as $fieldname => $attributes){

            if($attributes['encrypted'] === true){
                $fieldSelect = "CAST(AES_DECRYPT(".$this->table.".".$fieldname.", :db_encryption_key) AS CHAR)";
            }
            else{
                $fieldSelect = $this->table.".".$fieldname;
            }

            if($attributes['type'] === 'date'){
                $fieldSelect = "DATE_FORMAT(".$fieldSelect.", '%d-%m-%Y')";
            }
            elseif($attributes['type'] === 'datetime'){
                $fieldSelect = "DATE_FORMAT(".$fieldSelect.", '%d-%m-%Y %H:%i:%s')";
            }

            $select .= $fieldSelect." AS ".$this->table."_".$fieldname;
            unset($fieldSelect);
        }

        //Filters
        if(isset($filters['id'])){
            $values['filter_id'] = $filters['id'];
            $where[] = $this->table.".id = :filter_id";
        }
        //TODO: verschillende soorten filters (encrypted, varchar (LIKE), _in, not_, _before, _after

        //Order
        if($orderColumn){
            $orderby = "ORDER BY ".$orderColumn." ".($orderDesc ? "DESC" : "ASC").PHP_EOL;
        }

        //Limit
        if($start && $count){
            $limit = "LIMIT ".$start.", ".$count.PHP_EOL;
        }

        $sql = "
            SELECT
                *
            FROM
                ".$this->table."

            ".$join."

            ".($where ? "WHERE ".implode(" AND ", $where) : "")."

            ".$orderby."

            GROUP BY ".$this->table.".id

            ".$limit;

        //TODO: run query

        //TODO: create BaseModel objects with result
    }

    /**
     * @abstract
     * @param BaseModel $model
     * @throws ValidationException, SQLException
     */
    abstract public function save(BaseModel $model);

    /**
     * @param BaseModel $model
     * @throws ValidationException, SqlException
     */
    public function delete(BaseModel $model){

        $values = [
            'id' => $model->id
        ];
        $sql = "
            DELETE FROM
                ".$this->table."
            WHERE
                id = :id";

        //TODO: run query
    }

    /**
     * @param $sql
     * @param $vars
     * @return array[BaseModel]
     * @throws NotImplementedException, SqlException
     */
    public function findBySql($sql, $vars){
        throw new NotImplementedException();

        //TODO: run query
    }
}
?>