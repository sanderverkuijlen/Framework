<?
class QueryException extends Exception{

    /**
     * @var string
     */
    private $query;

    /**
     * @param string $message
     * @param int $query
     */
    public function __construct($message, $query){

        parent::__construct($message);
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery(){
        return $this->query;
    }
}