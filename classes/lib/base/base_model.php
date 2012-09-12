<?
abstract class BaseModel{

    public $id;

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
?>