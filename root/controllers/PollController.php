 <?php

require_once __DIR__ . "/../models/Poll.php";
require_once __DIR__ . "/../models/vote.php";
require_once __DIR__ . "/../models/option.php";
require_once __DIR__ . "/../config/controller.php";

class PollController extends controller
{
    private $pollModel;
    private $voteModel;
    private $optionModel;


    public function __construct()
    {
        $this->pollModel=new poll();
        $this->voteModel=new vote();
        $this->optionModel=new option();
    }

//show the all polls
    public function index()
    {
       $polls = $this->pollModel->getAll();
       return $polls;
    }

//create a new poll
    public function create ($title,$description,$created_by,$option=[])
    {   //Create a poll
        $poll_id=$this->pollModel->create($title,$description,$created_by);
        if(!$poll_id)
        {
            return ['success'=>false , 'message'=>'error in create poll' ];
        }

        //insert options
        foreach($option as $opTitle)
        {
            $op=$this->optionModel->create($poll_id,$opTitle);
            if(!$op)
            {
                return ['success'=>false , 'message'=>'error in insert option : $opTitle']; 
            }
        }

        return ['success'=> true , 'message'=> 'your poll created!','poll_id '=>$poll_id];
    }


    //show a poll with its options
    public function showPoll($poll_id)
    {
       $poll= $this->pollModel->getById($poll_id);
       if(!$poll)
       {
        return ['success'=>false, 'message'=>'error in show poll!'];
       }

       $options=$this->optionModel->getAllByPollId($poll_id);
       if(!$options)
       {
        return ['success'=>false, 'message'=>'error in show options!'];
       }

       return ['success'=>true , 'poll'=>$poll ,  'option'=>$options];
    }



    public function vote()
{
    $poll_id   = $_POST['poll_id']   ?? null;
    $option_id = $_POST['option_id'] ?? null;
    $user_id   = $_POST['user_id']   ?? null; // بعداً از سشن بگیر
    
    if (!$poll_id || !$option_id || !$user_id) {
        return ['success' => false, 'message' => 'اطلاعات ناقص ارسال شده است'];
    }

    if ($this->voteModel->hasVoted($poll_id, $user_id)) {
        return ['success'=> false , 'message'=> "شما قبلاً رای داده‌اید!"];
        
    }

    $vote = $this->voteModel->create($poll_id, $option_id, $user_id);
    if(!$vote) {
        return ['success'=> false , 'message'=> "خطا در ثبت رای!"];
    }
    $this->view('userPoll');
    return true;
}

    public function result($poll_id,$option_id,)
    {
        $poll=$this->pollModel->getById($poll_id);
        if(!$poll)
        {
            return  ['success'=> false , 'message'=> "this poll is not exist!"];
        }

       
        $options=$this->optionModel->getAllByPollId($poll_id);
        $res=[];
        foreach($options as $option)
        {
            $voutCnt=$this->voteModel->getVoteCount($option['id']);
           
            $res[]=[
                'option_id'=> $option['id'],
                'title'=>$option['title'],
                'vote_count'=>$voutCnt
            ];
        }

        return ['success'=>true , 'poll'=> $poll , 'result'=>$res ];

    }
} 

