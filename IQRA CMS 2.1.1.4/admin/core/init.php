<?php 
// init function

define('ABSPATH', true);

foreach(current_active_addons() as $data){
    $file_url = ADDONSDIR.'/'.$data.'/'.$data.'.php';
    if(file_exists($file_url)){
        require_once($file_url);
    }
}

// theme function include
if(file_exists(THEMEDIR.'/'.current_active_theme().'/function.php')){
    require_once(THEMEDIR.'/'.current_active_theme().'/function.php');
}

add_action('init');

// check loggedin user exist
function loggedinuser_verification(){
    if(!empty(loggedinuser('return_id'))){
    $user = get_users(array('id'=>loggedinuser('return_id')));

    if($user[0]['status'] != 1){
        back('logout.php','admin');
    }
}
}

loggedinuser_verification();
?>