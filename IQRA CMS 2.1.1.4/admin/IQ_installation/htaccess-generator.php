<?php 

function IQ_auto_htaccess_generator(){
  
    $htaccessContent = " 

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^([^\.]+)$ $1.php [NC,L]
    
    ErrorDocument 404 ".site_url('admin')."404.php 
   
    "; 
     
    $htaccessFile = fopen(ADMINROOTDIR.'.htaccess', 'w'); 
    if ($htaccessFile) { 
        fwrite($htaccessFile, $htaccessContent); 
        fclose($htaccessFile); 
        echo ".htaccess file created successfully."; 
    } else { 
        echo "Failed to create .htaccess file."; 
    } 
   

}

IF(!file_exists(ADMINROOTDIR.'.htaccess')){
IQ_auto_htaccess_generator();
}