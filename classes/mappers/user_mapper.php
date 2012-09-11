<?
class UserMapper extends BaseMapper{

    protected $table = 'user';

    protected $fields = array(
        'email'     =>  [   'type'      => 'varchar',
                            'required'  => true,
                            'unique'    => true
                        ],
        'password'  =>  [
                            'type'      => 'text',
                            'required'  => true
                        ]
    );

    /**
     * @param BaseModel $model
     * @throws ValidationException, SQLException
     */
    public function save(BaseModel $model){

    }

    /**
     * @param BaseModel $model
     * @throws ValidationException, SqlException
     */
    public function delete(BaseModel $model){

    }

    /**
     * @param $sql
     * @param $vars
     * @throws NotImplementedException, SqlException
     */
    public function findBySql($sql, $vars){
        throw new NotImplementedException();
    }
}
?>