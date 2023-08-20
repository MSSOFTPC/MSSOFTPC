<?php

// active
function theme_activator($val){
    $data = ['option_value' => $val];
    updatedata($data, 'option_name="active_theme"', 'site_options');
    $_SESSION['success_theme_notice'] = $val.' Theme Activated';
    return 1;
}


// delete
function theme_delete($val){
    if(file_exists(THEMEDIR.$val)){
        if(removeDir(THEMEDIR.$val) === 1){
            $_SESSION['success_theme_notice'] = $val.' Theme Deleted Successfully';
            return 1;
        }
    }else{ $_SESSION['success_addons_notice'] = 'not exist'; return 0;}
}


// stylesheet extractor
function style_extractor($style_dir){
    $styledata = file_get_contents($style_dir);
    $styledata = substr($styledata, 0, strpos($styledata, "*/"));

    if(!empty($styledata)){
    $styledata = str_replace('/*','', $styledata);
    $styledata = explode("\n", $styledata);
    $theme_data = []; 

    foreach($styledata as $data){
        $data = explode(':', $data,2);
        $data[0] = strtolower(str_replace(' ', '', $data[0]));
        $theme_data += [$data[0] => $data[1]];
    }
    return $theme_data;
}
}

// required parametors
// themename, version, themeuri, 


