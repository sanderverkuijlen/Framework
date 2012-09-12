<?php
class ClassLoader{

    /**
     * @var ClassLoader
     */
    static private $instance    = null;
    /**
     * @var array
     */
    static private $classMap    = array();

    private function __construct(){
        $this->loadIncludeFile();
    }

    /**
     * @static
     * @return ClassLoader
     */
    static public function getInstance(){

        if(self::$instance == null){
            self::$instance = new ClassLoader();
        }
        return self::$instance;
    }

    public function loadIncludeFile(){
        $classMap = array();

        require_once(FILE_ROOT.'/lib/autoload.inc.php');

        self::$classMap = $classMap;
    }

    /**
     * @param $className
     */
    public function loadClass($className){
        require_once(FILE_ROOT.self::$classMap[$className]);
    }
}

function __autoload($className){
    $oOL = ClassLoader::getInstance();
    $oOL->loadClass($className);
}
?>