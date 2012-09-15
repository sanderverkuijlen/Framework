<?php
class Config{
    use Singleton;

    private $defaultResource;
    private $resources;


    /**
     * @param $name
     * @param Resource $dataSource
     */
    public function addResource($name, Resource $dataSource){
        $this->resources[$name] = $dataSource;
    }

    /**
     * @param $name
     * @return Resource
     */
    public function getResource($name){
        return $this->resources[$name];
    }

    /**
     * @return Resource
     */
    public function getDefaultResource(){
        return $this->resources[$this->defaultResource];
    }

    /**
     * @param $defaultResource
     */
    public function setDefaultResource($defaultResource){
        $this->defaultResource = $defaultResource;
    }
}