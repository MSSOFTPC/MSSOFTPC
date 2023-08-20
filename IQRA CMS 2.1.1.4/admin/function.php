<?php

session_start();

define('CONTENTDIR', __DIR__.'/../content/');
define('ADDONSDIR', __DIR__.'/../content/addons/');
define('THEMEDIR', __DIR__.'/../content/themes/');
define('UPLOADDIR', __DIR__.'/../content/uploads/');
define('ROOTDIR', __DIR__.'/../');
define('ADMINROOTDIR', __DIR__.'/../admin/');
define('CACHEDIR', __DIR__.'/../admin/cache/');

// get domain name magic
function IQ_base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
   
    // get domain name magic
        $getroot = explode($_SERVER['DOCUMENT_ROOT'],ROOTDIR);
        if($_SERVER['HTTP_HOST'] == 'localhost'){
            $domain = explode('/admin',$getroot[1]);
            return  'http://'.$_SERVER['HTTP_HOST'].$domain[0].'/';
        }else{
          
         if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        
                    $base_url = explode('admin/',$base_url);
                    
                     return $base_url[0];

         }
        
    }
   
}

    if (!function_exists('base_url')) {
        function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
            if (isset($_SERVER['HTTP_HOST'])) {
                $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
                $hostname = $_SERVER['HTTP_HOST'];
                $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    
                $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
                $core = $core[0];
    
                $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
                $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
                $base_url = sprintf( $tmplt, $http, $hostname, $end );
            }
            else $base_url = 'http://localhost/';
    
            if ($parse) {
                $base_url = parse_url($base_url);
                if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
            }
    
            return $base_url;
        }
    }
    

// white space fixer for sitemap
function ___wejns_wp_whitespace_fix($input) {
	$allowed = false;
	$found = false;
	foreach (headers_list() as $header) {
		if (preg_match("/^content-type:\\s+(text\\/|application\\/((xhtml|atom|rss)\\+xml|xml))/i", $header)) {
			$allowed = true;
		}

		if (preg_match("/^content-type:\\s+/i", $header)) {
			$found = true;
		}
	}

	if ($allowed || !$found) {
		return preg_replace("/\\A\\s*/m", "", $input);
	} else {
		return $input;
	}
}

ob_start("___wejns_wp_whitespace_fix");


// config checker
if(!file_exists(ROOTDIR.'config.php')){ 
// setup wizard
include('IQ_installation/setup-wizard/setup-wizard.php');
    die(); }


include('functions/filevalidation.php');
include('functions/datainsert.php');
include('functions/frontendapis/front-taxonomy.php');
include('functions/frontendapis/users-action.php');
include('functions/frontendapis/site_options-front.php');
include('mail/mails.php');
include('modules/formfields.php');
include('modules/notifications.php');
include('functions/action_hooks.php');
include('functions/shortcodes.php');
include('core/theme_installation.php');
include('core/addons_installation.php');
include('core/update_core.php');
include('core/smtp.php');
register_taxonomy(array('name'=>'Pages','icon'=>'<i class="icon icon-xs me-2 ri-pages-line" ></i>', 'post_type'=>'page'));  
include('core/init.php');
include('includes/maintenance.php');
include('includes/top_admin_bar.php');




// IQ CMS Data
function IQ_data($data){
    global $IQ;
    if($data == 'logo'){
        return adminassets('IQ/IQ-logo.png', 'img');
    }


    if($data == 'official_url'){ return 'https://iqra.mssoftpc.com/';}
    if($data == 'IQ_update_download'){ return 'https://iqra.mssoftpc.com/content/uploads/updates/Iqra_cms_update.zip';}
    if($data == 'update_file_name'){ return 'Iqra_cms_update.zip';  }
    if($data == 'version'){ return site_options('return_IQ_cms_version');  }

    // update status
    $version = site_options('return_IQ_cms_version');

    if($data == 'update_status'){ if(trim($IQ->version()) != trim($version)){
        return true;
    }}
}


// IQ errors
function site_options($title){
    global $db;
    $return = explode('_', $title,2);
    if($return[0] == 'return'){ 
        $rows = fetch('site_options','option_name="'.$return[1].'"');
        if(!empty($rows)){
        if($return[1] == 'logo'){ return assets('site/'.$rows['option_value'],'upload'); }else{
            return IQ_html_decode($rows['option_value']);
    }}
    } else {
        $rows = fetch('site_options','option_name="'.$title.'"');
        if(!empty($rows)){
        if($title == 'logo'){ echo assets('site/'.IQ_html_decode($rows['option_value']),'upload');}else{ echo IQ_html_decode($rows['option_value']);}
     }}
    
    
}

// set time zone
date_default_timezone_set(site_options('return_IQ_time_zone'));   //India time (GMT+5:30)

function is_admin(){
    $data = explode(site_url('admin',''),site_url('currentURL'));
    if(empty($data[0])){
        return true;
    }else{
        return false;
    }
}

function IQ_trim($data){
$max_length = $data['limit'];
$s = $data['content'];

if(!isset($data['end_text'])){ $data['end_text'] = ''; }
if(!isset($data['start_text'])){ $data['start_text'] = ''; }

$start_text = $data['start_text'];
$end_text = $data['end_text'];

if(empty($max_length)){
    return $start_text.$s.$end_text ;

}else{
if (strlen($s) > $max_length)
{
    $offset = ($max_length - 3) - strlen($s);
    $s = substr($s, 0, strrpos($s, ' ', $offset)) . $end_text;
    return $start_text.$s;
}else{
    return $start_text.$s.$end_text ;
}

}
}


function IQ_date_time($data = null){
    date_default_timezone_set(site_options('return_IQ_time_zone'));   //India time (GMT+5:30)
    if(!isset($data) || empty($data)){ $data = 'd-m-Y H:i:s'; }
    return date($data);
}

function IQ_time_format($data,$time){
    if($data == 'am' || $data == '12' ||  $data == 'pm'){
        $dateObject = new DateTime($time);
        return $dateObject->format('h:i A');
    }
}

function IQ_date_format($date,$format = null){
    if(!isset($format)){ $format = 'Y-m-d';}
    $d = date($format, strtotime($date));
    return $d;
}

function loggedinuser($data = null){
    if(!isset($data) || empty($data)){
        return $_SESSION['login'];
    }else{
    
    $return = explode('_', $data,2);
    if(isset($_SESSION['login'])){
    if($return[0] === 'return'){ return $_SESSION['login'][$return[1]];}
    else{echo $_SESSION['login'][$data];}
}else{ return false; }
}
}

function site_url($url = null,$link = null){
    if(!isset($url)){$url = '';}
    $rows = site_options('return_url');
    $urlg = '';
    $urlg = $rows.'/';
    $hostchk = explode('_', $url);
    if($hostchk[0] == 'nohost'){ $hostmatch = explode('://', $urlg); if(count($hostmatch) > 0){ $urlg =  $hostmatch[1];  }}
    if($url == 'currentURL'){ 
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){ $urlg = "https://"; } else {$urlg = "http://";}
        $urlg .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];}
    if($url == 'admin' || isset($hostchk[1]) && $hostchk[1] == 'admin'){ $urlg .= 'admin/';}
    if(!empty($link)){   $urlg .= $link;  }

    return $urlg;

}

function is_localhost(){
    if($_SERVER['HTTP_HOST'] === 'localhost'){
        return true;
    }else{
        return false;
    }
}

function IQ_filepermission($dir){
    return substr(sprintf("%o", fileperms($dir)),-4);
}
// 0777 (for folder )
// 0644 (for file)

$adminurl = site_url('admin');
$site_url = site_url('','');
$site_title = site_options('return_title');

function adminassets($title, $type){
    Global $adminurl;
    if($type == 'css'){ return $adminurl.'assets/css/'.$title; }
    if($type == 'img'){ return $adminurl.'assets/img/'.$title; }
    if($type == 'js'){ return $adminurl.'assets/js/'.$title; }
    if($type == 'vendor'){ return $adminurl.'assets/vendor/'.$title; }
}
function assets($title, $type){
    Global $site_url;
    if($type == 'css'){ return get_current_theme_dir().'assets/css/'.$title; }
    if($type == 'images' || $type == 'img'){ return get_current_theme_dir().'assets/img/'.$title; }
    if($type == 'upload'){ return site_url('').'content/uploads/'.$title; }
    if($type == 'js'){ return get_current_theme_dir().'assets/js/'.$title; }
    if($type == 'vendor'){ return get_current_theme_dir().'assets/vendor/'.$title; }
}


// back to page
function back($redirect = null, $path = null){ 
    if($redirect == 'reload'){ back(site_url('currentURL'));  die();  }
    if(isset($redirect) && !empty($redirect) && !isset($path)){
        if (headers_sent()) { echo '<script>window.location.href = "'.$redirect.'";</script>'; }else{ header("Location: ".$redirect); }

    }else{
    if(isset($redirect) && !empty($redirect)){
        if($redirect == '/'){ $redirect = '';}
        if($path == ''){ $path = '';}
        if (headers_sent()) { echo '<script>window.location.href = "'.site_url($path, $redirect).'";</script>'; }else{ header("Location: ".site_url($path, $redirect)); }
    }else{
        if (headers_sent()) { echo '<script>history.back(-1)</script>'; }else{ header('Location:'.$_SERVER['HTTP_REFERER']); }
    }}
}

// access with role based
function loginonly($logout = null, $role=null){

    if(isset($_SESSION['login'])){
        if(isset($role) && !empty($role)){

                // visitor    

                if(loggedinuser('return_role') == 'visitor'){
                    if($logout == 'logout'){ back('/', '');}
                    }else{
                    if($logout == ''){ back('/','');}
                    }

        }else{
            if(loggedinuser('return_role') == 'admin'){
                if($logout == 'logout'){ back('index.php', 'admin');}
                }else{
                if($logout == ''){ back('/','');}
                }
        }
        
}else{
    if($logout == ''){ back('login.php', 'admin');}
}
}

// get text with star and end line
function IQ_getStringBetween($str, $start='[', $end=']', $with_from_to=true){
    $arr = [];
    $last_pos = 0;
    $last_pos = strpos($str, $start, $last_pos);
    while ($last_pos !== false) {
        $t = strpos($str, $end, $last_pos);
        $arr[] = ($with_from_to ? $start : '').substr($str, $last_pos + 1, $t - $last_pos - 1).($with_from_to ? $end : '');
        $last_pos = strpos($str, $start, $last_pos+1);
    }
    return $arr; 
}

function IQ_content($data){
    $entity = html_entity_decode($data);
    $data = IQ_getStringBetween($entity);
    if(!empty($data)){
    foreach($data as $datas){
        $shortcode = do_shortcode($datas);
        $text = str_replace($datas, $shortcode, $entity);
    }
}else{
    $text = $entity;
}
    return $text;
   
}

function IQ_html_decode($data,$type = null){
    if(isset($type) && !empty($type)){
        if($type === 'unserialize'){
            $entity = unserialize(html_entity_decode(IQ_html_decode($data)));
        }
    }else{
        $entity = html_entity_decode($data);
    }

    return $entity;
}


// x time age generator
function IQ_date_to_ago_generator($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// registered only
function registereduseronly($logout){
    if(isset($_SESSION['login'])){
         if($logout == 'logout'){ back('/', '');}     
        }else{
    if($logout == ''){ back('login.php', 'admin');}
    }
}


// login
if(isset($_POST['LoginSubmit']) ){
$email = strip_tags($_POST['email']);
$encritppass = md5($_POST['password']);
$pass = $encritppass;
$dta = $conn->query('select * from user where email="'.$email.'"');
if($dta->num_rows > 0){
    $store = $dta->fetch_assoc();
    if($pass != $store['password']){ $_SESSION['error'] = 'Credentials Not Correct';$_SESSION['error_login_notice'] = 'Credentials Not Correct'; back(); die(); }
    if($store['status'] == 0){  $_SESSION['error'] = 'Your Account Not Active'; back(); die();}
$_SESSION['login'] = $store;
$_SESSION['success'] = 'Welcome Back!';

// redirect
if(isset($_GET['redirect']) && !empty($_GET['redirect'])){
    if($store['role'] == 'visitor'){header('Location: '.$_GET['redirect']);}
    if($store['role'] == 'admin'){header('Location: '.$_GET['redirect']);}
}else{
if($store['role'] == 'visitor'){header('Location: '.site_url(''));}
if($store['role'] == 'admin'){header('Location: index.php');}
}


}else{
    $_SESSION['error'] = 'Email Not Registered';
    $_SESSION['error_login_notice'] = 'Email Not Registered';
    header('Location: login.php');
}


}

// Registration
if(isset($_POST['RegisterSubmit']) ){
    global $insert_id;
    $email = strip_tags($_POST['email']);
    $encritppass = md5($_POST['password']);
    $conformpassword = md5($_POST['confirm_password']);
    $pass = $encritppass;
    $dta = $conn->query('select * from user where email="'.$email.'"');
    if($dta->num_rows == 0){
        if($encritppass == $conformpassword){        

        $datainsert = array();
        $datainsert += ['role' => 'visitor'];
        $datainsert += ['password'=>$encritppass];
        $datainsert += ['email_token'=> date('Ymd')];

        
        foreach($_POST as $data => $val){ 
            if($data != 'RegisterSubmit' && $data != 'confirm_password'){ 
                $datainsert += [ $data => $val];
            }
            
            } 
            insertdata($datainsert, 'user');
            $insert_id = $conn->insert_id;
            IQ_mail_registration($email);
            add_action('IQ_after_user_registration');
            $_SESSION['success'] = 'Registration Success! Please Verify Email';
            back();

        }else{
            $_SESSION['error'] = 'Password Not Match';
                back();
        }
    }else{
        $_SESSION['error'] = 'Email Already Exist';
            back();
    }

    }

    // email verify
    if(isset($_GET['email_token']) && isset($_GET['email'])){
            $emaildata = fetch('user', 'email="'.$_GET['email'].'"');
            if(isset($emaildata['email'])){
                if($emaildata['email_token'] == $_GET['email_token']){
                    $data = ['email_verify' => 1];
                    $data += ['email_token' => ''];
                    updatedata($data, 'email="'.$emaildata['email'].'"', 'user');
                        $_SESSION['success'] = 'Email Verified';
                        back('');
                }else{
                    if($emaildata['email_verify'] == 1){
                        $_SESSION['error'] = 'Email Already Verified!';
                        back('');
                    }else{
                    $_SESSION['error'] = 'Email Verification Link Expired!';
                    back('');}
                }
            }
    }

// forget
    if(isset($_POST['ForgetSubmit'])){
        $email = $_POST['email'];
        $fetch = fetch('user', 'email="'.$email.'"');
       
        if(!empty($fetch['email'])){
            $token = 'forget_'.date('Ymd');
            IQ_mail_reset($token,$email);
            $data = ['forgettoken'=>$token]  ;
            updatedata($data, 'id='.$fetch['id'], 'user');
            $_SESSION['success'] = 'Reset Token Has Been Send to your Mail' ;
            $_SESSION['success_login_notice'] = 'Reset Token Has Been Send to your Mail' ;
            back();
        }else{
            $_SESSION['error'] = 'No Email Found' ;
            $_SESSION['error_login_notice'] = 'No Email Found' ;
            back();

            die();
        }
    }





// forget token
if(isset($_POST['resettokenSubmit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
   $password2 = $_POST['password2'];

        if($password == $password2){
            $encritppass = md5($password);
            $data = ['password'=>$encritppass];
            $data += ['forgettoken'=>''];
            updatedata($data, 'email="'.$email.'"','user');
            $_SESSION['success'] = 'Password updated Successfully';
            $_SESSION['success_login_notice'] = 'Password updated Successfully';
            header('Location: '.site_url('admin', 'login.php'));
        }else{
        $_SESSION['error'] = 'Please Enter Same Password'; 
        $_SESSION['error_login_notice'] = 'Please Enter Same Password' ;
            back();
        }
    }


    // auto include files
    function autoload( $path ) {
        $items = glob( $path . DIRECTORY_SEPARATOR . "*" );
    
        foreach( $items as $item ) {
            $isPhp = pathinfo( $item )["extension"] === "php";
    
            if ( is_file( $item ) && $isPhp ) {
                require_once $item;
            } elseif ( is_dir( $item ) ) {
                autoload( $item );
            }
        }
    }
    
    // theme_installation
    // theme_uploader
if(isset($_POST['theme_uploader'])){
    $path = '../content/themes/';
    $theme = $_FILES['theme_upload'];
    $name = $theme['name'];
    if(substr(strrchr($name, '.'), 1) == 'zip'){
        $realpath = realpath($path);
        if(move_uploaded_file($theme['tmp_name'], $path.$name)){
            $zip = new ZipArchive;  
                     if($zip->open($path.$name))  
                     {  
                           //   check file support
                           $total = $zip->count();
                           for ($i=0; $i<$total; $i++) {
                               $zipdata = explode('/',$zip->statIndex($i)['name']);
                               $zipdata =  $zipdata[0].'/style.css';
                               if($zip->statIndex($i)['name'] === $zipdata){
                                     $zip->extractTo($path);  
                                     removeDir($path.'__MACOSX');
                                     removeDir($path.$name);
                                   $_SESSION['success_theme_notice'] = 'Successfully Theme Uploaded';                                
                               }else{
                                   unlink($path.$name);
                                   $_SESSION['error_theme_notice'] = 'Uploaded Theme not Supported.';                                
                               }
                           
                           }
                           $zip->close();  


                         
                     }  
                     back();


        }else{
            $_SESSION['error_theme_notice'] = 'Upload Error! Please try again after few minutes';
        }
    }else{
      $_SESSION['error_theme_notice'] = 'Upload Zip Only';
      back();
    }
    
  
  }

    
    
// random string generator
function IQ_random_generator($n) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
 
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
 
    return $randomString;
}


// bulk action
if(isset($_POST['bulk_action_table'])){
    if($_POST['bulk_action_table'] == 'post'){
        if($_POST['bulk_action_type'] == 'delete'){
            $id = explode(',',$_POST['bulk_action_ids']);
            foreach($id as $post_id){
                if(!empty($post_id)){
                    deletedata('post', 'id='.$post_id); 
                    deletedata('post_meta', 'post_id='.$post_id); 
                    $_SESSION['success_post_list_notice'] = 'Deleted Successfully'; 
                    back();
                }
            }
             
        }
    }

    // for users
    if($_POST['bulk_action_table'] == 'users'){
        print_r($_POST);
        if($_POST['bulk_action_type'] == 'delete'){
            $id = explode(',',$_POST['bulk_action_ids']);
            foreach($id as $post_id){
                if(!empty($post_id)){
                    deletedata('user', 'id='.$post_id); 
                    deletedata('user_meta', 'user_id='.$post_id); 
                    $_SESSION['success_users_notice'] = 'Users Deleted Successfully'; 
                    back();
                }
            }
             
        }
    }
}

// register_taxonomy
function register_taxonomy($data){
        Global $registered_taxonomies;
        if(!isset($registered_taxonomies)){$registered_taxonomies = [];}
        if(!isset($data['archive'])){ $data['archive'] = ''; }
        if(!isset($data['supports'])){ $data['supports'] = ''; }
        array_push($registered_taxonomies, $data);
}



// add_action('init','register_taxonomy');

function get_meta($table, $where){
    Global $db;
    $fetch_post_meta = $db->fetch_all($table, $where);
    $i = 0;
    $meta_array = [];
    foreach($fetch_post_meta as $data){
        foreach($data as $datakey=>$dataval){
            if($datakey != 'id' && $datakey != 'post_id' && $datakey != 'meta_value'){
            $meta_array += [$dataval=>$fetch_post_meta[$i]['meta_value']];
        }  
    
    }  $i++;  }
        
    return $meta_array;
    
}

// site options register
function register_site_options($data){
    Global $registered_IQ_admin_site_options;
    if(!isset($registered_IQ_admin_site_options)){$registered_IQ_admin_site_options = [];}
    if(!isset($data['title'])){ $data['title'] = 'Website Options'; }
    if(!isset($data['function'])){ $data['function'] = ''; }
    if(!isset($data['slug'])){ $data['slug'] = ''; }
    array_push($registered_IQ_admin_site_options, $data);
}
// function function(){
// example
// $data['title'];
// $data['function'];
// $data['slug'];
// }
// register_site_options($data);
// add_action('init','function')






?>