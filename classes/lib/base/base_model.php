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

    public function __get($name){
        echo printR('get->'.$name);
        $this->$name = 'test';
    }

}
?>