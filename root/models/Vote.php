<?php

require_once __DIR__. "/../config/database.php";

class Vote
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo=$pdo;
    }

    //new vote
    public function create($poll_id, $option_id, $user_id)
    {
        $stmt=$this->pdo->prepare("INSERT INTO votes (poll_id, option_id, user_id, voted_at)
         VALUES (?,?,?,now())");
        try{
            $stmt->execute([$poll_id, $option_id, $user_id]);
            return true;
        } 
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

//get the number of votes for an option
    public function getVoteCount($option_id)
    {
        $stmt=$this->pdo->prepare("SELECT COUNT(*) as vote_count FROM votes WHERE option_id=?");
        $stmt->execute([$option_id]);
        $res=$stmt->fetch(PDO::FETCH_ASSOC);
        return $res['vote_count'];
    }

    //take all the votes of a poll
    public function getVotes($poll_id)
    {
        $stmt=$this->pdo->prepare("SELECT option_id, COUNT(*) as vote_count
         FROM votes WHERE poll_id=? GROUP BY option_id ");
        $stmt->execute([$poll_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }
<<<<<<< HEAD
   
=======

    public function hasVoted($poll_id,$user_id)
    {
        $stmt=$this->pdo->prepare("SELECT COUNT(*) as count FROM votes WHERE poll_id=? AND user_id=? " );
        $stmt->execute([$poll_id,$user_id]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count']>0;
        
    }
      
    
>>>>>>> aaf5437162a7e437e58226f9b050e892d46f2612
}