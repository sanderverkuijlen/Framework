<?
class ProductMapper extends BaseMapper{

    public $table = 'products';

    public $fields = array(
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
                            'type'          => TypeEnum::ASSOCIATION_HAS_MANY,
                            'class'         => 'Order',
                            'associated'    => 'products',
                            'field'         => 'product_id'
                        ]
    );

    /**
     * @param array $data
     * @return Product
     */
    protected function createObjectFromRow(array $data){

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
    protected function createArrayFromObject(BaseModel $product){
        /* @var $product Product */

        return [
            'id'        => $product->id,
            'name'      => $product->name,
            'brand'     => $product->brand,
            'price'     => $product->price
        ];
    }
}
?>