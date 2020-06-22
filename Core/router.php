<?php
namespace Core;
use Traits\log;
use Traits\session;
use Traits\loadController;
use Traits\render;

class router {
    use log;
    use session;
    use loadController;
    use render;

    private $variables = [];
    private $urlStatus = false;
    private static $listOfVars = [];
    private static $pagePath = '/home';
    public static $loadComplete = false;

    /**
     * router constructor.
     * @param bool $urlStatus
     * @param array $variables
     * getting urlStatus this will be true or false depending if the request url and get or post url matches
     */
    public function __construct ($urlStatus=false, $variables=[]) {
        $this->variables = $variables;
        $this->urlStatus = $urlStatus;
    }

    public function __destruct() {
        // TODO: Implement __destruct() method.
    }

    /**
     * @param null $fileName
     * @return null
     * this function is used in routes folder like -> self::get(...)->view(filename);
     * and you can chane Views self::get(...)->view(filename)->view(filename)->view(filename)
     */
    public function view ($fileName=null, $parameters=null) {
        if (is_null($fileName)) {
            self::render(ERROR404);
            return self::error('file name empty.');
        }

        self::render(VIEW_PATH . $fileName . '.php', $parameters);
        return $this;
    }


    /**
     * @param null $url
     * @param null $callback
     * @param string $type
     * @return router
     * getting all of the requests and sorting get, post request. ($_REQUEST['cont] can be used if there is no use for $_GET or $_POST).
     */
    private static function request ($url=null, $callback=null, $type='get') {
        if (self::$loadComplete === true) exit();

        if ($type === 'get')  {
            $requestContent = array_key_exists('cont', $_GET) ? $_GET['cont'] : self::error('install .htaccess file.');
        } else if ($type === 'post') {
            $requestContent = array_key_exists('cont', $_POST) ? $_POST['cont'] : self::error('install .htaccess file.');
        }

        $result = self::compareRequest($requestContent, $url);
        $variables = self::getUrlVariables();
        $router = new router($result, $variables);

        if (is_null($callback) && $result === true) {
            self::loadController('get', self::$pagePath, $variables);
            return $router;
        }

        if ($_GET['cont'] === "" && ($url === "" || $url === "/" ) || $result === true) {
            $callback($variables);
        }

        return $router;
    }

    /**
     * @param null $url
     * @param null $callback
     * @return router
     * get request is getting $_GET content.
     * testing if url matches and returning response.
     * it hse callBack function witch will get variables from url like -> /home/list/${variableName1}/${variableName2}
     */
    public static function get ($url=null, $callback=null) {
        return self::request($url, $callback);
    }

    /**
     * @param null $url
     * @param null $callback
     * @return router
     * get request is getting $_POST content.
     * testing if url matches and returning response.
     * it hse callBack function witch will get variables from url like -> /home/list/${variableName1}/${variableName2}
     * and from post as well.
     */
    public static function post ($url=null, $callback=null) {
        return self::request($url, $callback, 'post');
    }

    /**
     * @return array
     * returning variables from url.
     */
    protected static function getUrlVariables () {
        return self::$listOfVars;
    }

    /**
     * @param null $url
     * @param null $request
     * @return bool|null|string|string[]
     * this method saves the path to the url in pagePath var and using it to get controller/model/view files.
     */
    protected static function setUrlPath ($url=null, $request=null) {
        if (is_null($url) && is_null($request)) return self::error('variables are empty');

        if ($request === "" && $url === "/" || '/'.$request === self::$pagePath) {
            return null;
        }

        $realUrl = preg_replace('/([\/][$]{(\w+)})/', '', $url);
        $realUrl = $realUrl[0] === "/" ? substr($realUrl, 1) : $realUrl;

        self::$pagePath = $realUrl;
        return $realUrl;
    }

    /**
     * @param null $request
     * @param null $url
     * @return bool|null
     * compering real url with was passed from get or post methods.
     */
    protected static function compareRequest ($request=null, $url=null) {
        if (is_null($request) && is_null($url)) return null;

        if ($request === "" && $url === "/" || '/'.$request === self::$pagePath) {
            return true;
        }

        $realUrl = self::setUrlPath($url, $request);
        $realUrlVariableCount = preg_match_all("/([\/][$]{(\w+)})/", $url);
        $requestUrlCunt = preg_match_all('/(\w+)/', $request);

        $requestList = explode('/', $request);
        array_splice($requestList, $requestUrlCunt - $realUrlVariableCount, $requestUrlCunt);

        if ($realUrl === implode('/', $requestList)) {
            $variableList = array_slice(explode('/', str_replace($realUrl, '', $request)), 1);
            $variableKeyList = array_slice(explode('/', preg_replace('/[${}]/', '', str_replace($realUrl, '', $url))), 2);
            if (count($variableList) !== count($variableKeyList)) return false;

            self::$listOfVars = array_combine($variableKeyList, $variableList);

            if (count($variableList) > count(self::$listOfVars)) self::error('passed variable names are same. router::get("/.../${same}/${same}")');

            self::$loadComplete = true;

            return true;
        }

        return false;
    }
}