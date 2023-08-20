<?php

include('../function.php');
include('../core/addons-controller.php');
include('../core/themes-controller.php');



if(isset($_POST['isLogin'])){
    if(!empty($_SESSION['login'])){
    echo 1;
    }
}



if(isset($_POST['orderFetch'])){
    $fetch = fetch('orders', 'id='.$_POST['order_id']);
    echo json_encode($fetch);
}

if(isset($_POST['itemFetch'])){
    $fetch = $db->fetch_all('order_items', 'order_id='.$_POST['order_id']);
    echo json_encode($fetch);
}

// theme_installation 
if(isset($_POST['theme_controller'])){
    foreach($_POST as $data=>$val){
        if($data == 'theme_name'){
        echo theme_activator($val);
        }

        if($data == 'theme_delete'){
            if(IQ_filepermission(THEMEDIR.$val) === '0777'){
            echo theme_delete($val);
            }else{
                $_SESSION['error_theme_notice'] = $val.' Permission Denied';
                return 0;
            }
        }

    }

}

// Addons_installation 
if(isset($_POST['addons_controller'])){
    foreach($_POST as $data=>$val){
        $value = $val;
        $val = strtolower(str_replace(' ', '', $val));

        if($data == 'addons_name'){
           echo addons_activator($value);
        }

        if($data == 'addons_delete'){
            echo addons_delete($value);
        }

        // deactive
            if($data == 'addons_deactive'){
                echo addons_deactivator($value);
            }

    }

}

// media controller
if(isset($_POST['IQ_media_controller']) && $_POST['IQ_media_controller'] === 'get'){
    $media_controller = get_media(array('id'=>$_POST['id']));
    echo json_encode($media_controller);
    die();
}


?>