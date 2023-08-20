<?php 
include('admin/function.php');
if(site_options('return_maintenance')){ 
   if(empty(loggedinuser('return_id'))){
   if(file_exists(current_active_theme('dir').'maintenance'.'.php')){ 
      include(current_active_theme('dir').'maintenance'.'.php'); } 
      die();
   }
}


// parametors
// $router_data = array('permalink'=>'url','filepath'=>'');


function add_route_page($router_data){
   global $data,$IQ_route_verify;
   if($router_data['permalink'] == $data['permalink']){
      view($router_data['filepath'],$data);
      $IQ_route_verify = true;
   }
}



autoload( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . "content/themes/".current_active_theme()."/view/modules" );
Global $db;
global $IQ_route_verify;
$request_url = explode('?', $_SERVER['REQUEST_URI'], 2);
$site_urls = site_url('nohost');
$path = $_SERVER['HTTP_HOST'] . $request_url[0];
$IQ_route_verify = false;


if($path == $site_urls){
   $front_page = site_options('return_front_page');
   if(!empty($front_page)){
      $data = get_post(array('id'=>$front_page));
      if(!empty($data)){
         $data = $data[0];
      }
      add_action('is_home_page');
      include_current_theme_dir('index.php');
   }
   $IQ_route_verify = true;
   }


   if($IQ_route_verify == false){

//   post router
  $postdata = $db->fetch_all('post', 'NOT permalink="" and status="publish"');

 

  foreach($postdata as $data){
   if($path == site_url('nohost', $data['permalink'])){

      // custom page
      if($data['post_type'] == 'page'){
         add_action('is_custom_page');
         add_action('add_router_page');
      }

      // home page match
      if($IQ_route_verify == false){
         $front_page = site_options('return_front_page');
         $frontpage = get_post(array('id'=>$front_page));
         // print_r($frontpage);
         // $frontpage = $frontpage[0];
         if($site_urls.$frontpage[0]['permalink'] == $path){
            add_action('is_home_page');
            include_current_theme_dir('index.php');
            $IQ_route_verify = true;
         }
      }

      // single layout
   if($IQ_route_verify == false){
      if(!empty($data['layout'])){
         add_action('is_layout_page');
         if(file_exists(current_active_theme('dir').'/view/'.$data['layout'].'.php')){
            
            view($data['layout'],$data);
            $IQ_route_verify = true;
            break;
         }else{
            add_action('is_layout_page');
            view('content',$data);
            $varify = true;
            break;
         }
      }
   }

// archieve page
      if($IQ_route_verify == false){
         foreach($registered_taxonomies as $taxnomonies_route){
            if($taxnomonies_route['post_type'] == $data['post_type']){
               add_action('is_archive_page');
                  if(file_exists(current_active_theme('dir').'/view/'.$taxnomonies_route['archive'].'.php')){
                     
                     include(current_active_theme('dir').'/view/'.$taxnomonies_route['archive'].'.php');
                     $IQ_route_verify = true;
                     break;
                  }else{
                     add_action('is_archive_page');
                     view('content',$data);
                     $IQ_route_verify = true;
                     break;
                  }
            }
         }
         
         // last content page
         if($IQ_route_verify == false){
            add_action('is_archive_page');
            view('content',$data);
               $varify = true;
               break;
         }
        
      }


     }
  }

}



//   author
if($IQ_route_verify == false){
   $path_author = explode('/author/',$path);
   if($path_author[0].'/author' == site_url('nohost','author')){
      add_action('is_author_page');
      if(file_exists(current_active_theme('dir').'/view/'.'author'.'.php')){
         view('author',$path_author[1]);
         $IQ_route_verify = true;
      }
   }
}




//   check manual permalinks
  if($IQ_route_verify == false){
   if($path == $site_urls.'sitemap.xml'){
      add_action('is_sitemap_page');
       include('admin/sitemap.php');
   }else 
   if($path == $site_urls.'robot.txt'){
      add_action('is_robot_page');
      include('admin/robot.txt');
  }else{
   add_action('is_404_page');
   include_current_theme_dir('404.php');
   }
  }


  