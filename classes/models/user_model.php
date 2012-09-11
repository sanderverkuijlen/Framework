<?
class User extends BaseModel{

    public $email;
    public $password;

    public function __construct($email, $password, $id = -1){

        parent::__construct($id);

        $this->email    = $email;
        $this->password = $password;
    }

}
?>