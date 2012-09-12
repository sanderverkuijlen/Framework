<?php
/**
 * @method static User get($id)
 * @method static User findOne($id)
 * @method static array[User] find($id)
 * @method static array[User] findBySql($id)
 */
class User extends BaseModel{
    use Mapped;

    public $email;
    public $password;

    public function __construct($email, $password, $id = -1){

        parent::__construct($id);

        $this->email    = $email;
        $this->password = $password;
    }
}
?>