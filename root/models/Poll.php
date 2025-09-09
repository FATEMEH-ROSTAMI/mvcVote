<?php

require_once __DIR__."/../config/database.php";

class Poll
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo=$pdo;
    }

    public function create($title,$description,$created_by)
    {
        $stmt=$this->pdo->prepare("INSERT INTO polls (title, description,created_by, created_at)
        VALUES(?,?,?,now()) ");
        try{
            $stmt->execute([$title,$description,$created_by]);
            return $this->pdo->lastInsertId();
        }
        catch(PDOException $e)
        {
            return false;
        }
    }
 
    public function getAll()
    {
        $stmt=$this->pdo->prepare("SELECT * FROM polls ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }
   
    public function getById($poll_id)
    {
        $stmt=$this->pdo->prepare(" SELECT * FROM polls WHERE id=? ");
        $stmt->execute([$poll_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}