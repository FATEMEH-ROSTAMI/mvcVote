<?php

require_once "models/Option.php";

$op_test=new Option();

// $op1=$op_test->create(1,'قورمه سبزی');
// echo $op1 ? "created":"oops!";

$gozine=$op_test->getAllByPollId(18);
foreach($gozine as $go)
{
    echo "option " . $go['id']. " is : ".$go['title']."</br>";
}

$jj=$op_test->getById(3);
if($jj)
{
    echo "option id is : ".$jj['id']." title is:  ".$jj['title']." poll_id is : ".$jj['poll_id'];
}
else echo "not exist!";