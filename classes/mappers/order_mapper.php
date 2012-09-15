<?
class OrderMapper extends BaseMapper{

    public $collection  = 'orders';

    public $fields      = array(
        
        'customer_id'  =>  [
                            'type'          => TypeEnum::ASSOCIATION_N_1,
                            'class'         => 'Customer',
                            'field'         => 'orders',
                            'column'        => 'order_id'
                        ],
        'products'  =>  [
                            'type'          => TypeEnum::ASSOCIATION_N_N,
                            'class'         => 'Product',
                            'field'         => 'orders',
                            'column'        => 'order_id'
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
    public function createObjectFromArray(array $data){

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
    public function createArrayFromObject(BaseModel $order){
        /* @var $order Order */

        return [
            'id'            => $order->id,
            'customer_id'   => $order->customer_id,
            'date'          => $order->date
        ];
    }
}