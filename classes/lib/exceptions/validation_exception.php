<?
class ValidationException extends Exception{

    private $field_messages = array();

    public function __construct($message, $field_messages = array()){
        parent::__construct($message);
        $this->field_messages = $field_messages;
    }

    public function addFieldMessage($field, $message){
        $this->field_messages[$field][] = $message;
    }

    public function getFieldMessages(){
        return $this->field_messages;
    }
}
?>