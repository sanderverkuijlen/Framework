<?
class CustomerMapper extends BaseMapper{

    public $collection  = 'customers';

    public $fields      = array(
        
        'name'      =>  [
                            'type'          => TypeEnum::TEXT,
                            'encrypted'     => true
                        ],
        'email'     =>  [
                            'type'          => TypeEnum::VARCHAR,
                            'encrypted'     => true
                        ],
        'password'  =>  [
                            'type'          => TypeEnum::TEXT,
                            'encrypted'     => true
                        ],
        'orders'  =>    [
                            'type'          => TypeEnum::ASSOCIATION_1_N,
                            'class'         => 'Order',
                            'field'         => 'customer_id',
                            'column'        => 'customer_id'
                        ]
    );

    /**
     * @param array $data
     * @return Customer
     */
    public function createObjectFromArray(array $data){

        $customer = new Customer(   $data['name'],
                                    $data['email'],
                                    $data['password'],
                                    $data['id']
                                );
        return $customer;
    }

    /**
     * @param Customer $customer
     * @return array
     */
    public function createArrayFromObject(BaseModel $customer){
        /* @var $customer Customer */

        return [
            'id'        => $customer->id,
            'name'      => $customer->name,
            'email'     => $customer->email,
            'password'  => $customer->password
        ];
    }
}