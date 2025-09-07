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
    public function create($user_id,$poll_id,$option_id)
    {
        $stmt=$this->pdo->prepare("INSERT INTO votes (user_id,poll_id,option_id,voted_at)
         VALUES (?,?,?,now())");
        try{
            $stmt->execute([$user_id,$poll_id,$option_id]);
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
}