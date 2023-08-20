<?php
include('include.php');
loginonly('');
?>        
<?php 

// for handle foreach error
// get_admin_user_view_fields('', '');






class view {
   private $head_title;

    function __construct(){
        // conditions
        if(isset($_GET['action']) && empty($_GET['action'])){ IQ_die("You Can't Access This Area");}
        if(isset($_GET['action']) && $_GET['action'] == 'edit' && !isset($_GET['id']) || isset($_GET['id']) && empty($_GET['id'])){ IQ_die("You Can't Access This Area");}
        if(isset($_GET['action']) && $_GET['action'] == 'del' && !isset($_GET['id']) || isset($_GET['id']) && empty($_GET['id'])){ IQ_die("You Can't Access This Area");}
        // end conditions
        
        head('All Users');
        get_sidebar();
        echo '<main class="content">';
        get_header();
     
    }
     function action(){
        global $conn,$db;
        if(isset($_GET['action']) && !empty($_GET['action'])){
            // extra
            function helly(){
                add_admin_dashboard_breadcrumb_menu(array(array('name'=>'back','url'=>site_url('admin','users.php'))));
            }
            add_action('admin_dashboard_breadcrumb', 'helly');
            dashbreadcrumb('users.php?action=new'); 
            IQ_add_admin_notice('users');
            // end extra

            // delete btn
            if($_GET['action'] == 'del'){ deletedata('user', 'id='.$_GET['id']); deletedata('user_meta', 'user_id='.$_GET['id']); $_SESSION['success_users_notice'] = 'User Deleted Successfully'; back('users.php'); }
            // end delete action
            

            if($_GET['action'] == 'new' || $_GET['action'] == 'edit'){

            //  action insert data to mysql
                if(isset($_POST['user_submit'])){
                    global $result;
                    $IQ_post = $_POST;
                    $arry = [];
                    $arry += ['full_name' => $_POST['full_name']];
                    unset($IQ_post['full_name']);

                    $dta = $db->fetch_all('user','email="'.$_POST['email'].'"');
                    $username = $db->fetch_all('user','username="'.$_POST['username'].'"');

                    $arry += ['email' => $_POST['email']]; unset($IQ_post['email']);
                    $arry += ['username' => $_POST['username']]; unset($IQ_post['username']);
                    $arry += ['phone' => $_POST['phone']]; unset($IQ_post['phone']);
                    $arry += ['status' => $_POST['status']]; unset($IQ_post['status']);
                    $arry += ['role' => $_POST['role']]; unset($IQ_post['role']);

                  
                    // conditions
                        // password
                        if(!empty($_POST['password'])){
                    $arry += ['password' => md5($_POST['password'])]; unset($IQ_post['password']);
                        }

                    if(!isset($_POST['username']) && empty($_POST['username'])){ back(''); $_SESSION['error_users_notice'] = 'Username Not Found'; }
                    // end conditions

                    if(isset($_POST['id'])){ $id = $_POST['id']; unset($IQ_post['id']);}
                    
                    if(isset($_FILES['featured_img'])){ 
                        if(!empty($_FILES['featured_img']['name'])){
                        IQ_media_insert(array('media'=>'featured_img','dir'=>'users'));
                        $arry += ['featured_img' => $_FILES['featured_img']['name']];
                    }
                    }

                    if(isset($_FILES['banner'])){ 
                        if(!empty($_FILES['banner']['name'])){
                        IQ_media_insert(array('media'=>'banner','dir'=>'users'));
                        $arry += ['banner' => $_FILES['banner']['name']];
                    }
                    }


                    if($_POST['user_submit'] == 'publish'){
                        unset($IQ_post['user_submit']);
                        // validation
                        if(count($dta) != 0){ $_SESSION['error_users_notice'] = 'Email Already Exist'; back('reload'); die();}
                        if(count($username) != 0){ $_SESSION['error_users_notice'] = 'Username Already Exist'; back('reload'); die();}


                        $date = new \DateTime();
                        $arry += ['created_at'=> date_format($date, 'Y-m-d H:i:s')];
                        insertdata($arry, 'user');  
                        $insert_id = $conn->insert_id;
                    //  insert data to post_meta
                    $result= $IQ_post;
                    add_action('before_user_meta_insert_action');
                   
                    foreach($result as $post_meta=>$val){
                        $meta_data = [];
                            $meta_data += ['user_id' => $insert_id];
                            $meta_data += ['meta_key' => $post_meta];
                            $meta_data += ['meta_value' => $val];
                            insertdata($meta_data, 'user_meta'); 
                    }

                    // files validation
                    $files_meta = [];
                    foreach($_FILES as $files=>$fileval){
                        if($files != 'featured_img' && $files != 'banner' && !empty($_FILES[$files]['name'])){
                            $data = [$files => 'users'];
                            filevalidation($data);
                            $files_meta += ['user_id' => $insert_id];
                            $files_meta += ['meta_key' => $files];
                            $files_meta += ['meta_value' => $_FILES[$files]['name']];
                            insertdata($files_meta, 'user_meta'); 
                        }
                    }


                    //  insert data to post_meta


                     back('users.php?&action=edit&id='.$insert_id);
                     $_SESSION['success_users_notice'] = $_POST['full_name'].' Published';
                    }
                  
               
                    if($_POST['user_submit'] == 'update'){
                        unset($IQ_post['user_submit']);
                    // validation
                    if(count($dta) != 0 && $dta[0]['id'] != $id){ $_SESSION['error_users_notice'] = 'Email Already Exist'; back('reload'); die();}
                    if(count($username) != 0 && $username[0]['id'] != $id){ $_SESSION['error_users_notice'] = 'Username Already Exist'; back('reload'); die();}

                    updatedata($arry, 'id='.$id,'user');
                    $result=$IQ_post;
                    add_action('before_user_meta_update_action');
                    foreach($result as $post_meta=>$val){
                            $meta_data = [];
                            $meta_data += ['meta_value' => $val];
                            $fetch = fetch('user_meta','user_id='.$id.' and meta_key="'.$post_meta.'"');
                            if(empty($fetch)){
                                    $meta_data += ['user_id'=>$id];
                                    $meta_data += ['meta_key'=>$post_meta];
                                insertdata($meta_data, 'user_meta'); 
                            }else{
                                updatedata($meta_data, 'user_id='.$id.' and meta_key="'.$post_meta.'"', 'user_meta'); 
                            }

                    }

                    // files validation
                    $files_meta = [];
                    foreach($_FILES as $files=>$fileval){
                        if($files != 'featured_img' && $files != 'banner' && !empty($_FILES[$files]['name'])){
                            $data = [$files => 'users'];
                            filevalidation($data);
                            $files_meta += ['meta_value' => $_FILES[$files]['name']];

                            $fetch = fetch('user_meta','user_id='.$id.' and meta_key="'.$files.'"', 'user_meta');
                            if(empty($fetch)){
                                    $files_meta += ['user_id'=>$id];
                                    $files_meta += ['meta_key'=>$files];
                                insertdata($files_meta, 'user_meta'); 
                            }else{
                                updatedata($files_meta, 'user_id='.$id.' and meta_key="'.$post_meta.'"', 'user_meta'); 
                            }

                        }
                    }

                    $_SESSION['success_users_notice'] = $_POST['full_name'].' Updated';
                    back('users.php?action=edit&id='.$id);
                    }



                }
                // action insert data to mysql





        // Action new area
        $rows = fetch('site_options','');
        $fetchrows = '';
        $fetch_user_meta = '';
        // for new 
        $titlename = 'Add New'; 
        $submit = 'publish'; 
        $submitbtn = 'Publish'; 
        $actionlink = '&action=new';
        global $fetchrows;

        if($_GET['action'] == 'edit'){
            if(!isset($_GET['id']) || empty($_GET['id'])){ $_SESSION['success_users_notice'] = 'Invalid Input'; back(); }
            $actionlink = '&action=edit&id='.$_GET['id'];
            $fetchrows = fetch('user', 'id='.$_GET['id']);
            // fetch meta data
            $fetch_user_meta = $db->fetch_all('user_meta', 'user_id='.$_GET['id']);
            $i = 0;
            global $meta_array;
            $meta_array = [];
            foreach($fetch_user_meta as $data){
                foreach($data as $datakey=>$dataval){
                    if($datakey != 'id' && $datakey != 'user_id' && $datakey != 'meta_value'){
                    $meta_array += [$dataval=>$fetch_user_meta[$i]['meta_value']];
                }   }  $i++;  }

            // this is you cheker



            // end meta data
            if(empty($fetchrows)){ $_SESSION['error_users_notice'] == 'Data Not Found'; back('users.php'); die();  }
            $titlename = 'Edit User '.$fetchrows['full_name']; 
            $submit = 'update'; 
            $submitbtn = 'Update';
            $id = $fetchrows['id'];

        }
            if( isset($id) && $id == loggedinuser('return_id')){ $loggedinuser = '(You)'; $disabled = 'disabled'; }else{ $loggedinuser = ''; $disabled = '';}


       
            echo '<div class="col-12 col-xl-12">
                    <div class="card card-body border-0 shadow mb-4">';
            echo    '<h2 class="h5 mb-4" style="text-capitalize">'.$titlename.' '.$loggedinuser.'</h2>';
      
            echo    '<form action="'.site_url('currentURL').'" method="post" enctype="multipart/form-data">';
            
            if(!isset($id)){ $id = '';}
            echo    '<input type="hidden" name="id" value="'.$id.'">';

            echo     '<div class="row align-items-center">';

            // full name field
            get_field(array('title'=>'Full Name', 'name'=>'full_name','isset'=> $fetchrows, 'placeholder'=>'Enter Your Full Name', 'class'=>'col-md-6 mb-3'));

        //    username
        get_field(array('title'=>'Username', 'name'=>'username', 'isset'=> $fetchrows, 'placeholder'=>'Enter username','required'=>'', 'class'=>'col-md-6 mb-3','type'=>'text'));

         //    email
         get_field(array('title'=>'Email', 'name'=>'email', 'isset'=> $fetchrows, 'placeholder'=>'Enter Your Email','required'=>'', 'class'=>'col-md-6 mb-3','type'=>'email'));

        // Phone
        get_field(array('title'=>'Phone', 'name'=>'phone', 'isset'=> $fetchrows, 'placeholder'=>'Enter Your Phone Number', 'class'=>'col-md-6 mb-3','type'=>'tel'));

        // extra
        add_action('admin_user_fields');

        echo '</div>';
        echo     '<div class="row align-items-center">';
        // Featured Image
        if(isset($fetchrows['featured_img'])){ $featured_img = $fetchrows['featured_img']; }else{ $featured_img = '';}
        get_field(array('title'=>'Featured Img', 'name'=>'featured_img','img_url'=>assets('users/'.$featured_img, 'upload'), 'class'=>'col-md-6 mb-3','type'=>'file'));
              
         // Banner Image
         if(isset($fetchrows['banner'])){ $banner = $fetchrows['banner']; }else{ $banner = '';}
         get_field(array('title'=>'Banner', 'name'=>'banner', 'img_url'=>assets('users/'.$banner, 'upload'), 'class'=>'col-md-6 mb-3','type'=>'file'));
                  
            // end section
            echo ' </div>';
            echo ' <div class="row">';

            // status

            $options = array(
                array(1,'Active', ''),
                array(0,'Deactive', ''),
            );
            get_field(array('title'=>'Status', 'options'=>$options,'name'=>'status', 'isset'=> $fetchrows, 'required'=>'', 'class'=>'col-md-6 mb-3','type'=>'select'));

             // role
             $IQ_user_role = fetch_distictdata('user','role');
             
             if(array_search('admin', array_column($IQ_user_role, 'role')) == ''){ $IQ_user_role[] = ['role'=>'admin']; }
             if(array_search('visitor', array_column($IQ_user_role, 'role')) == ''){ $IQ_user_role[] = ['role'=>'visitor']; }

             foreach($IQ_user_role as $data){
                 $role[] = [$data['role'],$data['role']];
             }

            

            
            get_field(array('title'=>'Role', 'options'=>$role,'name'=>'role', 'isset'=> $fetchrows, 'required'=>'', 'class'=>'col-md-6 mb-3','type'=>'select'));
            get_field(array('title'=>'Password', 'type'=>'password','name'=>'password', 'class'=>'col-md-6 mb-3'));


            // Submit Button Image
            get_field(array('title'=>$submitbtn,'label'=>0, 'name'=>'user_submit', 'class'=>'mt-3','field_class'=>'btn btn-gray-800 mt-2 animate-up-2', 'value'=>$submit, 'type'=>'submit'));


            echo ' </div>';

            echo '</form>';


       //  delete button
       if(isset($id) && !empty(($id))){

        echo '<div class="mt-3">
        <a class="btn btn-danger '.$disabled .' " href="'.site_url('admin','users.php?action=del&id=').$id.'">Delete</a>
         </div>'; 
                 }

            echo ' </div> </div>';

            
                                
                            

                }}


    }
    public function view(){
        if(!isset($_GET['action'])){
                // extra
                dashbreadcrumb('users.php?&action=new','','Users'); 
                IQ_add_admin_notice('users');
                // end extra

        global $db;
        $rows = get_users(array('query'=>'','pagination'=>true,'search'=>true));
      

        echo '<div class="card card-body shadow border-0 table-wrapper table-responsive">';

// action button
        echo ' <div class="d-flex mb-3">
        <form action="function.php" method="post" id="bulkActionform">
        <select class="form-select fmxw-200" aria-label="Message select example" name="bulk_action_type">
        <option selected="" value="">Bulk Action</option>
        <option  value="delete" >Delete</option>
    </select>
    <input type="hidden" name="bulk_action_ids" value="">
    <input type="hidden" name="bulk_action_table" value="users">
    </form>
    <button class="btn btn-sm px-3 btn-secondary ms-3" id="bulkActionBtn">Apply</button>
    </div>';

       echo '
                <table class="table user-table table-hover align-items-center">
                    <thead class="thead-light">';
        echo  '<tr>';
        echo '<th class="border-0 fw-bold rounded-start"><input type="checkbox" class="form-check-input" id="checkAll"></th>';
        echo '<th class="border-0">Name</th>';
        echo '<th class="border-0 fw-bold">Image</th>';
        echo '<th class="border-0 ">Email</th>';

        if(!empty(get_admin_user_view_fields())){
        foreach(get_admin_user_view_fields() as $fiels_data){
            
            foreach($fiels_data as $viewdata=>$val){
                
                echo '<th class="border-0">'.$viewdata.'</th>';
        
            }
        }}
        echo '<th class="border-0 ">Role</th>';
        echo '<th class="border-0 ">Status</th>';
        echo '<th class="border-0 rounded-end">Action</th>';
        echo '</tr></head><body>';
        if(count($rows) == 0){echo '<td class="border-0 fw-bold text-center" colspan="6"><h1>Data Not Found</h1></td>';}else{
            foreach($rows as $data){ 
                if( $data['id'] == loggedinuser('return_id')){ $loggedinuser = '(You)'; $disabled = 'disabled'; }else{ $loggedinuser = ''; $disabled = '';}
                echo '<tr>';
                echo '<td class="border-0 fw-bold"><input type="checkbox" class="form-check-input check" value="'.$data['id'].'"></td>';
                echo '<td class="border-0 fw-bold">'.$data['full_name'].' '.$loggedinuser .'</td>';
                echo '<td class="border-0 fw-bold"><img src="'.assets('users/'.$data['featured_img'], 'upload').'" class="img-thumbnail" width="100px"></td>';
                echo '<td class="border-0 fw-bold">'.$data['email'].'</td>';

                if(!empty(get_admin_user_view_fields())){
                foreach(get_admin_user_view_fields() as $fiels_data){
                    foreach($fiels_data as $viewdata=>$val){
                        $explodes = explode('|', $val);
                            if(!isset($data[$explodes[0]])){ $data[$explodes[0]] = ''; }                     
                            echo '<td class="border-0 fw-bold">'.$data[$explodes[0]].'</td>';
                            }
                    
                }
            }
            
                echo '<td class"border-0 fw-bold">'.$data['role'].'</td>';
                if(isset($data['status']) && $data['status'] == 1){ $status = 'Active';}else{ $status = 'Deactivate';}
                echo '<td class="border-0 fw-bold"><button class="btn btn-tranparent">'.$status.'</button></td>';
                echo '<td>';
                echo '<a class="btn btn-secondary" href="'.site_url('','author/'.$data['username']).'" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
              </svg></a> | ';
                echo '<a class="btn btn-secondary" href="'.site_url('admin','users.php?action=edit&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
              </svg></a> | ';

                echo '<a class="btn btn-danger '.$disabled.'" href="'.site_url('admin','users.php?action=del&id=').$data['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
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
        notyf.open({ type: 'error', message: 'Please Select User' })
    }
    
})
</script>




<?php footer();?>