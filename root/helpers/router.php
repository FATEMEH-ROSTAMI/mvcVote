<?php
class Route {
    public function __construct() {
        // گرفتن مسیر از URL
        $url = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
        if (empty($url)) {
            $url = 'home'; // مسیر پیش‌فرض
        }

        // تعریف مسیرها
        $urls_array = [
            // مسیرهای عمومی
            [
                'url' => '/^home$/',
                'controller' => 'PollController',
                'action' => 'index',
                'type' => 'GET'
            ],
            // مسیرهای احراز هویت
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
                'type' => 'POST'
            ],
            // مسیرهای نظرسنجی
            [
                'url' => '/^poll$/',
                'controller' => 'PollController',
                'action' => 'index',
                'type' => 'GET'
            ],
            [
                'url' => '/^poll\/create$/',
                'controller' => 'PollController',
                'action' => 'create',
                'type' => 'POST'
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
            // مسیرهای ادمین
            [
                'url' => '/^admin\/dashboard$/',
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
            ],
            [
                'url' => '/^admin\/delete-poll\/(\d+)$/',
                'controller' => 'AdminController',
                'action' => 'deletePoll',
                'type' => 'POST'
            ],
            [
                'url' => '/^admin\/poll-results\/(\d+)$/',
                'controller' => 'AdminController',
                'action' => 'viewPollResults',
                'type' => 'GET'
            ]
        ];

        $route_fail = true;

        foreach ($urls_array as $url_arr) {
            if (
                preg_match($url_arr['url'], $url, $matches) &&
                $url_arr['type'] == $_SERVER['REQUEST_METHOD']
            ) {
                $route_fail = false;

                // حذف اولین مقدار matches (چون کل مسیر رو شامل می‌شه)
                unset($matches[0]);

                // وارد کردن فایل کنترلر
                include __DIR__ . '/controllers/' . $url_arr['controller'] . '.php';

                // ساخت شیء کنترلر
                $controller = new $url_arr['controller'];

                // فراخوانی اکشن با پارامترها
                $result = call_user_func_array([$controller, $url_arr['action']], array_values($matches));

                // مدیریت خروجی کنترلر
                if (is_array($result)) {
                    if (isset($result['redirect'])) {
                        header('Location: ' . $result['redirect']);
                        exit;
                    }
                    if (isset($result['view'])) {
                        $view_file = __DIR__ . '/views/' . $result['view'];
                        if (file_exists($view_file)) {
                            extract($result); // استخراج داده‌ها (مثل users, polls, csrf_token)
                            include $view_file;
                        } else {
                            echo "خطا: فایل ویو {$result['view']} پیدا نشد";
                        }
                    } elseif (isset($result['message'])) {
                        echo $result['message'];
                    }
                }

                break; // خروج از حلقه بعد از پیدا کردن مسیر
            }
        }

        if ($route_fail) {
            echo "(404) صفحه پیدا نشد";
        }
    }
}

// شروع روتر
new Route();
?>