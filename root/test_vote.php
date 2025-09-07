<?php 
require_once "models/Vote.php";

$vote_test=new Vote();

// $nn=$vote_test->create( 4,18,1);
// echo $nn?"voted!":"oops!";

// $cnt=$vote_test->getVoteCount(2);
// echo $cnt;

$num=$vote_test->getVotes(18);
foreach($num as $n)
{
    echo "option". $n['option_id'] . ":" . $n['vote_count']. "</br>"; 
}


