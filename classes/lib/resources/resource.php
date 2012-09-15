<?php
abstract class Resource{

    abstract public function find(BaseMapper $mapper, $filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null);
    abstract public function findByQuery(BaseMapper $mapper, $query, $values);

    abstract public function save(BaseMapper $mapper, BaseModel $model);
    abstract public function delete(BaseMapper $mapper, BaseModel $model);

    abstract public function addAssociation(BaseMapper $mapper, BaseModel $model, BaseModel $association, $field);
    abstract public function removeAssociation(BaseMapper $mapper, BaseModel $model, BaseModel $association, $field);

    abstract public function count(BaseMapper $mapper, $filters = array());
}