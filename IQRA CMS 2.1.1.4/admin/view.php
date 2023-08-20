
<?php

include('include.php');
if(!isset($_GET['post_type'])){ die("You Can't Access This Area"); }
$get = get_post(array('post_type'=>$_GET['post_type']));
$meta_array = [];


function post_type_function_exec($post_type,$function){
    if($_GET['post_type'] == $post_type){
        call_user_func($function);
    }
}

loginonly('');

// add_action('admin_action_tab');
// add_action('admin_action_field');
// add_action('admin_action_sidebar_field');

// for handle foreach error
admin_view_fields('', '','');


class view {
   private $head_title;
    private $get_post_type;

    function __construct(){
        // conditions
        if(!isset($_GET['post_type']) || empty($_GET['post_type'])){ die("You Can't Access This Area");}
        $this->get_post_type = $_GET['post_type'];
        if(isset($_GET['action']) && empty($_GET['action'])){ IQ_die("You Can't Access This Area");}
        if(isset($_GET['action']) && $_GET['action'] == 'edit' && !isset($_GET['id']) || isset($_GET['id']) && empty($_GET['id'])){ IQ_die("You Can't Access This Area");}
        if(isset($_GET['action']) && $_GET['action'] == 'del' && !isset($_GET['id']) || isset($_GET['id']) && empty($_GET['id'])){ IQ_die("You Can't Access This Area");}
        // end conditions
        
        head($this->head_title);
        get_sidebar();
        global $registered_taxonomies, $current_taxonomies,$current_taxonomy; $registered_taxonomies_status = 0; foreach($registered_taxonomies as $data){ if($data['post_type'] == $this->get_post_type){$registered_taxonomies_status = 1; $current_taxonomies = $data; }}
        // taxonomies
        $current_taxonomy = '';
        if(isset($current_taxonomies['taxonomy'])){
                $all_tags = $current_taxonomies['taxonomy'];
                $current_taxonomy = $all_tags;
        }

        if($registered_taxonomies_status == 0){back('404');}
        echo '<main class="content">';
        get_header();
     
    }
     function action(){
        global $conn,$db,$meta_array, $registered_taxonomies, $current_taxonomies,$current_taxonomy;
        $get_post_type = $this->get_post_type;
        if(isset($_GET['action']) && !empty($_GET['action'])){
            $current_taxonomies['supports'] = explode(',', $current_taxonomies['supports']);
           
            IQ_add_admin_notice('post');
            // end extra

            // delete btn
            if($_GET['action'] == 'del'){ IQ_post_delete($_GET['id']); add_action('post_delete_action'); $_SESSION['success_post_list_notice'] = $current_taxonomies['name'].' Deleted Successfully'; back('view.php?post_type='.$get_post_type); }
            // end delete action
             // delete btn
             if($_GET['action'] == 'clone'){ $_SESSION['error'] = ' Action Not Working'; back('view.php?post_type='.$get_post_type);  }
             // end delete action

            if($_GET['action'] == 'new' || $_GET['action'] == 'edit'){

            //  action insert data to mysql
                if(isset($_POST['post_submit'])){
                    global $result,$arry;
                    $IQ_post = $_POST;
                    $arry = [];
                    $arry += ['title' => $_POST['title']];
                    unset($IQ_post['title']);
                    unset($IQ_post['post_type']);
                    $arry += ['post_type' => $_POST['post_type']];
                    $arry += ['author_id' => loggedinuser('return_id')];

                    // conditions
                    if(!isset($_POST['title']) && empty($_POST['title'])){ back(''); $_SESSION['error'] = 'Title Not Found'; }
                    // end conditions

                    if(isset($_POST['id'])){ $id = $_POST['id']; unset($IQ_post['id']); }
                    if(isset($_POST['content'])){ $arry += ['content' =>  $_POST['content']]; unset($IQ_post['content']);}
                    if(isset($_POST['status'])){ $arry += ['status' => $_POST['status']]; unset($IQ_post['status']);}
                    if(isset($_POST['layout'])){ $arry += ['layout' => $_POST['layout']]; unset($IQ_post['layout']);}
                    if(isset($_POST['status'])){ $arry += ['status' => $_POST['status']]; unset($IQ_post['status']);}
                    if(isset($_POST['breadcrumb'])){ $arry += ['breadcrumb' => $_POST['breadcrumb']]; unset($IQ_post['breadcrumb']);}
                    if(isset($_POST['parent'])){ $arry += ['parent' => $_POST['parent']]; unset($IQ_post['parent']);}
                    if(isset($_POST['post_date']) && !empty($_POST['post_date'])){ $arry += ['post_date' => $_POST['post_date']]; unset($IQ_post['post_date']);}
                    if(isset($_POST['tax_input']) && !empty($_POST['tax_input'])){ $tax_input = $IQ_post['tax_input']; unset($IQ_post['tax_input']);}
                   

                    if(isset($_FILES['featured_img'])){ 
                        if(!empty($_FILES['featured_img']['name'])){
                        IQ_media_insert(array('media'=>'featured_img','dir'=>$get_post_type));
                        $arry += ['featured_img' => $_FILES['featured_img']['name']];
                    }
                    }


                    if($_POST['post_submit'] == 'publish'){
                        unset($IQ_post['post_submit']);
                        $date = new \DateTime();

                          // permalink area
                            if(isset($_POST['permalink'])){ 
                                if(empty($_POST['permalink'])){ $permalink = $_POST['title']; }else{$permalink = $_POST['permalink']; }
                                $permalink = str_replace(' ', '-', $permalink);
                                $permalink = strtolower($permalink);
                                $fetch = $db->fetch_all('post', 'permalink="'.$permalink.'"');
                                if(count($fetch) != 0){
                                    $_POST['permalink'] = $fetch[0]['permalink'].'-'.IQ_random_generator('10');
                                    IQ_notice('error_post_notice','Permalink Changed Because its already Exist');
                                }else{
                                    $_POST['permalink'] = $permalink;
                                }

                                $arry += ['permalink' => $_POST['permalink']]; 
                                unset($IQ_post['permalink']);

                             }
                        //  permalink area end

                        $arry += ['post_date'=> date_format($date, 'Y-m-d H:i:s')];
                        add_action('before_post_submit');
                        insertdata($arry, 'post');  
                        $insert_id = $conn->insert_id;

                        // taxonomy
                        if(isset($tax_input)){
                            foreach($tax_input as $taxonomies_input){
                                    if(!empty($taxonomies_input[0])){
                                        foreach($taxonomies_input as $tax_term){
                                            $tax_term_data = [];
                                            $tax_term_count = fetch('taxonomy','term_id="'.$tax_term.'"');
                                            $tax_term_count['count'] = $tax_term_count['count'] + 1;
                                            $tax_term_data = ['count'=>$tax_term_count['count']];
                                            updatedata($tax_term_data, 'term_id='.$tax_term,'taxonomy');

                                            // insert object to relationship
                                            $tax_relation_data = array();
                                            $tax_relation_data += ['object_id'=>$insert_id];
                                            $tax_relation_data += ['term_taxonomy_id'=>$tax_term_count['id']];
                                            insertdata($tax_relation_data ,'term_relationships');

                                        }
                                    }
                            }
                        }

                    //  insert data to post_meta
                    $result=$IQ_post;
                    add_action('before_post_meta_insert_action');
                    
                    foreach($result as $post_meta=>$val){
                         if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }

                        $meta_data = [];
                            $meta_data += ['post_id' => $insert_id];
                            $meta_data += ['meta_key' => $post_meta];
                            $meta_data += ['meta_value' => $val];
                            insertdata($meta_data, 'post_meta'); 
                    }
                    // files validation
                    $files_meta = [];
                    foreach($_FILES as $files=>$fileval){
                        if($files != 'featured_img' && !empty($_FILES[$files]['name'])){
                            IQ_media_insert(array('media'=>$files,'dir'=>$get_post_type));
                            $files_meta += ['post_id' => $insert_id];
                            $files_meta += ['meta_key' => $files];
                            $files_meta += ['meta_value' => $_FILES[$files]['name']];
                            insertdata($files_meta, 'post_meta'); 
                        }
                    }


                    //  insert data to post_meta

                    add_action('post_insert_action');
                     back('view.php?post_type='.$_POST['post_type'].'&action=edit&id='.$insert_id);
                     $_SESSION['success_post_notice'] = $_POST['title'].' Published';
                    }
                  
               
                    if($_POST['post_submit'] == 'update'){
                        unset($IQ_post['post_submit']);

                       // permalink area
                       if(isset($_POST['permalink'])){ 
                        if(empty($_POST['permalink'])){ $permalink = $_POST['title']; }else{$permalink = $_POST['permalink']; }
                        $permalink = str_replace(' ', '-', $permalink);
                        $permalink = strtolower($permalink);
                        $fetch = $db->fetch_all('post', 'permalink="'.$permalink.'"');
                        if(count($fetch) != 0){
                            if($permalink == $fetch[0]['permalink']){
                                $_POST['permalink'] = $fetch[0]['permalink'];
                            }else{
                                $_POST['permalink'] = $fetch[0]['permalink'].'-'.IQ_random_generator('10');
                                IQ_notice('error_post_notice','Permalink Changed Because its already Exist');
                            }
                            
                        }else{
                            $_POST['permalink'] = $permalink;
                        }

                        $arry += ['permalink' => htmlspecialchars($_POST['permalink'])];
                        unset($IQ_post['permalink']);

                     }
                    //  permalink area end
                    add_action('before_post_submit');
                    updatedata($arry, 'id='.$id,'post');

                    // taxonomy
                    if(isset($tax_input)){
                        // remove all objects tax
                        unlink_taxonomy(array('id'=>$id));
                        foreach($tax_input as $taxonomies_input){
                                if(!empty($taxonomies_input[0])){
                                    foreach($taxonomies_input as $tax_term){
                                            $tax_term_data = [];
                                            $tax_term_count = fetch('taxonomy','term_id="'.$tax_term.'"');
                                            $tax_term_count['count'] = $tax_term_count['count'] + 1;
                                            $tax_term_data = ['count'=>$tax_term_count['count']];
                                            updatedata($tax_term_data, 'term_id='.$tax_term,'taxonomy');

                                            // insert object to relationship
                                            $tax_relation_data = array();
                                            $tax_relation_data += ['object_id'=>$id];
                                            $tax_relation_data += ['term_taxonomy_id'=>$tax_term_count['id']];
                                            insertdata($tax_relation_data ,'term_relationships');
                                    }
                                }
                        }
                    }
                    
                    $result=$IQ_post;
                    add_action('before_post_meta_update_action');
                    foreach($result as $post_meta=>$val){
                        if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }

                        $meta_data = [];
                        $meta_data += ['meta_value' => $val];
                        $fetch = fetch('post_meta','post_id='.$id.' and meta_key="'.$post_meta.'"');
                        if(empty($fetch)){
                                $meta_data += ['post_id'=>$id];
                                $meta_data += ['meta_key'=>$post_meta];
                            insertdata($meta_data, 'post_meta'); 
                        }else{
                            updatedata($meta_data, 'post_id='.$id.' and meta_key="'.$post_meta.'"', 'post_meta'); 
                        }
                    }

                    // files validation
                    $files_meta = [];
                    foreach($_FILES as $files=>$fileval){
                        if($files != 'featured_img' && !empty($_FILES[$files]['name'])){
                            IQ_media_insert(array('media'=>$files,'dir'=>$get_post_type));
                            $files_meta += ['meta_value' => $_FILES[$files]['name']];
                            $fetch = fetch('post_meta','post_id='.$id.' and meta_key="'.$files.'"');
                        if(empty($fetch)){
                                $files_meta += ['post_id'=>$id];
                                $files_meta += ['meta_key'=>$files];
                            insertdata($files_meta, 'post_meta'); 
                        }else{
                            updatedata($files_meta, 'post_id='.$id.' and meta_key="'.$post_meta.'"', 'post_meta'); 
                        }
                        }
                    }
                    add_action('post_update_action');
                    $_SESSION['success_post_notice'] = $_POST['title'].' Updated';
                    back('view.php?post_type='.$_POST['post_type'].'&action=edit&id='.$id);
                    }



                }
                // action insert data to mysql





        // Action new area
        $rows = fetch('site_options','');

            
        // for new 
        $titlename = 'Add New '.$current_taxonomies['name']; 
        $submit = 'publish'; 
        $submitbtn = 'Publish'; 
        $actionlink = '&action=new';
        global $fetchrows;
        if($_GET['action'] == 'edit'){
            $actionlink = '&action=edit&id='.$_GET['id'];
            $fetchrows = fetch('post', 'post_type="'.$get_post_type.'" && id='.$_GET['id']);
            // fetch meta data
            $fetch_post_meta = $db->fetch_all('post_meta', 'post_id='.$_GET['id']);
             $i = 0;
            foreach($fetch_post_meta as $data){
                foreach($data as $datakey=>$dataval){
                    if($datakey != 'id' && $datakey != 'post_id' && $datakey != 'meta_value'){
                    $meta_array += [$dataval=>$fetch_post_meta[$i]['meta_value']];
                }   }  $i++;  }

                global $meta_array;
            // end meta data
            if(empty($fetchrows)){ $_SESSION['error'] == 'Data Not Found'; back('view.php?post_type='.$get_post_type); die();  }
            if(!isset($_GET['id']) || empty($_GET['id'])){ back(); }
            $titlename = 'Edit '.$fetchrows['title']; 
            $submit = 'update'; 
            $submitbtn = 'Update';
            $id = $fetchrows['id'];

            function breadcrumbview($fetchrows){
                add_admin_dashboard_breadcrumb_menu(array(array('name'=>'View','url'=>site_url('',$fetchrows['permalink']))));
            }
            add_action('admin_dashboard_breadcrumb', 'breadcrumbview',$fetchrows);

        }

        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('new',$current_taxonomies['supports'])){
        dashbreadcrumb('view.php?post_type='.$_GET['post_type'].'&action=new',array(array('Back'=>'view.php?post_type='.$get_post_type))); 
        }

        echo    '<form action="'.site_url('admin','view.php').'?post_type='.$get_post_type.$actionlink.'" method="post" enctype="multipart/form-data">';
            echo '<div class="row">';

            echo '<div class="col-12 col-xl-8">
                    <div class="card card-body border-0 shadow mb-4">';
            echo    '<h2 class="h5 mb-4" style="text-capitalize">'.$titlename.'</h2>';
            
      
            
            if(!isset($id)){ $id = '';}
            echo    '<input type="hidden" name="id" value="'.$id.'">';
            echo    '<input type="hidden" name="post_type" value="'.$get_post_type.'">';

            echo     '<div class="row align-items-center">';

            // title field
            get_field(array('isset'=>$fetchrows,'title'=>'Title','name'=>'title','class'=>'col-md-6 mb-3', 'placeholder'=>'Title', 'required'=>''));
        
            // Permalink field
        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('permalink',$current_taxonomies['supports'])){
            get_field(array('title'=>'Permalink','name'=>'permalink','class'=>'col-md-6 mb-3' ,'isset'=> $fetchrows, 'placeholder'=>'Permalink', 'required'=>''));
        }

            // Content field
        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('content',$current_taxonomies['supports'])){
            if(!empty($fetchrows['content'])){$fetchrows['content'] = IQ_html_decode($fetchrows['content']);}
            get_field(array('label'=>0,'type'=>'textarea','name'=>'content','isset'=>$fetchrows,'class'=>'mb-3','id'=>'IQ_tiny_mce_editor'));
        }

         // extra
         add_action('admin_action_fields');

        echo '</div>';
        echo '</div>';
            
       
        // extra tabs
        add_action('admin_action_tab');
       

        // page settings
        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('page_settings',$current_taxonomies['supports'])){
        function IQ_admin_page_setting(){
            echo '<div class="row align-items-center">';
            global $fetchrows,$registered_taxonomies;
            $get_post_type = $_GET['post_type'];

            // breadcrumb
         if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('breadcrumb',$current_taxonomies['supports'])){
            $breadcrumb[] = array('0','Disabled', '');
            $breadcrumb[] = array('1','Enable', '');

      
            get_field(array('title'=>'Breadcrumb', 'options'=>$breadcrumb,'name'=>'breadcrumb', 'isset'=> $fetchrows, 'class'=>'col-md-6 mb-3','type'=>'select'));
        }

            // Layout
            if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('layout',$current_taxonomies['supports'])){

                // use this $register_layout[] = ['layout'=>'path']
                if(!isset($register_layout)){ $register_layout = [];}
                $layout[] = array('','Select Layout', '');
                foreach($register_layout as $IQ_layout){
                    if(isset($IQ_layout['layout'])){
                        $layout[] = array($IQ_layout['layout'],$IQ_layout['layout'],'');
                    }
                }
                foreach($registered_taxonomies as $layouttaxi){
                    if($layouttaxi['post_type'] == $get_post_type){
                        if(isset($layouttaxi['layout'],$layouttaxi['layout'])){
                        $layout[] = array($layouttaxi['layout'],$layouttaxi['layout'],'');
                    }
                    }
                }
           
    
                get_field(array('title'=>'Layout', 'options'=>$layout,'name'=>'layout', 'isset'=> $fetchrows, 'class'=>'col-md-6 mb-3','type'=>'select'));
            }
            echo '</div>';
        }
        IQ_dashboard_widget(array('title'=>'Page Settings', 'function'=>'IQ_admin_page_setting'));
    }
       
        // end page settings
                
            // end section
      
            echo ' </div> ';

                // sidebar colomn
                echo '<div class="col-12 col-xl-4">';
                //    publish area
                 echo '<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_publish_area">
                 <!-- header -->
                 <div class="card-header d-flex align-items-center">
                     <h2 class="fs-5 fw-bold mb-0">Publish</h2>
                 
                 </div>
             
                 <!-- body -->
                     <div class="card-body">';
                         
                    // status
        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('status',$current_taxonomies['supports'])){
            $status[] = array('publish','Publish', '');
            $status[] = array('pending','Pending', '');
            $status[] = array('draft','Draft', '');
            $status[] = array('trash','Trash', '');
      
            get_field(array('title'=>'Status', 'options'=>$status,'name'=>'status', 'isset'=> $fetchrows, 'class'=>'','type'=>'select'));
        }

                //   date
                if(isset($fetchrows['post_date'])){  
                    $postdate = substr($fetchrows['post_date'], 0, -3);
                        }else{ $postdate = ''; }
                get_field(array('title'=>'Published On: ','type'=>'datetime-local','isset'=> $postdate,'name'=>'post_date','class'=>'mt-3'));

                     echo '</div>

                     <!--  footer -->
                    <div class="card-footer">';
                         
                   
                            

                    //  delete button
                    if(isset($id) && !empty(($id))){
                    echo '<a class="btn btn-danger" href="'.site_url('admin','view.php?post_type='.$get_post_type.'&action=del&id=').$id.'">Delete</a>'; 
                        }

                         // Submit Button Image
                    get_field(array('title'=>$submitbtn,'label'=>0, 'name'=>'post_submit', 'class'=>'mt-3','field_class'=>'btn btn-gray-800 mt-2 animate-up-2', 'value'=>$submit, 'type'=>'submit'));


                    echo '</div>
              
             
                     <!-- end body -->
             
             
             
                 </div>';
                //  end publish

                // featured img area
                  // Featured Image
        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('featured_img',$current_taxonomies['supports'])){
            
         
                echo '<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_publish_area">
                <!-- header -->
                <div class="card-header d-flex align-items-center">
                    <h2 class="fs-5 fw-bold mb-0">Featured Image</h2>
                
                </div>
            
                <!-- body -->
                    <div class="card-body">';
                    if(isset($fetchrows['featured_img'])){ $featured_img = $fetchrows['featured_img'];}else{ $featured_img = ''; }
                    get_field(array('title'=>'Featured Img', 'name'=>'featured_img', 'img_url'=>assets($get_post_type.'/'.$featured_img, 'upload'), 'type'=>'file'));        
                 
                    echo '</div>
             
            
                    <!-- end body -->
            
            
            
                </div>';
            }    
               //  end featured img area

               // parent
        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('parent',$current_taxonomies['supports'])){
            
         
            echo '<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_publish_area">
            <!-- header -->
            <div class="card-header d-flex align-items-center">
                <h2 class="fs-5 fw-bold mb-0">Parent</h2>
            
            </div>
        
            <!-- body -->
                <div class="card-body">';
                $parents = get_post(array('query'=>'status="publish" and post_type="'.$get_post_type.'"'));
               $parent[] = ['','No Parent'];
                foreach($parents as $parant){
                    if($parant['id'] != $id){
                    $parent[] = [$parant['id'],$parant['title']];
                    }
                }
                    
              
                get_field(array('title'=>'Parent', 'options'=>$parent,'name'=>'parent', 'isset'=> $fetchrows, 'class'=>'','type'=>'select'));
               
             
                echo '</div>
         
        
                <!-- end body -->
        
        
        
            </div>';
        }    
           //  end parent area

            // taxonomies
            if(!empty($current_taxonomy)){
                $post_terms = fetch_terms(array('post_id'=>$id));
                

            foreach($current_taxonomy as $current_terms){
                $terms = get_terms(array('taxonomy'=>$current_terms['permalink']));

            echo '<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_publish_area">
            <!-- header -->
            <div class="card-header d-flex align-items-center">
                <h2 class="fs-5 fw-bold mb-0">'.$current_terms['title'].'</h2>
            </div>
        
            <!-- body -->
                <div class="card-body">';
                $tagoptions = array();
                    foreach($terms as $taxterm){
                        $post_terms_match = '';
                        foreach($post_terms as $terms_match){
                           
                            if($taxterm['id'] === $terms_match['id']){
                                $post_terms_match = 'selected';
                            }
                          
                        }
                       
                        $tagoptions[] = [$taxterm['id'],$taxterm['title'],$post_terms_match];
                    }
                    
               echo get_field(array('label'=>false,'type'=>'select','options'=>$tagoptions,'name'=>'tax_input['.$current_terms['permalink'].']','isset'=>$post_terms,'multi'=>true));
             
                echo '</div>
         
        
                <!-- end body -->
        
        
        
            </div>';
        }
             }
            // end taxonomies



               add_action('admin_action_sidebar_tab');


                echo '</div>';


            echo '</div> </div> ';

            
            //    end      
            echo '</form>';           

                }}


    }
    public function view(){
        if(!isset($_GET['action'])){
        global $db,$current_taxonomies,$current_taxonomy;
        $current_taxonomies['supports'] = explode(',', $current_taxonomies['supports']);

                // extra
                if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('new',$current_taxonomies['supports'])){
                dashbreadcrumb('view.php?post_type='.$_GET['post_type'].'&action=new','',$current_taxonomies['name']); 
                }

                IQ_add_admin_notice('post_list');
                // end extra

                $get_post_type = $_GET['post_type'];

                // query buttons
                echo '<div style="margin: 20px 0;">';
                $currentsite_url = explode('?',site_url('currentURL'));
                echo '<a href="'.$currentsite_url[0].'?post_type='.$get_post_type.'"  class="btn btn-primary" style="margin-right: 10px;">All</a>';
                echo '<a  href="'.$currentsite_url[0].'?post_type='.$get_post_type.'&status=trash" style="margin-right: 10px;" class="btn btn-danger">Trash</a>';

                echo '</div>';




  

        // $rows = $db->fetch_all('post','post_type="'.$get_post_type.'"','pagination','','search');
        $postquery = 'post_type="'.$get_post_type.'"';
        if(isset($_GET['status']) && $_GET['status'] == 'trash'){ $postquery .= ' AND  status="trash"'; }
        $rows = get_post(array('query'=>$postquery, 'pagination'=>true,'search'=>true));
        

        echo '<div class="card card-body shadow border-0 table-wrapper table-responsive">';

// action button
        echo ' <div class="d-flex mb-3">
       
        <form action="function.php" method="post" id="bulkActionform">
        <select class="form-select fmxw-200" aria-label="Message select example" name="bulk_action_type">
            <option selected="" value="">Bulk Action</option>
            <option  value="delete" >Delete</option>
        </select>
        <input type="hidden" name="bulk_action_ids" value="">
        <input type="hidden" name="bulk_action_table" value="post">
        </form>
        <button class="btn btn-sm px-3 btn-secondary ms-3" id="bulkActionBtn">Apply</button>

    </div>';

       echo '
                <table class="table user-table table-hover align-items-center">
                    <thead class="thead-light">';
        echo  '<tr>';
        echo '<th class="border-0 fw-bold rounded-start"><input type="checkbox" class="form-check-input" id="checkAll"></th>';
        echo '<th class="border-0">Title</th>';

        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('featured_img',$current_taxonomies['supports'])){
        echo '<th class="border-0 fw-bold">Image</th>';
        }

        foreach(get_admin_view_fields() as $fiels_data){
            
            foreach($fiels_data as $viewdata=>$val){
                $explodes = explode('|', $val);
                if($explodes[1] == $get_post_type){
                echo '<th class="border-0">'.$viewdata.'</th>';
            }
            }
        }

        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('featured_img',$current_taxonomies['supports'])){
        echo '<th class="border-0 ">Status</th>';
        }

        echo '<th class="border-0 rounded-end">Action</th>';
        echo '</tr></head><body>';
        if(count($rows) == 0){echo '<td class="border-0 fw-bold text-center" colspan="6"><h1>Data Not Found</h1></td>';}else{
            foreach($rows as $data){ 
                echo '<tr>';
                echo '<td class="border-0 fw-bold"><input type="checkbox" class="form-check-input check" value="'.$data['id'].'"></td>';
                echo '<td class="border-0 fw-bold">'.$data['title'].'</td>';

                if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('featured_img',$current_taxonomies['supports'])){
                echo '<td class="border-0 fw-bold"><img src="'.assets($get_post_type.'/'.$data['featured_img'], 'upload').'" class="img-thumbnail" width="100px"></td>';
                }

                foreach(get_admin_view_fields() as $fiels_data){
                    foreach($fiels_data as $viewdata=>$val){
                        $explodes = explode('|', $val);
                        if($explodes[1] == $get_post_type){

                            if(!isset($data[$explodes[0]])){ $data[$explodes[0]] = ''; } 
                            echo '<td class="border-0 fw-bold">'.$data[$explodes[0]].'</td>';
                            }
                    }
                }
            
                if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('featured_img',$current_taxonomies['supports'])){
                echo '<td class="border-0 fw-bold "><button class="btn btn-tranparent text-capitalize">'.$data['status'].'</button></td>';
                }

                echo '<td>';
                // view
                if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('view',$current_taxonomies['supports'])){
                 
                echo '<a class="btn btn-secondary" href="'.site_url('',$data['permalink']).'" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
              </svg></a> | ';
                }

            //   end view
               
              echo '<a class="btn btn-secondary" href="'.site_url('admin','view.php?post_type='.$get_post_type.'&action=edit&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
              </svg></a> | ';

              if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('clone',$current_taxonomies['supports'])){
                echo '<a class="btn btn-danger" href="'.site_url('admin','view.php?post_type='.$get_post_type.'&action=clone&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-back" viewBox="0 0 16 16">
                <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z"/>
              </svg></a> | ';
              }

                echo '<a class="btn btn-danger" href="'.site_url('admin','view.php?post_type='.$get_post_type.'&action=del&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
              </svg></a>';
                echo '</tr>';

                echo '</tr>';
               
            }
            
        }

            echo '</tbody></table>';

            // pagination 
            echo ' <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mb-0">';
        
            $paginationdata = $db->paginationlast();  
            foreach($paginationdata as $paginationcount){
                foreach($paginationcount as $data=>$val){ 
                        if(isset($_GET['page'])){$page = $_GET['page'];}else{ $page = 1;}
                        if($data == $page){$pagistatus = 'active';}else{$pagistatus = '';} 
                         echo    '<li class="page-item '.$pagistatus.'"><a class="page-link" href="'. $val .'">'.$data.'</a></li>';
               }}
                              echo  ' </ul></nav>';
                               $totalcount = $db->paginationlast('total'); 
           echo ' <div class="fw-normal small mt-4 mt-lg-0">Showing <b>'. $totalcount[0].'</b> out of <b>'. $totalcount[1].'</b> entries</div>';
        
        
       echo  '</div> </div>  ';




       



        
    }

    }

    function __destructor(){

    }
}

$new = new view();







$new->action();  $new->view(); ?>

<script>
    $("#checkAll").click(function () {
    $("input.check").prop('checked', $(this).prop('checked'));
});


$('#bulkActionBtn').on('click', function(){
    if($('input.check:checked').length  != 0){
        if($('#bulkActionform select[name=bulk_action_type]').val() != ''){
        checkeddataid = '';
        for(x=0; x < $('input.check:checked').length; x++){
            checkeddataid += $('input.check:checked')[x].value+',';
        }
      $('#bulkActionform input:hidden[name=bulk_action_ids]').val(checkeddataid);
      $('#bulkActionform').submit()
        }  
}else{
        notyf = new Notyf({ position: { x: 'right', y: 'top', }, types: [{ type: 'error', 
            background: 'red', icon: { className: 'fas fa-comment-dots',  tagName: 'span', color: '#fff' }, dismissible: false }]
        });
        notyf.open({ type: 'error', message: 'Please Select <?php echo $_GET['post_type']; ?>' })
    }
    
})
</script>




<?php footer();?>