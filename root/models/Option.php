<?php

require_once __DIR__ ."/../config/database.php";

class Option{
    private $pdo;

    public function __construct(){
        global $pdo;
        $this->pdo=$pdo;
    }

    //create a new option for a poll
    public function create($poll_id,$title)
    {
        $stmt=$this->pdo->prepare("INSERT INTO options (poll_id,title) VALUES (?,?) ");
        try{
            $stmt->execute([$poll_id,$title]);
            return $this->pdo->lastInsertId();
        }
        catch(PDOException $e )
        {
            echo $e->getMessage();
            return false;
        }
    }

//to take all options of a poll
    public function getAllByPollId($poll_id)
    {
        $stmt=$this->pdo->prepare("SELECT * FROM options WHERE poll_id=?");
        $stmt->execute([$poll_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   // get the details of an option
    public function getById($option_id)
    {
        $stmt=$this->pdo->prepare("SELECT * FROM options WHERE id=?");
        $stmt->execute([$option_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}