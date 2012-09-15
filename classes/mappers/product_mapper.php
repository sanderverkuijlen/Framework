<?
class ProductMapper extends BaseMapper{

    public $collection  = 'products';

    public $fields      = array(

        'name'      =>  [
                            'type'          => TypeEnum::TEXT,
                            'multilanguage' => true
                        ],
        'brand'     =>  [
                            'type'          => TypeEnum::VARCHAR,
                            'multilanguage' => true
                        ],
        'price'     =>  [
                            'type'          => TypeEnum::DECIMAL,
                            'encrypted'     => true
                        ],
        'orders'    =>  [
                            'type'          => TypeEnum::ASSOCIATION_N_N,
                            'class'         => 'Order',
                            'field'         => 'products',
                            'column'        => 'product_id'
                        ]
    );

    /**
     * @param array $data
     * @return Product
     */
    public function createObjectFromArray(array $data){

        $product = new Product(     $data['name'],
                                    $data['brand'],
                                    $data['price'],
                                    $data['id']
                                );
        return $product;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function createArrayFromObject(BaseModel $product){
        /* @var $product Product */

        return [
            'id'        => $product->id,
            'name'      => $product->name,
            'brand'     => $product->brand,
            'price'     => $product->price
        ];
    }
}