<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/controller.php";

class AuthController extends controller
{
    private $pdo;
    private $userModel;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->userModel = new User();
    }

    private function generateCSRFToken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // همیشه یه توکن جدید بساز
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    private function verifyCSRFToken($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        $isValid = hash_equals($_SESSION['csrf_token'], $token);

        // مصرف شدن توکن بعد از چک
        unset($_SESSION['csrf_token']);

        return $isValid;
    }
    public function showRegister()
    {
        $csrf_token = $this->generateCSRFToken();
        
        return ['csrf_token' => $csrf_token];
    }

    public function register($name, $email, $password, $csrf_token)
    {
        if (!$this->verifyCSRFToken($csrf_token)) {
            return ['success' => false, 'message' => 'CSRF token is invalid'];
        }

        if (empty($name) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'name, email, and password are required'];
        }
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'the password must be at least 8 characters long'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'email is invalid.'];
        }

        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'the email has already been exist'];
        }

        //????
        $result = $this->userModel->register($name, $email, $password);
        if (is_array($result) && !$result['success']) {
            return $result;
        }
        if (!$result) {
            return ['success' => false, 'message' => 'error in user registration'];
        }


        $_SESSION['user_id']   = $result;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = 'user';


        return ['success' => true, 'user_id' => $result, 'message' => 'register was successful!'];
    }

    //show login form
    public function showLogin()
    {
        $csrf_token = $this->generateCSRFToken();
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                header('location:http://localhost/voting%20system/mvcVote/root/views/polls/dashboard.php');
            } else {
                header('location:http://localhost/voting%20system/mvcVote/root/views/polls/userPoll.php');
            }
        } else {
            $this->view('login', ['csrf_token' => $csrf_token]);
        }
        return true;
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'email, and password are required'];
        }

        $user = $this->userModel->login($email, $password);
        if (!$user) {
            return ['success' => false, 'message' => 'email or password is incorrect'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            $redirect = 'dashboard';
        } else {
            $redirect = 'userPoll';
        }
        
        $this->view($redirect);
    }

    public function logout()
    {
        session_destroy();
        // return ['success'=>true,'redirect'=>'login.php','message'=>'you have exited'];
        header("location:http://localhost/voting%20system/mvcVote/root/login");
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        if (isset($_SESSION['user_role'])  && $_SESSION['user_role'] == 'admin') {
            return true;
        }
        return false;
    }
}
