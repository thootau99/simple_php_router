<?php
    class Router {
        private $routes = array();
        function addPath ($method, $path, $handler) {
            array_push($this -> routes, array("method"=> $method, "path"=>$path, "handler"=>$handler));
        }
        function get($path, $handler) {
            $this -> addPath("GET", $path, $handler);
        }
        function post($path, $handler) {
            $this -> addPath("POST", $path, $handler);
        }
        function put($path, $handler) {
            $this -> addPath("PUT", $path, $handler);
        }
        function delete($path, $handler) {
            $this -> addPath("DELETE", $path, $handler);
        }
        function findApi($method, $path) {
            foreach($this -> routes as $route) {
                if ($route["method"] === $method && $route["path"] === $path) {
                    return $route;
                }
            }
            return false;
        }
        function handle($path) {
            $method = $_SERVER['REQUEST_METHOD'];
            $api = $this -> findApi($method, $path);
            if ($api) {
                /* API EXISTS. */
                switch ($method) {
                    case "GET":
                        $api["handler"]($_GET);
                        break;
                    case "POST":
                        $api["handler"]($_POST);
                        break;
                    case ($method === "DELETE" || $method === "PUT"):
                        $json_body = json_decode(file_get_contents('php://input'), true);
                        $api["handler"]($json_body); 
                        break;
                }
            } else {
                http_response_code(404);
                echo "can not found this api.";
            }
        }
    }
?>