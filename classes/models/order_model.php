<?php
/**
 * @method static Order get($id)
 * @method static Order findOne($id)
 * @method static array[Order] find($id)
 * @method static array[Order] findBySql($id)
 */
class Order extends BaseModel{
    use Mapped;

    public $customer_id;
    public $date;

    private $customer;
    private $products;


    public function __construct($customer_id, $date, $id = -1){

        parent::__construct($id);

        $this->customer_id  = $customer_id;
        $this->date         = $date;
    }

    public function customer($refresh = false){

        if($this->customer === null || $refresh){
            $mapper = new CustomerMapper();
            $this->customer = $mapper->get($this->customer_id);
        }
        return $this->customer;
    }

    public function products($refresh = false){

        if($this->products === null || $refresh){
            $mapper = new ProductMapper();
            $this->products = $mapper->find( array('order_id' => $this->id) );
        }
        return $this->products;
    }

    public function addProduct(Product $product){

        $mapper = new OrderMapper();
        $mapper->addAssociation($this, $product, 'products');
    }

    public function removeProduct(Product $product){

        $mapper = new OrderMapper();
        $mapper->removeAssociation($this, $product, 'products');
    }
}
?>