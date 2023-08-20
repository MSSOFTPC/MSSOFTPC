
<?php

include('include.php');
loginonly('');

if(!isset($_GET['taxonomy']) || empty($_GET['taxonomy']) || !isset($_GET['post_type']) || empty($_GET['post_type'])){ die("You Can't Access This Area"); }

// check taxonomies
global $registered_taxonomies, $current_taxonomies,$current_tags,$db,$current_taxonomy,$db_taxonomy,$taxonomies_dir;
$get_post_type = $_GET['post_type'];
$get_taxonomy = $_GET['taxonomy'];

$registered_taxonomies_status = 0;
foreach($registered_taxonomies as $data){ 
    if($data['post_type'] == $get_post_type){ $registered_taxonomies_status = 1; $current_taxonomies = $data; }}
    
    // back if taxonomy not matched
    if($registered_taxonomies_status == 0){back('404');}


    $current_taxonomy = '';
    if(isset($current_taxonomies['taxonomy'])){
            $all_tags = $current_taxonomies['taxonomy'];
            foreach($all_tags as $taxonomydata){
                if($get_taxonomy === $taxonomydata['permalink']){
                    $current_taxonomy = $taxonomydata;
                }
                
            }
            
            // taxonomy not match
            if(empty($current_taxonomy)){
                back('404');
            }


    }else{
        back('404');
    }


  
      
        // create directory if not exist
        if(!file_exists(UPLOADDIR.'Taxonomies')){
            mkdir(UPLOADDIR.'Taxonomies');
        }

        $taxonomies_dir = 'Taxonomies/';


    // $current_tags = array_merge($current_tags,array(array('title'=>'ahmad')));

// print_r($current_tags);



    // end taxonomy


// $get = get_post(array('post_type'=>$_GET['post_type']));
$meta_array = [];


function post_type_function_exec($post_type,$function){
    if($_GET['post_type'] == $post_type){
        call_user_func($function);
    }
}


// add_action('admin_action_tab');
// add_action('admin_action_field');
// add_action('admin_action_sidebar_field');

// for handle foreach error
admin_view_fields('', '','');


class Tags_manager {
   private $head_title;
    private $get_post_type;

    function __construct(){
        // conditions
        // if(!isset($_GET['post_type']) || empty($_GET['post_type'])){ die("You Can't Access This Area");}
        $this->get_post_type = $_GET['post_type'];
        $this->taxonomy = $_GET['taxonomy'];



        if(isset($_GET['action']) && empty($_GET['action'])){ IQ_die("You Can't Access This Area");}
        if(isset($_GET['action']) && $_GET['action'] == 'edit' && !isset($_GET['id']) || isset($_GET['id']) && empty($_GET['id'])){ IQ_die("You Can't Access This Area");}
        if(isset($_GET['action']) && $_GET['action'] == 'del' && !isset($_GET['id']) || isset($_GET['id']) && empty($_GET['id'])){ IQ_die("You Can't Access This Area");}
        // end conditions
        
        head($this->head_title);
        get_sidebar();
        global $registered_taxonomies, $current_taxonomies,$current_taxonomy; $registered_taxonomies_status = 0; foreach($registered_taxonomies as $data){ if($data['post_type'] == $this->get_post_type){$registered_taxonomies_status = 1; $current_taxonomies = $data; }}
        if($registered_taxonomies_status == 0){back('404');}
        echo '<main class="content">';
        get_header();
     
    }
     function action(){
        global $conn,$db,$meta_array, $registered_taxonomies, $current_taxonomies,$current_taxonomy,$taxonomies_dir;
        $get_post_type = $this->get_post_type;
        if(isset($_GET['action']) && !empty($_GET['action'])){
            $current_taxonomies['supports'] = explode(',', $current_taxonomies['supports']);
           
            IQ_add_admin_notice('post');
            // end extra

            // delete btn
            if($_GET['action'] == 'del'){ deletedata('terms', 'id='.$_GET['id']); deletedata('terms_meta', 'term_id='.$_GET['id']); add_action('post_delete_action'); $_SESSION['success_taxonomy_notice'] = $current_taxonomy['title'].' Deleted Successfully'; back('tags.php?taxonomy='.$current_taxonomy['permalink'].'&post_type='.$get_post_type.''); }
            // end delete action
         

            if($_GET['action'] == 'edit'){
              

            //  action insert data to mysql
                if(isset($_POST['post_submit'])){
                    global $result,$arry;
                    $IQ_post = $_POST;
                    $arry = [];
                    $arry += ['title' => $_POST['title']];
                    unset($IQ_post['title']);
                    unset($IQ_post['post_type']);
                    

                    // conditions
                    if(!isset($_POST['title']) && empty($_POST['title'])){ back(''); $_SESSION['error'] = 'Title Not Found'; }
                    // end conditions

                    if(isset($_POST['id'])){ $id = $_POST['id']; unset($IQ_post['id']); }
                    $tax_arry = [];
                    if(isset($_POST['content'])){ $content = $_POST['content']; unset($IQ_post['content']);}
                    if(isset($_POST['parent'])){ $parent = $_POST['parent']; unset($IQ_post['parent']);}

                    if(isset($_FILES['featured_img'])){ 
                        if(!empty($_FILES['featured_img']['name'])){
                        IQ_media_insert(array('media'=>'featured_img','dir'=>$taxonomies_dir.$this->taxonomy));
                        $arry += ['featured_img' => $_FILES['featured_img']['name']];
                    }
                    }



                    if($_POST['post_submit'] == 'update'){

                    //     permalink area
                    if(isset($_POST['permalink'])){ 

                        if(empty($_POST['permalink'])){ $permalink = $_POST['title']; }else{$permalink = $_POST['permalink']; }
                        $permalink = str_replace(' ', '-', $permalink);
                        $permalink = strtolower($permalink);
                        $fetch = $db->fetch_all('taxonomy', 'taxonomy="'.$this->taxonomy.'"');

                        if(!empty($fetch)){
                        $i = 0; $dot = ''; $sqldata = '';
                        foreach($fetch as $ftch){
                            if($ftch['term_id'] != $id){
                            $i++;
                                if($i % 2 == 0){ $dot = ' or '; }
                                $sqldata .=  $dot.'id'.'="'.$ftch['term_id'].'" '; 
                            }

                        }

                        $fetch = $db->fetch_all('terms','('.$sqldata.') and permalink="'.$permalink.'"');


                       
                        if(!empty($fetch)){
                            IQ_notice('error_taxonomy_notice','Permalink Already Exist');
                            back('reload');
                            die();

                        }else{
                            $_POST['permalink'] = $permalink;
                        }

                        $arry += ['permalink' => $_POST['permalink']]; 
                        unset($IQ_post['permalink']);

                    }else{
                        $arry += ['permalink' => $permalink]; 
                        unset($IQ_post['permalink']);
                    }

                        

                     }
                    
                    //  permalink area end

                    add_action('before_term_submit');
                    updatedata($arry, 'id='.$id,'terms');

                    // taxonomy
                    $tax_arry += ['content'=>$content];
                    $tax_arry += ['parent'=> $parent];
                    updatedata($tax_arry, 'term_id='.$id,'taxonomy');
                    
                    $result=$IQ_post;
                    add_action('before_term_meta_update_action');
                    foreach($result as $post_meta=>$val){
                        if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }

                        $meta_data = [];
                        $meta_data += ['meta_value' => $val];
                        $fetch = fetch('terms_meta','term_id='.$id.' and meta_key="'.$post_meta.'"');
                        if(empty($fetch)){
                                $meta_data += ['term_id'=>$id];
                                $meta_data += ['meta_key'=>$post_meta];
                            insertdata($meta_data, 'terms_meta'); 
                        }else{
                            updatedata($meta_data, 'term_id='.$id.' and meta_key="'.$post_meta.'"', 'terms_meta'); 
                        }
                    }

                    // files validation
                    $files_meta = [];
                    foreach($_FILES as $files=>$fileval){
                        if($files != 'featured_img' && !empty($_FILES[$files]['name'])){
                            IQ_media_insert(array('media'=>$files,'dir'=>$taxonomies_dir.$this->taxonomy));
                            $files_meta += ['meta_value' => $_FILES[$files]['name']];
                            $fetch = fetch('terms_meta','term_id='.$id.' and meta_key="'.$files.'"');
                        if(empty($fetch)){
                                $files_meta += ['term_id'=>$id];
                                $files_meta += ['meta_key'=>$files];
                            insertdata($files_meta, 'terms_meta'); 
                        }else{
                            updatedata($files_meta, 'term_id='.$id.' and meta_key="'.$post_meta.'"', 'terms_meta'); 
                        }
                        }
                    }
                    add_action('term_update_action');
                    $_SESSION['success_taxonomy_notice'] = $_POST['title'].' Updated';
                    back('reload');
                    }



                }
                // action insert data to mysql





        // Action new area
        $rows = fetch('site_options','');

           
        global $fetchrows,$tax_fetchrows;
        if($_GET['action'] == 'edit'){
            $actionlink = '&action=edit&id='.$_GET['id'];
            $fetchrows = fetch('terms', 'id='.$_GET['id']);
            $tax_fetchrows = fetch('taxonomy', 'term_id='.$_GET['id']);
            
         

            // fetch meta data
            $fetch_post_meta = $db->fetch_all('terms_meta', 'term_id='.$_GET['id']);
             $i = 0;
            foreach($fetch_post_meta as $data){
                foreach($data as $datakey=>$dataval){
                    if($datakey != 'id' && $datakey != 'term_id' && $datakey != 'meta_value'){
                    $meta_array += [$dataval=>$fetch_post_meta[$i]['meta_value']];
                }   }  $i++;  }

                global $meta_array;



            // end meta data
            if(empty($fetchrows)){ IQ_notice('error_taxonomy_notice', $current_taxonomy['title'].' Something went wrong.'); back('tags.php?taxonomy='.$current_taxonomy['permalink'].'&post_type='.$get_post_type); die();  }
            if(!isset($_GET['id']) || empty($_GET['id'])){ back(); }
            $titlename = 'Edit '.$current_taxonomy['title'].' - '.$fetchrows['title']; 
            $submit = 'update'; 
            $submitbtn = 'Update';
            $id = $fetchrows['id'];

        }

        // breadcrumb text change
        function tags_admin_dashboard_breadcrumb_btn_txt(){
            return 'Back';
        }

        add_filter('admin_dashboard_breadcrumb_btn_txt','tags_admin_dashboard_breadcrumb_btn_txt');

        if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('new',$current_taxonomies['supports'])){
        dashbreadcrumb('tags.php?taxonomy='.$current_taxonomy['permalink'].'&post_type='.$get_post_type.''); 
        }

        IQ_add_admin_notice('taxonomy');

        echo    '<form action="'.site_url('currentURL').'" method="post" enctype="multipart/form-data">';
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
         add_action('admin_action_term_fields');

        echo '</div>';
        echo '</div>';
            
       
        // extra tabs
        add_action('admin_action_term_tab');
       

       
                
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
                         
                            // body

                     echo '</div>

                     <!--  footer -->
                    <div class="card-footer">';
                         
                   
                    //  delete button
                    if(isset($id) && !empty(($id))){
                    echo '<a class="btn btn-danger" href="'.site_url('admin','tags.php?taxonomy='.$current_taxonomy['permalink'].'&post_type='.$get_post_type.'&action=del&id=').$id.'">Delete</a>'; 
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
                    get_field(array('title'=>'Featured Img', 'name'=>'featured_img', 'img_url'=>assets($taxonomies_dir.$this->taxonomy.'/'.$featured_img, 'upload'), 'type'=>'file'));        
                 

                    echo '</div>
             
            
                    <!-- end body -->
            
            
            
                </div>';
            }    
               //  end featured img area

               // parent            
         
            echo '<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_publish_area">
            <!-- header -->
            <div class="card-header d-flex align-items-center">
                <h2 class="fs-5 fw-bold mb-0">Parent</h2>
            
            </div>
        
            <!-- body -->
                <div class="card-body">';
                 // parent
                 $fetch = $db->fetch_all('taxonomy', 'taxonomy="'.$this->taxonomy.'"');

                 $parent[] = ['0','Parent'];
                 if(!empty($fetch)){
                     $i = 0; $dot = ''; $sqldata = '';
                     
                     foreach($fetch as $ftch){
                        if($ftch['term_id'] != $id){
                         $i++;
                             if($i % 2 == 0){ $dot = ' or '; }
                             $sqldata .=  $dot.'id'.'="'.$ftch['term_id'].'" '; 
                         }
                        }
                        
                        $fetch = [];
                        if(!empty($sqldata)){
                            $fetch = $db->fetch_all('terms', $sqldata);
                        }
                     

                     
                     
                     foreach($fetch as $fetchterms){
                         $parent[] = [$fetchterms['id'],$fetchterms['title']];
                     }

                 }
                    
              
                    get_field(array('title'=>'Parent', 'options'=>$parent,'name'=>'parent', 'isset'=> $tax_fetchrows, 'class'=>'','type'=>'select'));
               
             
                echo '</div>
         
        
                <!-- end body -->
        
        
        
            </div>';
        
           //  end parent area

               add_action('admin_action_sidebar_term_tab');


                echo '</div>';


            echo '</div> </div> ';

            
            //    end      
            echo '</form>';           

                }}


    }
    public function view(){
        if(!isset($_GET['action'])){
        global $db,$current_taxonomies,$current_taxonomy,$conn,$db_taxonomy,$taxonomies_dir;
       
        $current_taxonomies['supports'] = explode(',', $current_taxonomies['supports']);

                // Title
                echo '<div class="d-flex flex-wrap flex-md-nowrap align-items-center py-4" style="justify-content: space-between">
   
                <div class="d-flex"> <h3>'.$current_taxonomy['title'].'</h3> </div>

                <div class="breadcrumb-search" style="">
                 <!-- Search form -->
                <form class="navbar-search form-inline" id="navbar-search-main" method="get">
                <div class="input-group input-group-merge search-bar">
                    <span class="input-group-text" id="topbar-addon">
                        <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="hidden" class="form-control" name="post_type" id="topbarInputIconLeft" value="doctors" placeholder="Search" aria-label="Search" aria-describedby="topbar-addon">
                    <input type="search" class="form-control" name="search" id="topbarInputIconLeft" value="" placeholder="Search" aria-label="Search" aria-describedby="topbar-addon">
                    <input type="submit" class="form-control" style="display: none" value="submit">
                    </div>
                </form>
                <!-- / Search form -->
                       </div> </div>';

                    // sql data 
                        if(isset($_POST['IQ_tags_submit'])){

                                // conditions
                                if(!isset($_POST['title']) || empty($_POST['title'])){ back('reload'); $_SESSION['error'] = 'Title is Required'; }
                                
                                // end conditions

                                global $result,$arry;
                                $IQ_post = $_POST;
                                $arry = [];
                                $arry += ['title' => $_POST['title']];
                                unset($IQ_post['title']);
                                $content = $IQ_post['content'];
                                unset($IQ_post['content']);
                                $parent = $IQ_post['parent'];
                                unset($IQ_post['parent']);   
            
                                if(isset($_FILES['featured_img'])){ 
                                    if(!empty($_FILES['featured_img']['name'])){
                                    IQ_media_insert(array('media'=>'featured_img','dir'=>$taxonomies_dir.$this->taxonomy));
                                    $arry += ['featured_img' => $_FILES['featured_img']['name']];
                                }
                                }
            
            
                                if($_POST['IQ_tags_submit'] === 'publish'){
                                    unset($IQ_post['IQ_tags_submit']);
            
                                      // permalink area
                                        if(isset($_POST['permalink'])){ 

                                            if(empty($_POST['permalink'])){ $permalink = $_POST['title']; }else{$permalink = $_POST['permalink']; }
                                            $permalink = str_replace(' ', '-', $permalink);
                                            $permalink = strtolower($permalink);
                                            $fetch = $db->fetch_all('taxonomy', 'taxonomy="'.$this->taxonomy.'"');

                                            if(!empty($fetch)){
                                            $i = 0; $dot = ''; $sqldata = '';
                                            foreach($fetch as $ftch){
                                                $i++;
                                                    if($i % 2 == 0){ $dot = ' or '; }
                                                    $sqldata .=  $dot.'id'.'="'.$ftch['term_id'].'" '; 
                                                }

                                            $fetch = $db->fetch_all('terms','('.$sqldata.') and permalink="'.$permalink.'"');
                                           
                                            if(!empty($fetch)){
                                                IQ_notice('error_taxonomy_notice','Term Already Exist');
                                                back('reload');
                                                die();

                                            }else{
                                                $_POST['permalink'] = $permalink;
                                            }

                                            $arry += ['permalink' => $_POST['permalink']]; 
                                            unset($IQ_post['permalink']);

                                        }else{
                                            $arry += ['permalink' => $permalink]; 
                                            unset($IQ_post['permalink']);
                                        }

                                            
            
                                         }

                                        
                                    //  permalink area end
                                    
            
                                    add_action('before_term_submit');
                                    insertdata($arry, 'terms');  
                                    $insert_id = $conn->insert_id;
            
                                    
                                //  insert data to post_meta
                                $result=$IQ_post;
                                add_action('before_term_meta_insert_action');
                                
                                foreach($result as $post_meta=>$val){
                                     if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }
            
                                    $meta_data = [];
                                        $meta_data += ['term_id' => $insert_id];
                                        $meta_data += ['meta_key' => $post_meta];
                                        $meta_data += ['meta_value' => $val];
                                        insertdata($meta_data, 'terms_meta'); 
                                }
                                // files validation
                                $files_meta = [];
                                foreach($_FILES as $files=>$fileval){
                                    if($files != 'featured_img' && !empty($_FILES[$files]['name'])){
                                        IQ_media_insert(array('media'=>$files,'dir'=>$taxonomies_dir.$this->taxonomy));
                                        $files_meta += ['term_id' => $insert_id];
                                        $files_meta += ['meta_key' => $files];
                                        $files_meta += ['meta_value' => $_FILES[$files]['name']];
                                        insertdata($files_meta, 'terms_meta'); 
                                    }
                                }

                            }

                            // insert to taxonomy
                            $taxonomydata = ['taxonomy' => $this->taxonomy];
                            $taxonomydata += ['term_id' => $insert_id];
                            $taxonomydata += ['content' => $content];
                            $taxonomydata += ['parent' => $parent];
                            
                            
                            insertdata($taxonomydata, 'taxonomy'); 
                            


                            IQ_notice('success_taxonomy_notice', $current_taxonomy['title'].' Added');
                            // back('reload');


                        }


                    // end sql data 


                
                IQ_add_admin_notice('taxonomy');
                // end extra

                $get_post_type = $_GET['post_type'];


                // notice area
                   

    // primary row
    echo '<div class="row">';

                // 1st colomn
                echo '<div class="col-md-5">';

                    echo '<form method="post" action="" enctype="multipart/form-data" class="card p-3">';
                        get_field(array('title'=>'Title','name'=>'title','s'=>'true','class'=>'mt-3'));
                        get_field(array('title'=>'Permalink','name'=>'permalink','class'=>'mt-3'));

                        // parent
                        $fetch = $db->fetch_all('taxonomy', 'taxonomy="'.$this->taxonomy.'"');
                            
                        $tags_parent_options[] = ['0','Parent'];
                        if(!empty($fetch)){
                            $i = 0; $dot = ''; $sqldata = '';
                            foreach($fetch as $ftch){
                                $i++;
                                    if($i % 2 == 0){ $dot = ' or '; }
                                    $sqldata .=  $dot.'id'.'="'.$ftch['term_id'].'" '; 
                                }
                            

                            $fetch = $db->fetch_all('terms', $sqldata);
                            
                            foreach($fetch as $fetchterms){
                                $tags_parent_options[] = [$fetchterms['id'],$fetchterms['title']];
                            }

                        }


                        
                        get_field(array('title'=>'Parent','name'=>'parent','class'=>'mt-3','type'=>'select','options'=>$tags_parent_options));

                        get_field(array('title'=>'Featured Image','name'=>'featured_img','type'=>'file','class'=>'mt-3'));
                        get_field(array('title'=>'Description','name'=>'content','type'=>'textarea','extra_input_data'=>'rows="5"','class'=>'mt-3'));
                        add_action('admin_action_term_new_fields');
                        get_field(array('title'=>'Add New','label'=>false,'name'=>'IQ_tags_submit','value'=>'publish','type'=>'submit','field_class'=>'btn btn-primary mt-3'));

                    echo '</form>';

                echo '</div>';
  
                // 2nd colomn
                echo '<div class="col-md-7 ms-auto">';

                    // fetch
                    $fetch = $db->fetch_all('taxonomy', 'taxonomy="'.$this->taxonomy.'"');

                    if(!empty($fetch)){
                        $i = 0; $dot = ''; $sqldata = '';
                        foreach($fetch as $ftch){
                            $i++;
                                if($i % 2 == 0){ $dot = ' or '; }
                                $sqldata .=  $dot.'id'.'="'.$ftch['term_id'].'" '; 
                            }
                        
                            
                        $rows = $db->fetch_all('terms', $sqldata,'pagination','','search');
                        
                    }else{
                        $rows = [];
                    }
        

        echo '<div class="card card-body shadow border-0 table-wrapper table-responsive">';

// action button
        echo ' <div class="d-flex mb-3">
       
        <form action="" method="post" id="bulkActionform">
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
        echo '<th class="border-0 rounded-end">Permalink</th>';

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

       

        echo '<th class="border-0 rounded-end">Action</th>';

        echo '</tr></head><body>';
        if(count($rows) == 0){echo '<td class="border-0 fw-bold text-center" colspan="6"><h1>Data Not Found</h1></td>';}else{
            foreach($rows as $data){ 
                echo '<tr>';
                echo '<td class="border-0 fw-bold"><input type="checkbox" class="form-check-input check" value="'.$data['id'].'"></td>';
                echo '<td class="border-0 fw-bold">'.$data['title'].'</td>';
                echo '<td class="border-0 fw-bold">'.$data['permalink'].'</td>';

                if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('featured_img',$current_taxonomies['supports'])){
                echo '<td class="border-0 fw-bold"><img src="'.assets($taxonomies_dir.$this->taxonomy.'/'.$data['featured_img'], 'upload').'" class="img-thumbnail" width="100px"></td>';
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
            
             

                echo '<td>';
                // view
                if(empty($current_taxonomies['supports'][0]) || !empty($current_taxonomies['supports']) && in_array('view',$current_taxonomies['supports'])){
                 
                echo '<a class="btn btn-secondary" href="'.site_url('',$data['permalink']).'" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
              </svg></a> | ';
                }

            //   end view
               
              echo '<a class="btn btn-secondary" href="'.site_url('admin','tags.php?post_type='.$get_post_type.'&taxonomy='.$this->taxonomy.'&action=edit&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
              </svg></a> | ';


                echo '<a class="btn btn-danger" href="'.site_url('admin','tags.php?taxonomy='.$this->taxonomy.'&post_type='.$get_post_type.'&action=del&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
              </svg></a>';
                echo '</tr>';

                echo '</tr>';
               
            }
            
        }

            echo '</tbody></table>';

            if(!empty($rows)){
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
        }
        
       echo  '</div> </div>  </div> </div>';




       



        
    }

    }

    function __destructor(){

    }
}

$new = new Tags_manager();







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