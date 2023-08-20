<?php

function IQ_user_delete($id){
    // delete post
    deletedata('user', 'id='.$id); 
    deletedata('user_meta', 'user_id='.$id); 
    return 1;
}


function IQ_user_insert($array){   
    global $conn,$db,$insert_id;
    $arry = [];

if(isset($array['password']) && !empty($array['password'])){ $arry += ['password' => md5($array['password'])]; }
if(isset($array['full_name']) && !empty($array['full_name'])){ $arry += ['full_name' => $array['full_name']]; }
if(isset($array['phone'])){ 
    $phone = get_users(array('phone'=>$array['phone']));
    if(empty($phone)){ $arry += ['phone' => $array['phone']];}else{ return 'Phone Number Already Exist'; }}
if(isset($array['username'])){ 
    $phone = get_users(array('username'=>$array['username']));
    if(empty($phone)){ $arry += ['username' => $array['username']];}else{ return 'Username Already Exist'; }}
    
if(isset($array['email'])){ 
    $phone = get_users(array('email'=>$array['email']));
    if(empty($phone)){ $arry += ['email' => $array['email']];}else{ return 'Email Already Exist'; }
    $arry += ['email_token'=> date('Ymd')];
}
if(isset($array['featured_img'])){ $arry += ['featured_img' => $array['featured_img']]; }
if(isset($array['status'])){ $arry += ['status' => $array['status']]; }
if(isset($array['role'])){ if($array['role'] != 'admin'){ $arry += ['role' => $array['role']]; }else{ return "Your Can't set Admin Role from frontend"; }}else{ $arry += ['role' => 'visitor'];}
if(isset($array['banner'])){ $arry += ['banner' => $array['banner']]; }

        

        
        $date = new \DateTime();
        $arry += ['created_at'=> date_format($date, 'Y-m-d H:i:s')];

        insertdata($arry, 'user');  
        if(isset($array['email'])  && !isset($array['email_verification']) || isset($array['email'])  && $array['email_verification'] != false){
            $mail->registation($array['email']);
        }

        $insert_id = $conn->insert_id;
    //  insert data to post_meta
    if(isset($array['user_meta'])){
        $result=$array['user_meta'];
        foreach($result as $post_meta=>$val){
                $meta_data = [];
                $meta_data += ['user_id' => $insert_id];
                $meta_data += ['meta_key' => $post_meta];
                $meta_data += ['meta_value' => $val];
                insertdata($meta_data, 'user_meta'); 
           
        }
    }
    
    add_action('IQ_after_user_registration');
    return $insert_id;
    
}

// update
// $arry = array(
//     'id'=>'', required
//     'title'=>'', optional
//     'post_type'=>'', optional
//     'content'=>'', optional
//     'status'=>'', optional
//     'layout'=>'', optional
//     'featured_img'=>'', optional
//     'post_meta'=>array()  options
// );

function IQ_user_update($array){
    $status = 1;
    $arry = [];
    $id = $array['id'];
    $data = get_users(array('id'=>$id));
    $data = $data[0];
    if(empty($data)){ return 'User Not Found';}
  
if(isset($array['password']) && !empty($array['password'])){ $arry += ['password' => md5($array['password'])]; }
if(isset($array['phone'])){ 
    if($data['phone'] == $array['phone']){ $arry += ['phone' => $array['phone']];}else{
        $phone = get_users(array('phone'=>$array['phone']));
        if(empty($phone)){ $arry += ['phone' => $array['phone']]; }else{ return 'Phone Already Exist'; }
    }
    }
    
if(isset($array['username'])){ 
    if($data['username'] == $array['username']){ $arry += ['username' => $array['username']];}else{
        $username = get_users(array('username'=>$array['username']));
        if(empty($username)){ $arry += ['username' => $array['username']]; }else{ return 'username Already Exist'; }
    }
    }
    
    
if(isset($array['email'])){ 
    if($data['email'] == $array['email']){ $arry += ['email' => $array['email']];}else{
        $email = get_users(array('email'=>$array['email']));
        if(empty($email)){ $arry += ['email' => $array['email']]; }else{ return 'Email Already Exist'; }
    }
    }

if(isset($array['full_name']) && !empty($array['full_name'])){ $arry += ['full_name' => $array['full_name']]; }
if(isset($array['featured_img'])){ $arry += ['featured_img' => $array['featured_img']]; }
if(isset($array['status'])){ $arry += ['status' => $array['status']]; }
if(isset($array['role'])){ if($array['role'] != 'admin'){ $arry += ['role' => $array['role']]; }else{ return "Your Can't set Admin Role from frontend"; }}
if(isset($array['banner'])){ $arry += ['banner' => $array['banner']]; }

if(!empty($arry)){
        updatedata($arry,'id='.$id, 'user');  
        $status = 1;
    }
    //  insert data to post_meta
    
    // post meta area
    if(isset($array['user_meta'])){

    $result=$array['user_meta'];
  
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
    }
    
    return $status;
}
    
// get post
function get_users($data){
    global $db;
    $query = '';
    if(isset($data['email'])){ $query = 'email="'.$data['email'].'"';}
    if(isset($data['username'])){ $query = 'username="'.$data['username'].'"';}
    if(isset($data['phone'])){ $query = 'phone="'.$data['phone'].'"';}
    if(isset($data['query'])){ $query = $data['query'];}
    if(isset($data['all'])){ $query = '';}
    if(isset($data['id'])){ $query = 'id="'.$data['id'].'"';}
    if(isset($data['pagination']) && $data['pagination'] == true){ $pagination = 'pagination';}else{ $pagination = ''; }
    if(isset($data['search']) && $data['search'] == true){ $search = 'search';}else{ $search = ''; }


    // add status
    if(isset($data['status'])){
    if($data['status'] == false){ 
         if(!empty($query)){ $query = ' and status=0'; }else{ $query = 'status=0'; } }else{
            if(!empty($query)){ $query = ' and status=1'; }else{ $query = 'status=1'; }
         }
        }

        //  end status

    $fetch = $db->fetch_all('user',$query,$pagination,'',$search);
    $i = 0;
    foreach($fetch as $post_meta_add){
        $fetch[$i] = [];
        $meta = get_meta('user_meta', 'user_id="'.$post_meta_add['id'].'"');
        $fetch[$i] += array_merge($post_meta_add,$meta);
        $i++;
    }
    return $fetch;
}

// $data['email'] or $data['id']
// $data['pagination']
// $data['search']

// get_post(array())


// for get single user meta

function get_user_meta($where){
    Global $db;
    
    $fetch = $db->fetch_all('user_meta', $where);
    $i = 0;
    foreach($fetch as $post_meta_add){
        $fetch[$i] = [];
        $meta = get_meta('user_meta', 'user_id="'.$post_meta_add['id'].'"');
        $fetch[$i] += array_merge($post_meta_add,$meta);
        $i++;
    }
     return $fetch;
    
}

// get_user_meta(meta_value)



