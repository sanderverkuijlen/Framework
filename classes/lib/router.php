<?
class Router{

    /**
     * @var Router
     */
    private static $instance;
    /**
     * @var array
     */
    private $routes;

    private function __construct(){
        $this->routes = [];
    }

    /**
     * @static
     * @return Router
     */
    public static function getInstance(){

        if(!self::$instance){
            self::$instance = new Router();
        }

        return self::$instance;
    }

    /**
     * @param $route
     * @param $params
     * @param array $requirements
     * @param null $shortcut
     * @throws NotImplementedException
     */
    public function register($route, $params, $requirements = array(), $shortcut = null){

        throw new NotImplementedException();
        //TODO: add route registering

    }

    /**
     * @param $url
     * @throws NotImplementedException
     */
    public function routeUrl($url){

        throw new NotImplementedException();
        //TODO: Route $url to the correct controller
    }

    /**
     * @param $controller
     * @param $params
     * @return string
     * @throws NotImplementedException
     */
    public function urlFor($controller, $params){

        throw new NotImplementedException();
        //TODO: add route building

        //return '';
    }

    /**
     * @param $controller
     * @param $params
     */
    public function redirectTo($controller, $params){

        $url = $this->urlFor($controller, $params);

        header('Location: '.$url);
        die();
    }
}