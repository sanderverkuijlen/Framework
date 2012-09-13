<?
class OrderMapper extends BaseMapper{

    public $table = 'orders';

    public $fields = array(
        'customer_id'  =>  [
                            'type'          => TypeEnum::ASSOCIATION_HAS_ONE,
                            'class'         => 'Customer',
                            'field'         => 'orders'
                        ],
        'products'  =>  [
                            'type'          => TypeEnum::ASSOCIATION_HAS_MANY,
                            'class'         => 'Product',
                            'associated'    => 'orders',
                            'field'         => 'order_id'
                        ],
        'date'      =>  [
                            'type'          => TypeEnum::DATE
                        ]
    );

//    public $customer_id;
//    public $date;
//
//    public $customer;
//    public $orderlines;

    /**
     * @param array $data
     * @return Order
     */
    protected function createObjectFromRow(array $data){

        $order = new Order(     $data['customer_id'],
                                $data['date'],
                                $data['id']
                            );
        return $order;
    }

    /**
     * @param Order $order
     * @return array
     */
    protected function createArrayFromObject(BaseModel $order){
        /* @var $order Order */

        return [
            'id'            => $order->id,
            'customer_id'   => $order->customer_id,
            'date'          => $order->date
        ];
    }
}
?>