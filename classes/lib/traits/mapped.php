<?php
trait Mapped{

    /**
     * @static
     * @param $id
     * @return BaseModel
     * @throws ClassNotFoundException, SQLException
     */
    static public function get($id){

        $mapper = BaseMapper::getMapperForClass(get_called_class());
        return $mapper->get($id);
    }

    /**
     * @static
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @return BaseModel
     * @throws ClassNotFoundException, SQLException
     */
    static public function findOne($filters = array(), $orderColumn = '', $orderDesc = false){

        $mapper = BaseMapper::getMapperForClass(get_called_class());
        return $mapper->findOne($filters, $orderColumn, $orderDesc);
    }

    /**
     * @static
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @param int $start
     * @param int $count
     * @return array[BaseModel]
     * @throws ClassNotFoundException, SQLException
     */
    static public function find($filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        $mapper = BaseMapper::getMapperForClass(get_called_class());
        return $mapper->find($filters, $orderColumn, $orderDesc, $start, $count);
    }

    /**
     * @static
     * @param $sql
     * @param $vars
     * @return array[BaseModel]
     * @throws ClassNotFoundException, SQLException
     */
    static public function findBySql($sql, $vars){

        $mapper = BaseMapper::getMapperForClass(get_called_class());
        return $mapper->findByQuery($sql, $vars);
    }

    /**
     * @throws ValidationException, ClassNotFoundException, SQLException
     */
    public function save(){

        $mapper = BaseMapper::getMapperForClass(get_called_class());
        $mapper->save($this);
    }

    /**
     * @throws ValidationException, ClassNotFoundException, SQLException
     */
    public function delete(){

        $mapper = BaseMapper::getMapperForClass(get_called_class());
        $mapper->delete($this);
    }
}