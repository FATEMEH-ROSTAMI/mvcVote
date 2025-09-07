<?php

require_once "controllers/PollController.php";

$pollc_test=new PollController();

// $rr=$pollc_test->create('your favarite acctor ',
// 'be unest',1,['golzar','hedye tehrani','hootan shakiba']);
// echo $rr?"poll was created":"sorry!";

// $cc=$pollc_test->vote(18,2,5);
// echo $cc? "hale":"oops!";


// $ff=$pollc_test->result(18,2);
// if($ff['success'])
// {
//     foreach($ff['result'] as $f)
//     {
//         echo $f['title']." : ".$f['vote_count']."</br>";
//     }
// }
// else{
//     echo $ff['message'];
// }

// $hh=$pollc_test->showPoll(18);
// if($hh['success'])
// {
//     echo $hh['poll']['title']. "</br>";
//     foreach($hh['option'] as $option )
//     {
//         echo $option['title']. "</br>";
//     }
// }
// else{
//     echo $hh['message'];
// }

$dd=$pollc_test->index();
if($dd)
{
    foreach($dd as $d)
    {
        echo $d['id'].$d['title']. "</br>";
    }
}
else{
    echo "nothing";
}


