<?
abstract class BaseMapper{

    public $collection  = '';
    public $fields      = array();

    /* @var $resource Resource */
    protected $resource;

    public function __construct($resource_name = null){

        /* @var $config Config */
        $config = Config::getInstance();

        if($resource_name != null){
            $this->resource = $config->getResource($resource_name);
        }
        else{
            $this->resource = $config->getDefaultResource();
        }

        $this->fields['id'] = [
            'type'      => TypeEnum::INT,
            'primary'   => true
        ];
    }


    /**
     * @param $id
     * @return BaseModel|null
     * @throws QueryException
     */
    public function get($id){

        return $this->resource->find($this, array( 'id' => $id ), '', '', 0, 1)[0];
    }

    /**
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @return BaseModel|null
     * @throws QueryException
     */
    public function findOne($filters = array(), $orderColumn = '', $orderDesc = false){

        return $this->resource->find($this, $filters, $orderColumn, $orderDesc, 0, 1)[0];
    }

    /**
     * @param array $filters
     * @param string $orderColumn
     * @param bool $orderDesc
     * @param null $start
     * @param null $count
     * @return array[BaseModel]
     * @throws QueryException
     */
    public function find($filters = array(), $orderColumn = '', $orderDesc = false, $start = null, $count = null){

        return $this->resource->find($this, $filters, $orderColumn, $orderDesc, $start, $count);
    }

    /**
     * @param array $filters
     * @return int
     * @throws QueryException
     */
    public function count($filters = array()){

        return $this->resource->count($this, $filters);
    }

    /**
     * @param string $query
     * @param array $values
     * @return array[BaseModel]
     * @throws QueryException
     */
    public function findByQuery($query, $values){

        return $this->resource->findByQuery($this, $query, $values);
    }


    /**
     * @param BaseModel $model
     * @throws ValidationException, SQLException
     */
    public function save(BaseModel $model){

        $model->validate();

        $this->resource->save($this, $model);
    }

    /**
     * @param BaseModel $model
     * @throws ValidationException, QueryException
     */
    public function delete(BaseModel $model){

        $model->validateDelete();

        $this->resource->delete($this, $model);
    }

    /**
     * @param BaseModel $model
     * @param BaseModel $associate
     * @param $field
     */
    public function addAssociation(BaseModel $model, BaseModel $associate, $field){

        $this->resource->addAssociation($this, $model, $associate, $field);
    }

    /**
     * @param BaseModel $model
     * @param BaseModel $associate
     * @param $field
     */
    public function removeAssociation(BaseModel $model, BaseModel $associate, $field){

        $this->resource->removeAssociation($this, $model, $associate, $field);
    }


    /**
     * @abstract
     * @param array $data
     * @return BaseModel
     */
    abstract public function createObjectFromArray(array $data);

    /**
     * @abstract
     * @param BaseModel $model
     * @return array
     */
    abstract public function createArrayFromObject(BaseModel $model);


    /**
     * @param $fieldName
     * @return string|bool
     */
    public function getOppositeAssociation($fieldName){

        $attributes = $this->fields[$fieldName];

        //If $field isn't an association do nothing
        if( in_array($attributes['type'], [TypeEnum::ASSOCIATION_1_1, TypeEnum::ASSOCIATION_1_N, TypeEnum::ASSOCIATION_N_1, TypeEnum::ASSOCIATION_N_N])){

            //If $field['class'] isn't set then we can't do anything
            if(array_key_exists('class', $attributes)){

                /* @var $mapper BaseMapper */
                $mapper = self::getMapperForClass($attributes['class']);

                //We can't do anything without a mapper
                if($mapper !== null){

                    //If $field['field'] isn't set then we can't do anything
                    if(array_key_exists($attributes['field'], $mapper->fields)){

                        return $mapper->fields[$attributes['field']];
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param $class
     * @return BaseMapper
     * @throws ClassNotFoundException
     */
    static public function getMapperForClass($class){

        $class = $class.'Mapper';
        return new $class;
    }
}

abstract class TypeEnum{
    const ID                        = 1;
    const TEXT                      = 2;
    const VARCHAR                   = 3;
    const INT                       = 4;
    const DECIMAL                   = 5;
    const BOOL                      = 6;
    const DATE                      = 7;
    const DATETIME                  = 8;
    const ASSOCIATION_1_1           = 9;
    const ASSOCIATION_1_N           = 10;
    const ASSOCIATION_N_1           = 11;
    const ASSOCIATION_N_N           = 12;
}