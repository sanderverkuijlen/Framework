<?php

class DbCon{
    use Singleton;

    protected $hostname = DB_HOSTNAME;
    protected $dbName   = DB_NAME;
    protected $dbUser 	= DB_USER;
    protected $dbPwd 	= DB_PASSWORD;

    /**
     * @var dbPDO
     */
    protected $pdo		= null;


    private function __construct(){

        $this->dbConnect();
    }

    public function __destruct(){

        $this->dbDisconnect();
    }


    private function dbConnect(){
        $this->pdo = new dbPDO('mysql:host='.$this->hostname.';dbname='.$this->dbName.';charset=UTF-8', $this->dbUser, $this->dbPwd);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function dbDisconnect(){

        $this->pdo = null;
    }

    /**
     * @param $sql
     * @param array $values
     * @return dbPDOStatement
     * @throws SqlException
     */
    public function query($sql, $values = array()){

        /* @var $pdoStmt dbPDOStatement */
        $pdoStmt = $this->pdo->prepare($sql);
        $pdoStmt = $this->bindValues($pdoStmt, $sql, $values);

        try{
            $pdoStmt->execute();
            return $pdoStmt;
        }
        catch(PDOException $e){

            throw new SqlException($e->getMessage(), $pdoStmt->getSql());
        }
    }


    /**
     * @param $query
     * @param array $values
     * @return String
     */
    public function getSql($query, $values = array()){

        /* @var $pdoStmt dbPDOStatement */
        $pdoStmt = $this->pdo->prepare($query);
        $pdoStmt = $this->bindValues($pdoStmt, $query, $values);

        return $pdoStmt->getSql();
    }

    /**
     * @param dbPDOStatement $pdoStmt
     * @return array
     */
    public function fetch(dbPDOStatement $pdoStmt){

        $aResult = $pdoStmt->fetch(PDO::FETCH_ASSOC);
        return $aResult;
    }

    /**
     * @param dbPDOStatement $pdoStmt
     * @return int
     */
    public function num_rows(dbPDOStatement $pdoStmt){

        return $pdoStmt->rowCount();
    }

    /**
     * @param dbPDOStatement $pdoStmt
     * @return bool
     */
    public function go(dbPDOStatement $pdoStmt){

        return ($pdoStmt->rowCount() > 0);
    }

    /**
     * @return array
     */
    private function error(){

        if($this->pdo != null){
            return $this->pdo->errorInfo();
        }

        return [];
    }

    /**
     * @return int
     */
    public function getLastId(){

        if($this->pdo != null){
            return (int) $this->pdo->lastInsertId();
        }

        return -1;
    }


    /**
     * @param dbPDOStatement $pdoStmt
     * @param $sql
     * @param $values
     * @return dbPDOStatement
     */
    private function bindValues(dbPDOStatement $pdoStmt, $sql, $values){
        $usedKeys = array();
        preg_match_all('/:([a-z0-9_-]+)/i', $sql, $usedKeys);

        $values = array_intersect_key($values, array_flip($usedKeys[1]));

        foreach($values as $key => $value){

            //Bind de waarde met het correcte datatype, anders krijg je vreemde situaties als LIMIT '0','25' die errors geven
            $datatype = PDO::PARAM_STR;
            if(is_int($value)){
                $datatype = PDO::PARAM_INT;
            }
            elseif($value === null){
                $datatype = PDO::PARAM_NULL;
            }

            $pdoStmt->bindValue(':'.$key, $value, $datatype);
        }

        return $pdoStmt;
    }
}
?>