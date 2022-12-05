<?php
class App
{
    protected $controller = DEFAULT_CONTROLLER;
    protected $method = DEFAULT_CONTROLLER_ACTION;
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();
        // controller
        if (isset($url) && file_exists(CONTROLLER_PATH . $url[0] . ".php")) {
            $this->controller = $url[0];
            unset($url[0]);
        }


        require_once CONTROLLER_PATH . $this->controller . ".php";
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        if (!empty($url)) {
            $this->params = array_values($url);
        }
        // var_dump($this->controller, $this->$params, $this->method);
        call_user_func_array([$this->controller, $this->method], array($this->params));
    }

    public function parseURL()
    {
        if (isset($_GET["url"])) {
            $url = $_GET['url'];
            $url = rtrim($_GET["url"], "/");
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode("/", $url);
            return $url;
        }
    }
}
