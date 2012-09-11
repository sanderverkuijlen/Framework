<?
abstract class BaseMapper{

    protected $table = '';

    protected $fields = array();

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
    public function findOne($filters = array(), $order_column = '', $order_desc = false){

        $result = $this->find($filters, $order_column, $order_desc, 1, 1);
        return $result[0];
    }

    /**
     * @param array $filters
     * @param string $order_column
     * @param bool $order_desc
     * @param int $start
     * @param int $amount
     * @return array[BaseModel]
     */
    public function find($filters = array(), $order_column = '', $order_desc = false, $start = null, $amount = null){

        $values     = array();
        $select     = "";
        $join       = "";
        $where      = "";
        $orderby    = "";
        $limit      = "";

        //TODO: fix de select
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

        if(isset($filters['id'])){
            $values['filter_id'] = $filters['id'];
            $where[] = $this->table.".id = :filter_id";
        }
        //TODO: verschillende soorten filters (encrypted, varchar (LIKE), _in, not_

        if($order_column){
            $orderby = "ORDER BY ".$order_column." ".($order_desc ? "DESC" : "ASC").PHP_EOL;
        }
        if($start && $amount){
            $orderby = "ORDER BY ".$order_column." ".($order_desc ? "DESC" : "ASC").PHP_EOL;
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

        //TODO: create UserModel objects with result
    }

    /**
     * @abstract
     * @param BaseModel $model
     * @throws ValidationException, SQLException
     */
    abstract public function save(BaseModel $model);

    /**
     * @abstract
     * @param BaseModel $model
     * @throws ValidationException, SqlException
     */
    abstract public function delete(BaseModel $model);

    /**
     * @param $sql
     * @param $vars
     * @throws NotImplementedException, SqlException
     */
    public function findBySql($sql, $vars){
        throw new NotImplementedException();
    }
}
?>