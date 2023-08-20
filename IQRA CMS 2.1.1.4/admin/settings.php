
<?php 
include('include.php');
head('IQ Settings');
loginonly('');
global $registered_IQ_admin_site_options;


?>        
<?php get_sidebar(); ?>
        <main class="content">
<?php get_header();?>

<!-- main body start -->
<br><br>
<?php 

// Setting
if(isset($_POST['siteoptions_submit'])){

        add_action('before_insert_site_options');

    foreach($_POST as $option=>$val){
        if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }
        if( $option != 'socialmedia' && $option != 'siteoptions_submit' && $option != 'social_type'){ 
            
            $update_data = ['option_value'=>$val];

            $fetch = fetch('site_options','option_name="'.$option.'"');
           
            if(!empty($fetch)){
            updatedata($update_data, 'option_name="'.$option.'"', 'site_options'); 
        }else{
            $update_data += ['option_name'=>$option];
            insertdata($update_data,'site_options');
            
        }

            // updatedata($update_data, 'option_name="'.$option.'"', 'site_options');
        }
    }

    // file upload
    foreach($_FILES as $option=>$val){
        if(!empty($_FILES[$option]['name'])){
        IQ_media_insert(array('dir'=>'site','media'=>$option));
        $files_meta = ['option_value' => $_FILES[$option]['name']];
        $fetch = site_options('return_'.$option);
        if(!empty($fetch)){
        updatedata($files_meta, 'option_name="'.$option.'"', 'site_options'); 
    }else{
        $files_meta += ['option_name'=>$option];
        insertdata($files_meta,'site_options');
    }

    }
    }

    if(isset($_POST['socialmedia'])){

            $social_media = $_POST['socialmedia'];
            $social_type = $_POST['social_type'];
            
            $decodedata = fetch('site_options', 'option_name="social_media"');
            $socialgroup = array();
        
            $i = 0;
            foreach($social_type as $socialtypes){
                if(!empty($social_media[$i])){ 
                $socialgroup += [$socialtypes => $social_media[$i]];
                }
                $i++;
            } 
            $datainsert = array('option_value'=>htmlentities(json_encode($socialgroup)));
            updatedata($datainsert, 'option_name="social_media"','site_options');

    }
    
    IQ_notice('success_notice','Settings Updated Successfully');
    back('reload');
    
}

// pages checker

IQ_add_admin_notice('options');

?>

<div class="row">
                <div class="col-12 col-xl-8">
                    <div class="card card-body border-0 shadow mb-4">
                    <?php 
                                $IQ_admin_option_status = true;
                                $IQ_admin_Option_title = 'Website information';
                                $IQ_admin_option_function_name = '';
                                if(isset($_GET['page']) && !empty($_GET['page'])){
                                foreach($registered_IQ_admin_site_options as $options){
                                    if($options['slug'] == $_GET['page']){
                                        $IQ_admin_Option_title = $options['title'];
                                        $IQ_admin_option_function_name = $options['function'];
                                        $IQ_admin_option_status = false;

                                    }
                                }
                                }
                                ?>
                        <h2 class="h5 mb-4"><?= $IQ_admin_Option_title; ?></h2>
                        <form action="<?php echo site_url('currentURL'); ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <?php if(!empty($IQ_admin_option_function_name)){ if(function_exists($IQ_admin_option_function_name)){ call_user_func($IQ_admin_option_function_name);}else{ echo 'Function Not exist';}; }?>
                 
                                <?php if($IQ_admin_option_status == true){ ?>
                                <?php get_field(array('title'=>'Site Title','name'=>'title', 'class'=>'col-md-6 mb-3','value'=>site_options('return_title'),'required'=>'')); ?>
                                <?php get_field(array('title'=>'Tag Line','name'=>'shortname', 'class'=>'col-md-6 mb-3','value'=>site_options('return_shortname'))); ?>
                      
                                <div class="col-md-6 mb-3">
                                    <label for="siteurl">Site URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                        </span>
                                        <input class="form-control" id="siteurl"  value="<?php  site_options('url'); ?>" type="url" disabled>                                            
                                     </div>
                                </div>

                                <?php get_field(array('title'=>'Email','name'=>'email', 'class'=>'col-md-6 mb-3','value'=>site_options('return_email'),'required'=>'')); ?>
                                <?php get_field(array('title'=>'Phone','name'=>'phone', 'class'=>'col-md-6 mb-3','value'=>site_options('return_phone'))); ?>
                                <?php get_field(array('title'=>'Logo','name'=>'logo','type'=>'file', 'class'=>'col-md-6 mb-3')); ?>

                                <!-- time zone -->
                                <?php
                                $time_zone = ['IQ_time_zone'=>site_options('return_IQ_time_zone')];

                                $time_zone_options[] = array('','Select Time Zone');
                                $timezone_list =   DateTimeZone::listIdentifiers(  DateTimeZone::ALL );
                                foreach($timezone_list as $timezonelist){
                                    $time_zone_options[] = array($timezonelist,$timezonelist);
                                }
                                // print_r($timezone_list);

                                get_field(array('title'=>'Select Time Zone','name'=>'IQ_time_zone','type'=>'select','isset'=>$time_zone, 'options'=>$time_zone_options,'class'=>'col-md-6 mb-3')); 
                                
                                    // front page
                                $page_isset = ['front_page'=>site_options('return_front_page')];
                                $front_page = get_post(array('post_type'=>'page'));
                                $options[] = array('','Select Front Page');
                                if(empty($front_page)){ $front_page = []; }
                                foreach($front_page as $data){
                                    $options[] = array($data['id'],$data['title'], '');
                                }
                                get_field(array('title'=>'Select Front Page','name'=>'front_page','type'=>'select','isset'=>$page_isset, 'options'=>$options,'class'=>'col-md-6 mb-3')); 
                                
           
                                // extra
                                add_action('admin_options_fields');
                                                        ?>

                                <div class="col-md-6 mb-3" id="socialmediarow">
                                        <label for="siteurl">Social Media Links</label>
                                        <div class="input-group">
                                        <select name="social_type[]" class="form-control " id="" value="">
                                            <option value="facebook" class="form-control" >facebook</option>
                                            <option value="twitter" class="form-control" >twitter</option>
                                            <option value="linkedin" class="form-control" >linkedin</option>
                                            <option value="instagram" class="form-control" >instagram</option>
                                        </select>
                                        <input class="form-control" id="socialmedia" type="url" name="socialmedia[]" value="" Placeholder="https://facebook.com">                                         
                                        <span id="btnarea">
                                            <button type="button" class="btn btn-gray-800" id="addmore"> + </button> 
                                        </div>
                                </div>
                                    <?php   $decode =   json_decode(html_entity_decode(site_options('return_social_media'))); 
                                    foreach($decode as $dejson=>$val){  ?>

                                    <div class="col-md-6 mb-3 disabled" id="socialmediarow">
                                            <label for="siteurl">Social Media Links</label>
                                            <div class="input-group">
                                            <select name="social_type[]" class="form-control " id="" value="<?php echo $dejson; ?>" >
                                                <option value="facebook" class="form-control" <?php if($dejson == 'facebook'){echo 'selected';} ?>>facebook</option>
                                                <option value="twitter" class="form-control" <?php if($dejson == 'twitter'){echo 'selected';} ?>>twitter</option>
                                                <option value="linkedin" class="form-control" <?php if($dejson == 'linkedin'){echo 'selected';} ?>>linkedin</option>
                                                <option value="instagram" class="form-control" <?php if($dejson == 'instagram'){echo 'selected';} ?>>instagram</option>
                                            </select>
                                            <input class="form-control" id="socialmedia" type="url" name="socialmedia[]" value="<?php echo $val; ?>" Placeholder="https://facebook.com">                                         
                                            <span id="btnarea">
                                            <button type="button" class="btn btn-danger" id="btnremove"> X </button> </div>
                                            </div>

                                <?php }} ?>

                                    </div>
                          
                                <?php get_field(array('title'=>'Save All', 'field_class'=>'btn btn-gray-800 mt-2 animate-up-2','name'=>'siteoptions_submit','type'=>'submit', 'label'=>0)); ?>
                                    
                           
                        </form>
                    </div>
                  
                </div>
                <div class="col-12 col-xl-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card shadow border-0 text-center p-0">
                                <div class="profile-cover rounded-top" data-background="../assets/img/profile-cover.jpg" style="background: url(&quot;../assets/img/profile-cover.jpg&quot;);"></div>
                                <div class="card-body pb-5">
                                    <img src="<?php site_options('logo') ?>" class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Neil Portrait">
                                    <h4 class="h3"><?php site_options('title'); ?></h4>
                                    <h5 class="fw-normal"><?php site_options('shortname'); ?></h5>
                                    <p class="text-gray mb-4"><?php  site_options('shortname'); ?></p>
                                    <form action="" method="post">
                                    <?php 
                                    if(site_options('return_maintenance') == 1){ $maintainance = 0; }else{ $maintainance = 1;} ?>
                                    <input type="hidden" name="maintenance" value="<?php echo $maintainance; ?>">
                                    <button class="btn btn-sm btn-<?php if($maintainance != '1'){echo 'success';}else{echo 'danger';} ?>" name="siteoptions_submit">Maintainance Mode <?php if(site_options('return_maintenance') == '1'){echo 'Active';}else{echo 'Inactive';} ?></button>
                                    </form><br>
                                    
                                    <?php

                                // extra
                                add_action('IQ_admin_options_sidebar');
                                                        ?>

                                  
                                </div>
                             </div>
                        </div>
                        <div class="col-12">
                            
                        </div>
                        <div class="col-12">
                           
                        </div>
                    </div>
                </div>
            </div>

<!-- main body end -->


<!-- clone function  -->
<script>
    $('#addmore').click(function () { 
                clone = $( "#socialmediarow").clone();
                clone.find('#addmore').remove();
                clone.find('.youtube_links').attr('id', '');
                clone.find('#btnarea').append('<button type="button" class="btn btn-danger" id="btnremove"> X </button>');
                clone.insertAfter('#socialmediarow');  
                  
            });
           
            
            $(document).on('click', '#btnremove', function () { 
                                    $(this).closest('#socialmediarow').remove();
                
            });
</script>
<?php footer(); ?>