<?php
  

function current_site_url(){
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){ $urlg = "https://"; } else {$urlg = "http://";}
        $urlg .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        return $urlg;
}



function IQ_config(){
    $content = '';
    $file = fopen(ROOTDIR.'config.php',"r");
    while(! feof($file))
    {
        $content .= fgets($file). "<br />";
    }

    if(empty($content)){ return true; }else{ return false; }
  
  fclose($file);
}


function setup_wizard(){

   
    
    
    include(ADMINROOTDIR.'IQ_installation/setup-wizard/head.php');
include('installation.php');

setup_wizard_head();

        if(!isset($_GET['step']) || empty($_GET['step'])){
                include('templates/welcome.php');
                die();
        }else 
        if(isset($_GET['step']) && $_GET['step'] == 1){
            include('templates/step1.php');
            die();
        }else
        {
            include('templates/welcome.php');
            die();
        }

        footer();
}


if(!file_exists(ROOTDIR.'config.php')){
    $url = explode('?',current_site_url());
   if($url[0] === IQ_base_url().'admin/'){  
    setup_wizard();
  
}else{
    echo back(IQ_base_url().'admin/');
}

}


