<?
class UserMapper extends BaseMapper{

    protected $table = 'user';

    protected $fields = array(
        'email'     =>  [   'type'      => 'text',
                            'encrypted' => true
                        ],
        'password'  =>  [
                            'type'      => 'text',
                            'encrypted' => true
                        ]
    );

    /**
     * @param array $data
     * @return User
     */
    protected function createObjectFromRow(array $data){

        $user = new User(   $data['email'],
                            $data['password'],
                            $data['id']
                        );
        return $user;
    }

    /**
     * @param User $model
     * @return array
     */
    protected function createArrayFromObject(BaseModel $user){
        /* @var $user User */

        return [
            'id'        => $user->id,
            'email'     => $user->email,
            'password'  => $user->password
        ];
    }
}
?>