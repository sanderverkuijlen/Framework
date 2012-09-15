<?php
/**
 * @method static Product get($id)
 * @method static Product findOne($id)
 * @method static array[Product] find($id)
 * @method static array[Product] findBySql($id)
 */
class Product extends BaseModel{
    use Mapped;

    public $name;
    public $brand;
    public $price;

    private $orders;


    public function __construct($name, $brand, $price, $id = -1){

        parent::__construct($id);

        $this->name     = $name;
        $this->brand    = $brand;
        $this->price    = $price;
    }

    public function orders($refresh = false){

        if($this->orders === null || $refresh){
            $mapper = new OrderMapper();
            $this->orders = $mapper->find( array('product_id' => $this->id) );
        }
        return $this->orders;
    }
}