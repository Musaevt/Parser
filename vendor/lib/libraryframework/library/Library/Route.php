<?php
namespace Library;

class Route{
    const DEFAULT_CONTROLLER = 'Index';
    const CONTROLLER_NOT_FOUND = 'Err404';
    const DEFAULT_ACTION = 'index';

    private $post_data;

    private $get_data;

    protected $request_uri;

    protected $clear_request_uri;

    protected $module_name;
  function __construct() {
        $this->request_uri = $_SERVER['REQUEST_URI'];
        $this->clear_request_uri = preg_replace("/(\?.*)/i", '', $this->request_uri);
        $this->post_data = $this->createPostData();
        $this->get_data = $this->createGetData();
    }

    public function getControllerName() {
        $broken_uri = explode('/', $this->clear_request_uri);
        $controller_name = null;
        if (isset($broken_uri[1]) && !empty($broken_uri[1])) {
            $controller_name = $this->findController($broken_uri[1]);
            if (!$controller_name) {
                $controller_name = Route::CONTROLLER_NOT_FOUND;
            }
        } else {
            $controller_name = Route::DEFAULT_CONTROLLER;
        }
        $this->module_name = $controller_name;
        return $controller_name;
    }

    public function getActionName() {
        $broken_uri = explode('/', $this->clear_request_uri);
        $action_name = null;
        if (isset($broken_uri[2]) && !empty($broken_uri[2])) {
            $action_name = $this->findAction($broken_uri[2]);
            if (!$action_name) {
                $action_name = Route::DEFAULT_ACTION;
            }
        } else {
            $action_name = Route::DEFAULT_ACTION;
        }
        return $action_name;
    }

    public function getParams() {
        return array(
            'post' => $this->getPostData(),
            'get' => $this->getGetData()
        );
    }

    public function toRoute($route) {
        header('Location: '. $route);
        exit();
    }
   private function createPostData() {
        return $_POST;
    }

    private function createGetData() {
        $get_data = array();
        foreach ($_GET as $key => $value) {
            if ($key == 'r') continue; //route key in .htaccess
            $get_data[$key] = $value;
        }
        return $get_data;
    }

    protected function getPostData() {
        return $this->post_data;
    }

    protected function getGetData() {
        return $this->get_data;
    }

    protected function findController($controller_key) {
        $modules = Application::$config['modules'];
        if (array_key_exists($controller_key, $modules)) {
            return $modules[$controller_key];
        }
        return !1;
    }

    protected function findAction($action_key) {
        if ($this->module_name != 'Err404') {
            $modules_path = Q_PATH.Application::$config['module_options']['module_path'];
            $module_config = require $modules_path.'/'.$this->module_name.'/config/module.config.php';
            $actions = $module_config['actions'];
            if (is_array($actions) && array_key_exists($action_key, $actions)) {
                return $actions[$action_key];
            }
        }
        return !1;
    }
	
}