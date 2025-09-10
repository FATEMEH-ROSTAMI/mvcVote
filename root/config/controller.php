<?php

class controller
{

    public function view($file_name, $data = '')
    {
        

        include "views/" .$this->selectFolder($file_name). $file_name . '.php';
    
    }
    public function selectFolder($file_name){
        $foldername ='';
        switch ($file_name) {
            case 'login':
                $foldername = 'auth/';
                break;
            case 'register':
                $foldername = 'auth/';
                break;
            case 'dashboard':
                $foldername = 'polls/';
                break;

            case 'userPoll' :
                $foldername = 'polls/';
                break;

        }
        return $foldername;
    }
}
