<?
abstract class BaseModel{

    public $id;

    /**
     * Used to store the initial state of the object, so it can later be retrieved to check dirty attributes
     * @var array
     */
    public $__initial;


    public function __construct($id){
        $this->id = $id;
    }


    /**
     * @throws ValidationException
     */
    public function validate(){
    }

    /**
     * @throws ValidationException
     */
    public function validateDelete(){
    }

}