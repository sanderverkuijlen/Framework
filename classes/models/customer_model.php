<?php
/**
 * @method static Customer get($id)
 * @method static Customer findOne($id)
 * @method static array[Customer] find($id)
 * @method static array[Customer] findBySql($id)
 */
class Customer extends BaseModel{
    use Mapped;

    public $name;
    public $email;
    public $password;

    private $orders;


    public function __construct($name, $email, $password, $id = -1){

        parent::__construct($id);

        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
    }

    public function orders($refresh = false){

        if($this->orders === null || $refresh){
            $mapper = new OrderMapper();
            $this->orders = $mapper->find( array('customer_id' => $this->id) );
        }
        return $this->orders;
    }
}