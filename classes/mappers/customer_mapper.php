<?
class CustomerMapper extends BaseMapper{

    public $table = 'customers';

    public $fields = array(
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
                            'type'          => TypeEnum::ASSOCIATION_HAS_MANY,
                            'class'         => 'Order',
                            'associated'    => 'products',
                            'field'         => 'customer'
                        ]
    );

    /**
     * @param array $data
     * @return Customer
     */
    protected function createObjectFromRow(array $data){

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
    protected function createArrayFromObject(BaseModel $customer){
        /* @var $customer Customer */

        return [
            'id'        => $customer->id,
            'email'     => $customer->email,
            'password'  => $customer->password
        ];
    }
}
?>