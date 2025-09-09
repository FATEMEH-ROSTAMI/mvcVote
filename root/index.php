<?php 
session_start();
include "root/models/Poll.php";
include_once "root/models/User.php";
require_once 'config/database.php';
header("Location: views/auth/login.php");

