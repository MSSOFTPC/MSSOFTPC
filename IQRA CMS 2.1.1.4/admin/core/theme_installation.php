<?php
function removeDir($dir) {
  $status = 0;
  if(!is_dir($dir)){ unlink($dir); }
  $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $dir).'/{,.}*', GLOB_BRACE);
    foreach ($files as $file) {
        if ($file == $dir.'/.' || $file == $dir.'/..') { continue; } // skip special dir entries
        is_dir($file) ? removeDir($file) : unlink($file);
    }

    rmdir($dir);
  if(!file_exists($dir)){ $status = 1;}
  return $status; // UNIX commands return zero on success
}

function get_theme_dir($dir){
    $dir = explode('themes',$dir,2);
    $dir = explode('/',$dir[1],3);
    // $dir = strtok($dir[0],'\/');

    return site_url('').'content/themes/'.$dir[1].'/';
  }

  
  function current_active_theme($data = null){
    $themedata = site_options('return_active_theme');
    if(isset($data) && $data == 'dir'){ $themedata = THEMEDIR.'/'.current_active_theme().'/'; }
    return $themedata;
  }

  function include_current_theme_dir($dir = null){
      $fetch = site_options('return_active_theme');
      if(!isset($dir)){ $dir = ''; }
      include('content/themes/'.$fetch.'/'.$dir);
  }

  function get_current_theme_dir($dir = null){
    $fetch = site_options('return_active_theme');
      if(!isset($dir)){ $dir = ''; }
    return site_url('').'content/themes/'.$fetch.$dir.'/';
}

function IQ_header(){
  include_current_theme_dir('header.php');
}

function IQ_footer(){
  include_current_theme_dir('footer.php');
  echo '<script src="'.adminassets('extra.js', 'js').'"></script>';
  echo '<script src="'.adminassets('top_admin_bar.js', 'js').'"></script>';
msg();
add_action('IQ_add_footer_data');
}



function view($path, $data = null){
  if(isset($data)){ global $data; }
  if(file_exists(THEMEDIR.'/'.current_active_theme().'/view/'.$path.'.php')){
    include(THEMEDIR.'/'.current_active_theme().'/view/'.$path.'.php');
  }else{
    echo 'File Not Found';
    return false;
  }
 
}
// end view part

// head part
function IQ_head($title = null){
  global $data;
  $site_title = site_options('return_title');
  $site_logo = site_options('return_logo');

  if(isset($data['featured_img'])){ $feature_img = $data['featured_img']; }
  if(isset($data['title'])){ $title = $data['title']; }else{$title = '';}
  if(!isset($data['modified_date'])){ $data['modified_date'] = '';}
  if(!isset($data['permalink'])){ $data['permalink'] = '';}
  if(!isset($data['post_date'])){ $data['post_date'] = ''; }

  if(empty($feature_img)){ $feature_img = $site_logo; }
  $post_title = $title.' | '.$site_title;
  $IQ_post_type_head = true;
  if(isset($title)){
    $post_title = $title.' | '.$site_title;
    $title = $title;
    $IQ_post_type_head = false;
  }
  

  echo '<!DOCTYPE html> <html lang="en"><head>';
  echo '<meta charset="UTF-8">';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '<meta http-equiv="X-UA-Compatible" content="ie=edge">';
  echo '<link rel="shortcut icon" type="image/png" href="'.$site_logo.'">';

$title_final = apply_filter('IQ_title',$title.' | '.site_options('return_title'));


  echo '<title>'.$title_final.'</title>';
  // meta tags
  echo '<meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>';
  echo '<link rel="canonical" href="'.site_url('').'" />';


  // end meta
  add_action('IQ_add_head_data');
  echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" ></script>';
  echo '<link rel="stylesheet" href="'.adminassets('notyf/notyf.min.css','vendor').'">';
  echo '<link rel="stylesheet" href="'.adminassets('top_admin_bar.css','css').'">';
  // <!-- https://remixicon.com/-->
  echo '<link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet">';
  echo '<script src="'.adminassets('sweetalert2/dist/sweetalert2.all.min.js','vendor').'"></script>';
  echo '<script src="'.adminassets('notyf/notyf.min.js', 'vendor').'"></script>';
  if(loggedinuser('return_role') == 'admin'){
    $loggedinclass = 'TQtopadminBar';
  }else{
    $loggedinclass = '';
  }
  echo '</head><body class="'.$loggedinclass.'">';
  IQ_top_admin_bar();

}

// reset link page
function resetpassword($id, $class=null){ ?>
      <form action="<?php echo site_url('admin','function.php?value='.$id); ?>" method="post">
      <div class="mt-3">
              <input type="hidden" name="forgettoken" value="forget_<?php echo date("Ymd"); ?>">
              <button class="btn <?php echo $class; ?>" name="submit" value="update_user" type="submit">Send Reset Password Link to Mail</button>
          </div>
      </form>
<?php }



