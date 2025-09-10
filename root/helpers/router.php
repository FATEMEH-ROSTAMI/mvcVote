<?php
class Route {
    public function __construct() {
        $url = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
        if (empty($url)) {
            $url = 'login';
        }

        $urls_array = [
            [
                'url' => '/^home$/',
                'controller' => 'PollController',
                'action' => 'index',
                'type' => 'GET'
            ],

            [
                'url' => '/^login$/',
                'controller' => 'AuthController',
                'action' => 'showLogin',
                'type' => 'GET'
            ],
            [
                'url' => '/^login$/',
                'controller' => 'AuthController',
                'action' => 'login',
                'type' => 'POST'
            ],
            [
                'url' => '/^register$/',
                'controller' => 'AuthController',
                'action' => 'showRegister',
                'type' => 'GET'
            ],
            [
                'url' => '/^register$/',
                'controller' => 'AuthController',
                'action' => 'register',
                'type' => 'POST'
            ],
            [
                'url' => '/^logout$/',
                'controller' => 'AuthController',
                'action' => 'logout',
                'type' => 'GET'
            ],

            [
                'url' => '/^polls$/',
                'controller' => 'PollController',
                'action' => 'index',
                'type' => 'GET'
            ],
            [
                'url' => '/^poll\/(\d+)$/',
                'controller' => 'PollController',
                'action' => 'showPoll',
                'type' => 'GET'
            ],
            [
                'url' => '/^vote$/',
                'controller' => 'PollController',
                'action' => 'vote',
                'type' => 'POST'
            ],

            [
                'url' => '/^dashboard$/',
                'controller' => 'AdminController',
                'action' => 'dashboard',
                'type' => 'GET'
            ],
            [
                'url' => '/^admin\/users$/',
                'controller' => 'AdminController',
                'action' => 'listUsers',
                'type' => 'GET'
            ],
            [
                'url' => '/^admin\/create-poll$/',
                'controller' => 'AdminController',
                'action' => 'showCreatePoll',
                'type' => 'GET'
            ],
            [
                'url' => '/^admin\/create-poll$/',
                'controller' => 'AdminController',
                'action' => 'createPoll',
                'type' => 'POST'
            ]
        ];

        $route_fail = true;

        foreach ($urls_array as $url_arr) {
            if (
                preg_match($url_arr['url'], $url, $matches) &&
                $url_arr['type'] == $_SERVER['REQUEST_METHOD']
            ) {
                $route_fail = false;
                unset($matches[0]);

                include 'controllers/' . $url_arr['controller'] . '.php';
                $controller = new $url_arr['controller'];

                $result = call_user_func_array([$controller, $url_arr['action']], array_values($matches));

                if (is_array($result)) {
                    if (isset($result['redirect'])) {
                        header('Location: ' . $result['redirect']);
                        exit;
                    }
                    if (isset($result['view'])) {
                        $view_file = __DIR__ . '/views/' . $result['view'];
                        if (file_exists($view_file)) {
                            extract($result);
                            include $view_file;
                        } else {
                            echo "خطا: فایل ویو {$result['view']} پیدا نشد";
                        }
                    } elseif (isset($result['message'])) {
                        echo $result['message'];
                    }
                }
                break;
            }
        }

        if ($route_fail) {
            http_response_code(404);
            echo "(404) صفحه پیدا نشد";
        }
    }
}
