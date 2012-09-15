<?php
/**
 * PDO en PDOStatement wrappers om debugging gemakkelijker te maken.
 * Aangepaste versie van onderstaande source.
 *
 * Source: http://daveyshafik.com/archives/605-debugging-pdo-prepared-statements.html
 */

class dbPDO extends PDO{

    public function __construct($dsn, $username = null, $password = null, $driverOptions = array())
    {
        parent::__construct($dsn, $username, $password, $driverOptions);
        $this->setAttribute( PDO::ATTR_STATEMENT_CLASS, array('dbPDOStatement', array($this)));
    }
}

class dbPDOStatement extends PDOStatement{

    private $dbPDO;
    private $boundValues;

    /**
     * @param dbPDO $dbPDO
     */
    protected function __construct(dbPDO $dbPDO){
        $this->dbPDO        = $dbPDO;
        $this->boundValues  = array();
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $datatype
     * @return bool
     */
    public function bindValue($key, $value, $datatype = PDO::PARAM_STR)
    {
        $this->boundValues[$key] = array(
            'value' => $value,
            'type' => $datatype
        );
        return parent::bindValue($key, $value, $datatype);
    }

    /**
     * Functie voor het retourneren van de opgebouwde SQL op basis van de gevens van deze dbPDOStatement.
     * LET OP: Het opbouwen van de query wordt normaal gesproken door de database gedaan, daarom kunnen er theoretisch gezien verschillen zijn tussen deze sql-query en wat de database er van maakt.
     * Beter dan niets dus, maar ga er niet van uit dat deze sql gelijk is aan wat de server ervan maakt.
     *
     * @return String
     */
    public function getSql()
    {
        $sql = $this->queryString;

        foreach ($this->boundValues as $key => $param) {

            //Value ophalen
            $value = $param['value'];

            //Value casten (zo nodig)
            if(!is_null($param['type'])){
                $value = self::cast($value, $param['type']);
            }
            if($param['type'] == PDO::PARAM_STR){
                $value = $this->dbPDO->quote($value);
            }

            //Query opbouwen
            if(!is_null($value)){
                $sql = str_replace($key, $value, $sql);
            }
            else{
                $sql = str_replace($key, 'NULL', $sql);
            }
        }

        return $sql;
    }

    /**
     * @param $value $value
     * @param $type $type
     * @return mixed
     */
    static protected function cast($value, $type)
    {
        switch ($type){

            case PDO::PARAM_BOOL:
                return (bool) $value;
                break;

            case PDO::PARAM_NULL:
                return null;
                break;

            case PDO::PARAM_INT:
                return (int) $value;

            case PDO::PARAM_STR:
            default:
                return $value;
        }
    }
}