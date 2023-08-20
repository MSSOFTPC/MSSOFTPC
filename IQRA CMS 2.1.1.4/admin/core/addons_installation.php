<?php

function current_active_addons(){   
    $arry = site_options('return_active_addons');
    $arry = html_entity_decode($arry);
    $arry = unserialize($arry);
    return $arry;
}

function is_addons_active($dta){
    $arry = current_active_addons();
    if(!is_array($arry)){ $arry = []; }
    $status = 0;
    foreach($arry as $data){
        if($data == $dta){
            $status = 1;
        }
    }

    return $status;
}

function is_addons_exist($dta){
    $status = 0;
    $addons_location = ADDONSDIR;
    $addons_scandir = scandir($addons_location);
    $is_data = 0;
    foreach($addons_scandir as $path){
        if(($path != '.') && ($path != '..') && ($path != '__MACOSX')){
        if(is_dir($addons_location.$path)){
            if($path == $dta){
                $status = 1;
            }
        }
    }}

    return $status;
}

function current_addon_dir($dir){
    $dir = explode('/content/addons/',$dir,2);
    $dir = explode('/',$dir[1]);
    $dir = site_url('','content/addons/'.$dir[0]);
    return $dir;
}

function current_addon_include_dir($dir){
    $dir = explode('/content/addons/',$dir,2);
    $dir = explode('/',$dir[1]);
    $dir = ADDONSDIR.$dir[0];
    return $dir;
}


?>