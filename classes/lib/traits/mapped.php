<?php
trait Mapped{

    static function get($id){

        /* @var $mapper BaseMapper */
        $mapper = call_user_func(array(get_called_class().'Mapper', 'getInstance'));
        return $mapper->get($id);
    }

    static function findOne($filters = array(), $orderColumn = '', $orderDesc = false){

        /* @var $mapper BaseMapper */
        $mapper = call_user_func(array(get_called_class().'Mapper', 'getInstance'));
        return $mapper->findOne($filters, $orderColumn, $orderDesc);
    }

    static function find($filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        /* @var $mapper BaseMapper */
        $mapper = call_user_func(array(get_called_class().'Mapper', 'getInstance'));
        return $mapper->find($filters, $orderColumn, $orderDesc, $start, $count);
    }

    static function findBySql($sql, $vars){

        /* @var $mapper BaseMapper */
        $mapper = call_user_func(array(get_called_class().'Mapper', 'getInstance'));
        return $mapper->findBySql($sql, $vars);
    }

    function save(){

        /* @var $mapper BaseMapper */
        $mapper = call_user_func(array(get_called_class().'Mapper', 'getInstance'));
        $mapper->save($this);
    }

    function delete(){

        /* @var $mapper BaseMapper */
        $mapper = call_user_func(array(get_called_class().'Mapper', 'getInstance'));
        $mapper->delete($this);
    }
}
?>