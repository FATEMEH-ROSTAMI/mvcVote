<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../config/database.php";


class AuthController
{
    private $pdo;
    private $userModel;

    public function __construct()
    {
        global $pdo;
        $this->pdo=$pdo;
        $this->userModel=new User();
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
        $csrf_token=$this->generateCSRFToken();
        return ['csrf_token'=>$csrf_token];
    }

    public function register($name,$email,$password,$csrf_token)
    {
        if(!$this->verifyCSRFToken($csrf_token))
        {
            return ['success' => false , 'message'=> 'CSRF token is invalid'];
        }
         
        if(empty($name) || empty($email) || empty($password)  )
        {
            return ['success' => false , 'message'=> 'name, email, and password are required']; 
        }
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'the password must be at least 8 characters long'];
        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
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


        return ['success'=>true ,'user_id'=>$result ,'message'=>'register was successful!'] ;
    }

    //show login form
    public function showLogin(){
        $csrf_token=$this->generateCSRFToken();
        return ['csrf_token'=>$csrf_token];
    }

    public function login($email,$password,$csrf_token)
    {
        if(!$this->verifyCSRFToken($csrf_token))
        {
            return ['success' => false , 'message'=> 'CSRF token is invalid'];
        }

        if(empty($email)||empty($password))
        {
            return ['success' => false , 'message'=> 'email, and password are required']; 
        }

        $user= $this->userModel->login($email,$password);
        if(!$user)
        {
            return ['success' => false , 'message'=> 'email or password is incorrect']; 
        }

        
        $_SESSION['user_id']=$user['id'];
        $_SESSION['user_name']=$user['name'];
        $_SESSION['user_role']=$user['role'];


        if($user['role']==='admin')
        {
            $redirect='admin/panel.php';
        }
        else{
            $redirect='user/dashboard.php';///takmil shavad
        }

        // return [
        //     'success'=>true,
        //     'user_id'=>$user['id'],
        //     'role'=>$user['role'],
        //     'redirect'=>$redirect,
        //     'message'=>'login was successful'

        // ];
        header("location:$redirect");
    }

    public function logout()
    {
        session_destroy();
        return ['success'=>true,'redirect'=>'login.php','message'=>'you have exited'];
    }

    public function isLoggedIn()
    {
        
       return isset($_SESSION['user_id']);
    }

    public function isAdmin()
    {
        
        return isset($_SESSION['user_role'])  && $_SESSION['user_role']=='admin';
    }
}
