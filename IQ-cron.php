<?php
include('function.php');

class IQ_cron{
    
    function __construct(){
        
    }
    
    function at($data){ // time
    
    // function
    // time
    // variable
    
    if(isset($data['time']) && !empty($data['time'])){
    $date =  IQ_date_time('H:i:s');
    
    // condition
        if(!isset($data['seconds']) || isset($data['seconds']) && $data['seconds'] != true){
        $data['time'] = substr($data['time'], 0, -3);
        $date = substr($date, 0, -3);
        }
        

       if($data['time'] === $date && isset($data['function']) && !empty($data['function'])){
             if(!isset($data['variable']) || empty($data['variable'])){ $data['variable'] = ''; }
            call_user_func($data['function'],$data['variable']);
       }
    }
        
    }
    
    function date($data){ // date time
        
        
    }
    
    
    function __destructors(){
        
    }
    
}

// $IQ_con = new IQ_cron();
// function hello(){
//     echo 'IQ Return';
// }
// $IQ_con->at(array('time'=>'21:00:00','function'=>'hello'));

add_action('IQ_cron');