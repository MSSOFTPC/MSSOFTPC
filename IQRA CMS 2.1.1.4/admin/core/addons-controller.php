<?php

// activator
function addons_activator($val){
    $value = $val;
    $val = strtolower(str_replace(' ', '', $val));

        
        $arry = site_options('return_active_addons');
        if(!empty($arry)){
        $arry = html_entity_decode($arry);
        $arry = unserialize($arry);
        $arry = array_merge([$value],$arry);
    }else{
        $arry = [$value];
    }
        
        $arry = htmlentities( serialize($arry) );
        $data = ['option_value' => $arry];
        updatedata($data, 'option_name="active_addons"', 'site_options');
        if(file_exists(ADDONSDIR.$value.'/activate.php')){ include(ADDONSDIR.$value.'/activate.php');  }
        $_SESSION['success'] = $value.' Addon Activated';
        $_SESSION['success_addons_notice'] = $value.' Addon Activated';
        return 1;
  
}

// example
// addons_activator('IQ Contact btn');

// Deactivator
 function addons_deactivator($val){
    $value = $val;
    $val = strtolower(str_replace(' ', '', $val));

    if(is_addons_active($value) == 1){
        $arry = current_active_addons();
        foreach($arry as $count=>$new){
            if($value == $new){
            if(file_exists(ADDONSDIR.$arry[$count].'/deactivate.php')){ include(ADDONSDIR.$arry[$count].'/deactivate.php'); }
                unset($arry[$count]);
            }
        }
        $arry = htmlentities( serialize($arry) );
$data = ['option_value' => $arry];
updatedata($data, 'option_name="active_addons"', 'site_options');
$_SESSION['success_addons_notice'] = $value.' Addons Dectivated';
return 1;
    }else{
        $_SESSION['error_addons_notice'] = $value.' Addon Not Found';
        return 0;
    }
}


// addons_deactivator('IQ Contact btn');

// Delete
function addons_delete($val){
    $value = $val;
    $val = strtolower(str_replace(' ', '', $val));

if(file_exists(ADDONSDIR.$value)){                
    if(file_exists(ADDONSDIR.$value.'/uninstall.php')){ include(ADDONSDIR.$value.'/uninstall.php'); }

    if(IQ_filepermission(ADDONSDIR.$value) === '0777'){
    if(removeDir(ADDONSDIR.$value) == 1){
        $_SESSION['success_addons_notice'] = $value.' Addon Deleted Successfully';
        return 1;
    }
}else{
    $_SESSION['error_addons_notice'] = $value.' Permission Denied';
    return 0;
}


}else{ $_SESSION['error_addons_notice'] = $value.' Addon not Found'; return 0;}
}

// addons_delete('IQ Contact btn');

