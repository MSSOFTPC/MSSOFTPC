<?php function get_sidebar(){ 
  $bytes = random_bytes(1);
  $randomid = bin2hex($bytes);

  function single_admin_sidebar_menu($title, $url,$icon = null){ ?>

<li class="nav-item">
       <a href="<?php echo site_url('admin', $url);?>"> <span
          class="nav-link  collapsed  d-flex justify-content-between align-items-center"
          data-bs-toggle="collapse" data-bs-target="#<?php echo $id.'_menu'.$randomid;?>">
          <span>
            <span class="sidebar-icon">
              <?php 
              $icon_link =  '<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path></svg>'; 
              if(isset($icon)){ $icon = $icon;}else{ $icon = $icon_link;} 
        echo $icon;   ?>
          </span> 
            <span class="sidebar-text"><?php echo $title;?></span>
          </span>
          <span class="link-arrow">
            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
          </span>
        </span></a>
      </li>


 <?php } 


    // register_post_type end
    // array(
    //   'name'=>'',
    //  'post_type'=>'',
    //  'layout'=>'',
    //   'extra_menu'=>array(),
    //   )


    function apply_taxonomies(){
      Global $registered_taxonomies;
      foreach($registered_taxonomies as $data){
          if(!isset($data['sidebar']) || $data['sidebar'] === true){
      $parentmenu = $data['name'];
      $pluralclass = $data['name'];
  
      if(!isset($data['icon'])){$data['icon'] = '';}
      $icon = $data['icon'];
      $pluralurl = 'view.php?post_type='.$data['post_type'];
  
      $extramenu = array('All '.$pluralclass => $pluralurl);

      // taxonomy
      if(isset($data['taxonomy'])){
        foreach($data['taxonomy'] as $taxonomy){
          $extramenu = array_merge($extramenu,[$taxonomy['title']=>'tags.php?taxonomy='.$taxonomy['permalink'].'&post_type='.$data['post_type']]);
        }
      }
      // end taxonomy
      
      if(isset($data['extra_menu'])){
        $extramenu = array_merge($extramenu,$data['extra_menu']);
    }

      $id = str_replace(' ', '', $parentmenu);
      $bytes = random_bytes(1);
      $randomid = bin2hex($bytes);
     
      add_admin_sidebar_menu(array('primary_name'=>$parentmenu, 'name'=>'Add New','url'=>$pluralurl.'&action=new','icon'=>$icon,'extra_menu'=>array($extramenu)));
  
   } 
      }
  }
  // end apply_taxonomies()

   // admin_sidebar_menu 
    // array(
    //   'name'=>'',
    // 'primary_name'=>'',
    //  'url'=>'',
    //   'extra_menu'=>array(),
    //   )

    // extra_menu
    // array(
      //   'name'=>'',
      //  'url'=>'',
      //   )

  function add_admin_sidebar_menu($data){ 
    global $extramenu;
    $parentmenu = $data['name'];
    $primary_name = $data['primary_name'];
    $url = $data['url'];
    if(isset($data['extra_menu'])){
      $extramenu = $data['extra_menu'];
    }
      $id = str_replace(' ', '', $primary_name);
      $bytes = random_bytes(1);
      $randomid = bin2hex($bytes)
    ?>
<li class="nav-item">
        <span
          class="nav-link  collapsed  d-flex justify-content-between align-items-center"
          data-bs-toggle="collapse" data-bs-target="#<?php echo $id.'_menu'.$randomid;?>">
          <span>
            <!-- icons area -->
            <span class="sidebar-icon">
            <?php $icon = '<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path></svg>';
             if(!isset($data['icon']) || empty($data['icon'])){
               $data['icon'] = $icon;
             }else{
              $data['icon'] =  $data['icon'];
             }

             echo $data['icon']; ?>
            
            </span> 

            <!-- end icons -->

            <span class="sidebar-text"><?php echo $primary_name;?></span>
          </span>
          <span class="link-arrow">
            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
          </span>
        </span>
        <div class="multi-level collapse "
          role="list" id="<?php echo $id.'_menu'.$randomid;?>" aria-expanded="false">
          <ul class="flex-column nav">
          <li class="nav-item ">
              <a class="nav-link" href="<?php echo site_url('admin', $url);?>">
                <span class="sidebar-text"><?= $parentmenu; ?></span>
              </a>
            </li>
            <?php add_action('IQ_admin_sidebar_menu_'.$id);?>
            <?php if(isset($extramenu)){
                  foreach($extramenu as $multidata){
                      foreach($multidata as $data=>$val){ ?>
            <li class="nav-item ">
              <a class="nav-link" href="<?php echo site_url('admin', $val);?>">
                <span class="sidebar-text"><?php echo $data; ?></span>
              </a>
            </li>
                 <?php     }
                  }
            }?>
            
            
          </ul>
        </div>
      </li>
  <?php }

  // end admin_sidebar_menu()
  
  ?>
    <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="../../index.html">
        <img class="navbar-brand-dark" src="<?php site_options('logo'); ?>" alt="<?php site_options('title'); ?>" /> 
        <img class="navbar-brand-light" src="<?php site_options('logo'); ?>" alt="<?php site_options('title'); ?>" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

        <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
  <div class="sidebar-inner px-4 pt-3">
    <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
      <div class="d-flex align-items-center">
        <div class="avatar-lg me-4">
          <img src="<?php echo assets('users/'.loggedinuser('return_featured_img'), 'upload'); ?>" class="card-img-top rounded-circle border-white"
            alt="Bonnie Green">
        </div>
        <div class="d-block">
          <h2 class="h5 mb-3">Hi, <?php loggedinuser('full_name'); ?></h2>
          <a href="<?= site_url('admin','logout.php'); ?>" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
            <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>            
            Sign Out
          </a>
        </div>
      </div>
      <div class="collapse-close d-md-none">
        <a href="#sidebarMenu" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
            aria-label="Toggle navigation">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
          </a>
      </div>
    </div>
    <ul class="nav flex-column pt-3 pt-md-0">
      <li class="nav-item">
        <a href="<?php echo site_url('admin'); ?>" class="nav-link d-flex align-items-center">
          <span class="sidebar-icon">
            <img src="<?php site_options('logo'); ?>" height="20" width="20" alt="<?php site_options('title'); ?>">
          </span>
          <span class="mt-1 ms-1 sidebar-text"><?php site_options('title'); ?></span>
        </a>
      </li>
      <li class="nav-item  active ">
        <a href="<?php echo site_url('admin',''); ?>" class="nav-link">
          <span class="sidebar-icon">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
          </span> 
          <span class="sidebar-text">Dashboard</span>
        </a>
      </li>
      
      <!-- menu area -->
    <?php
// update checker
if(IQ_data('update_status')){
    single_admin_sidebar_menu('Update','update.php','<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>');
          }
    
    add_admin_sidebar_menu(array('primary_name'=>'Media','name'=>'Add Media', 'url'=>'media.php?action=new','extra_menu'=>array(array('All Media'=>'media.php'))));
    apply_taxonomies();
    add_action('register_admin_sibebar');



    add_admin_sidebar_menu(array('primary_name'=>'Addons','icon'=>'<i class="icon icon-xs me-2 ri-plug-fill"></i>','name'=>'Add Addons','url'=> 'addons.php?action=new','extra_menu'=>array(array('All Addons'=>'addons.php'))));  
    $themes = array(array('Themes'=>'themes.php'), array('Theme Editor'=>'theme_editor.php'));
    add_admin_sidebar_menu(array('primary_name'=>'Themes','name'=>'Add Themes','icon'=>'<i class="icon icon-xs me-2 ri-pencil-ruler-2-fill"></i>','url'=>'themes.php?action=new', 'extra_menu'=>$themes));  
    add_admin_sidebar_menu(array('primary_name'=> 'Users','name'=>'Add user', 'url'=>'users.php?action=new','icon'=>'<i class="icon icon-xs me-2 ri-user-fill"></i>', 'extra_menu'=>array(array('All Users'=>'users.php'))));  

    
    ?>


      <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
      <?php 
    add_admin_sidebar_menu(array('primary_name'=>'Settings','name'=>'General Setting','icon'=>'<i class="icon icon-xs me-2 ri-equalizer-fill" ></i>','url'=> 'settings.php','extra_menu'=>array(array('SMTP'=>'settings.php?page=smtp'))));  
    ?>
    

      
      <!-- <li class="nav-item">
        <a href="<?php // global $site_url; echo $site_url; ?>" target="_blank"
          class="btn btn-secondary d-flex align-items-center justify-content-center btn-upgrade-pro">
          <span class="sidebar-icon d-inline-flex align-items-center justify-content-center">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
          </span> 
          <span>Visit to Frontend</span>
        </a>
      </li> -->
    </ul>
  </div>
</nav>

    <?php
  // example
//   function name(){
//   admin_sidebar_menu('paramentor');
// or
// single_admin_sidebar_menu('parametors);

  // apply_taxonomies();
// }

// add_action('register_admin_sibebar', 'name');

// add menu in menu from add_action or other addons and themes

// function echox(){
//   global $extramenu;
//   $extramenu[] = array('name'=>'hello');
//   $extramenu[] = array('wr'=>'asdf');
// }
// add_action('IQ_admin_sidebar_menu_GeneralSetting', 'echox');


  
  } ?>