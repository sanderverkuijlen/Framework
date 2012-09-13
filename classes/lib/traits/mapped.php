<?php
trait Mapped{

    /**
     * @static
     * @param $id
     * @return BaseModel
     */
    static function get($id){

        /* @var $mapper BaseMapper */
        $className = get_called_class().'Mapper';
        $mapper = new $className;
        return $mapper->get($id);
    }

    /**
     * @static
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @return BaseModel
     */
    static function findOne($filters = array(), $orderColumn = '', $orderDesc = false){

        /* @var $mapper BaseMapper */
        $className = get_called_class().'Mapper';
        $mapper = new $className;
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
     */
    static function find($filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        /* @var $mapper BaseMapper */
        $className = get_called_class().'Mapper';
        $mapper = new $className;
        return $mapper->find($filters, $orderColumn, $orderDesc, $start, $count);
    }

    /**
     * @static
     * @param $sql
     * @param $vars
     * @return array[BaseModel]
     */
    static function findBySql($sql, $vars){

        /* @var $mapper BaseMapper */
        $className = get_called_class().'Mapper';
        $mapper = new $className;
        return $mapper->findBySql($sql, $vars);
    }

    /**
     * @throws ValidationException
     */
    function save(){

        /* @var $mapper BaseMapper */
        $className = get_called_class().'Mapper';
        $mapper = new $className;
        $mapper->save($this);
    }

    /**
     * @throws ValidationException
     */
    function delete(){

        /* @var $mapper BaseMapper */
        $className = get_called_class().'Mapper';
        $mapper = new $className;
        $mapper->delete($this);
    }
}
?>