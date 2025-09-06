<?php

require_once "models/Poll.php";

$poll_test=new Poll();

// $tt=$poll_test->create('your favarite color','RGB','1');
// echo $tt?'created!': 'oops!!';


$aa=$poll_test->getAll();
foreach($aa as $a)
{
    echo 'the title of polls  : '.$a['id']." ". $a['title']. '</br>';
}


$ii=$poll_test->getById(18);
echo $ii['description'];

