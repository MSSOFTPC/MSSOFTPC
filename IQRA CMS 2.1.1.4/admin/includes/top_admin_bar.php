<?php 
function IQ_top_admin_bar(){
    if(loggedinuser('return_role') == 'admin'){
        global $registered_taxonomies;
    //     echo '<pre>';
    // print_r($registered_taxonomies);

    // $data['id']
    // $data['class']
    // $data['menus'] = array(array('id'=>'','path'=>'','title'=>''))



    function IQ_add_top_bar_sub_menus($data){
        if(!isset($data['id'])){ $data['id'] = ''; }
        if(!isset($data['class'])){ $data['class'] = ''; }
       echo  '<ul id="'.$data['id'].'" class="ab-submenu '.$data['class'].'">';
   
        foreach($data['menus'] as $menus){

       echo  '<li id="'.$menus['id'].'">';
       echo   '<a class="ab-item" href="'.$menus['path'].'" >'.$menus['title'].'</a>';
       echo   ' </li>';

        }


       echo   '</ul>';
    }

//     example
//     $menus[] = array('id'=>'lismar','path'=>'li path','title'=>'working');
//    $menus[] = array('id'=>'yes','path'=>'li yes','title'=>'yes');
//    IQ_add_top_bar_sub_menus(array("id"=>"sub_samar",'class'=>'class_sub_samar','menus'=>$menus));


    
        // $data['id']
    // $data['path']
    // $data['title']

function IQ_add_top_bar_menu($data){ ?>
<li id="<?= $data['id']; ?>" class="menupop">
<a class="ab-item" aria-haspopup="true" href="<?= $data['path']; ?>">
        <span class="ab-icon" aria-hidden="true"></span>
        <span class="ab-label"><?= $data['title']; ?></span>
</a>
<div class="ab-sub-wrapper">
   <?php 
            add_action('IQ_admin_topbar_wrapper_'.$data['id']);
    ?>
 
</div>
</li>
<?php }
// example
// add_action('IQ_admin_topbar_wrapper_'.$data['id'],'function()');

// IQ_add_top_bar_menu(array('id'=>'smar','path'=>'samar.php','title'=>'Hello'));


    ?>
   
        <div id="IQ_top_admin_bar">

        <div id="IQadminbar" class="nojq">
						<div class="quicklinks" id="wp-toolbar" role="navigation" aria-label="Toolbar">
				<ul id="IQ-admin-bar-root-default" class="ab-top-menu">
                    <li id="IQ-admin-bar-menu-toggle">
                        <a class="ab-item" href="#" aria-expanded="false">
                            <span class="ab-icon" aria-hidden="true"></span>
                            <span class="screen-reader-text">Menu</span></a>
                        </li>

                        <!-- about -->
                        <li id="IQ-admin-bar-wp-logo" class="menupop">
                            <a class="ab-item" aria-haspopup="true" href="https://iqra.mssoftpc.com/about-us">
                                <span class="ab-icon" aria-hidden="true"><img src="<?= adminassets('iqra logo.png','img'); ?>"  alt=""></span>
                            </a>
                            <div class="ab-sub-wrapper">
                                <ul id="IQ-admin-bar-wp-logo-default" class="ab-submenu">
                                    <li id="IQ-admin-bar-about">
                                        <a class="ab-item" href="https://www.iqra.mssoftpc.com/about" target="_blank">About IQRA CMS</a>
                                    </li>
                                </ul>
                                <ul id="IQ-admin-bar-wp-logo-external" class="ab-sub-secondary ab-submenu">
                                    <li id="IQ-admin-bar-wporg">
                                        <a class="ab-item" href="https://mssoftpc.com/" target="_blank">Official Site</a></li>
                                        <li id="IQ-admin-bar-documentation">
                                            <a class="ab-item" href="https://www.iqra.mssoftpc.com/documentation">Documentation</a>
                                        </li>
                                        <li id="IQ-admin-bar-support-forums">
                                            <a class="ab-item" href="https://www.iqra.mssoftpc.com/contact-us">Contact US</a>
                                        </li>
                                        <li id="IQ-admin-bar-feedback">
                                            <a class="ab-item" href="https://www.iqra.mssoftpc.com/request-feedback">Feedback</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        <!-- about -->

                            <li id="IQ-admin-bar-site-name" class="menupop">
                                <a class="ab-item" aria-haspopup="true" href="<?= site_url('admin'); ?>"><?php site_options('title'); ?></a>
                                <div class="ab-sub-wrapper"><ul id="IQ-admin-bar-site-name-default" class="ab-submenu">
                                    <li id="IQ-admin-bar-view-site">
                                        <a class="ab-item" href="<?= site_url(''); ?>" target="blank">Visit Site</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- new post type -->
                      
                        <li id="IQ-admin-bar-new-content" class="menupop">
                            <a class="ab-item" aria-haspopup="true" href="<?= site_url('admin','view.php?post_type=page&action=new'); ?>">
                                <span class="ab-icon" aria-hidden="true"></span>
                                <span class="ab-label">New</span>
                            </a>
                            <div class="ab-sub-wrapper">
                                <ul class="ab-submenu">

                                <?php 
                                foreach($registered_taxonomies as $post_type){
                                    $post_type['supports'] = explode(',', $post_type['supports']);
                                    if(empty($post_type['supports'][0]) || !empty($post_type['supports']) && in_array('new',$post_type['supports'])){
                                    echo ' <li> <a class="ab-item" href="'.site_url('admin','view.php?post_type='.$post_type['post_type'].'&action=new').'">'.$post_type['name'].'</a> </li>';
                                    }
                                }
                                ?>
                                       
                                
                                </ul>
                            </div>
                        </li>
                        <!-- new post type -->

                        <!-- other add -->
                       <?php add_action('IQ_top_admin_bar');   ?>
                      
                    </ul>
                </div>
            </li>

                <!-- end other -->
                
                    </ul>
                

                <!-- right area -->
                                <ul id="IQ-admin-bar-top-secondary" class="ab-top-secondary ab-top-menu">
                                    <li id="IQ-admin-bar-my-account" class="menupop with-avatar">
                                    <a class="ab-item" aria-haspopup="true" href="<?= site_url('admin','users.php?action=edit&id='.loggedinuser('return_id').''); ?>">Howdy, <span class="display-name"><?php loggedinuser('full_name') ?></span>
                                    <img alt="" src="<?php echo assets('users/'.loggedinuser('return_featured_img'),'upload')  ?>" class="avatar avatar-26 photo" height="26" width="26" loading="lazy" decoding="async"></a>
                                    <div class="ab-sub-wrapper">
                                        <ul id="IQ-admin-bar-user-actions" class="ab-submenu">
                                        <li id="IQ-admin-bar-user-info">
                                        <a class="ab-item" tabindex="-1" href="<?= site_url('admin','users.php?action=edit&id='.loggedinuser('return_id').''); ?>">
                                            <img alt="" src="<?php echo assets('users/'.loggedinuser('return_featured_img'),'upload')  ?>" class="avatar avatar-64 photo" height="64" width="64" loading="lazy" decoding="async">
                                        <span class="display-name"><?php loggedinuser('full_name') ?></span>
                                        <span class="username"><?php loggedinuser('email') ?></span>
                                    </a>
                                    </li>
                                        <li id="IQ-admin-bar-edit-profile">
                                            <a class="ab-item" href="<?= site_url('admin','users.php?action=edit&id='.loggedinuser('return_id').''); ?>">Edit Profile</a></li>
                                            <li id="IQ-admin-bar-logout">
                                            <a class="ab-item" href="<?= site_url('admin','logout.php'); ?>">Log Out</a>
                                    </li>
                                    </ul>
                    <!-- right area end -->


                                    </div>
                                    </li>
                                    </ul>
                                    			</div>
						

					</div>      


        </div>


<?php }
}