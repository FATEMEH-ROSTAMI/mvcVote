<?php 

require_once "models/User.php";

$user_test=new User();

$cc=$user_test->register('mmd','mm10@gmail.com','2654lllkkfjmul');
echo $cc?"yes":"no";