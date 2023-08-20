<?php





function IQ_auto_config_generator($db_name, $user, $pass, $host){

    $content = file_get_contents(ADMINROOTDIR.'IQ_installation/setup-wizard/config-sample.txt',"r");

    // replace array
    $variables = array('database_name'=>$db_name,'database_username'=>$user,'database_password'=>$pass, 'database_localhost'=>$host);

    //   replace
    foreach($variables as $key => $value){
        $content = str_replace($key, $value, $content);
        }

            $configfile = fopen(ROOTDIR.'config.php', 'w'); 
            if ($configfile) { 
                fwrite($configfile, $content); 
                fclose($configfile); 
                return 1;
            } else { 
                return 0;
            } 
   

}


// config file checker





if(isset($_POST['IQ_database_submit'])){


    $name = $_POST['database-name'];
    $username = $_POST['database-username'];
    $password = $_POST['password'];
    $host = $_POST['database-host'];

   
    // Check connection
    $link = mysqli_connect($host, $username, $password, $name) or die("Unable to Connect to ");

   

    if(IQ_auto_config_generator($name,$username,$password, $host) === 1){
        header('Location: '.IQ_base_url().'admin');
    }

}