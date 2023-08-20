<?php

function add_action($action_name, $function_name=null,$variable = null){
    global $action_names, $variable_name;
    if(!isset($action_names)){$action_names = []; $variable_name = [];}

    if(isset($variable)){
            $variable_name[$action_name][$function_name] = $variable;
    }

    if(isset($action_name) && isset($function_name)){
        if(isset($action_names[$action_name])){  
        array_push($action_names[$action_name], $function_name);


        }else{
        $action_names[$action_name] =  [$function_name];
        }
}else{
    if(isset($action_names[$action_name])){
    foreach($action_names[$action_name] as $data){
        if(isset($variable_name[$action_name][$data])){
            call_user_func($data,$variable_name[$action_name][$data]);
        }else{
            call_user_func($data);
        }
    }}
}
}


// add or apply filters

function add_filter($name, $data,$variable = null){
    global $apply_filters, $apply_filter_variable_name;
    if(!isset($apply_filters)){$apply_filters = []; $apply_filter_variable_name = [];}

    if(isset($variable)){
        $apply_filter_variable_name[$name][$data] = $variable;
    }

    if(isset($apply_filters[$name])){  
        array_push($apply_filters[$name], $data);

        }else{
        $apply_filters[$name] =  [$data];
        }

        // print_r($apply_filters);
}

function apply_filter($name, $data,$variable = null){
    global $apply_filters, $apply_filter_variable_name;

    if(isset($apply_filters[$name])){
    foreach($apply_filters[$name] as $data){
        if(isset($apply_filter_variable_name[$name][$data])){
           return call_user_func($data,$apply_filter_variable_name[$name][$data]);
        }else{
           return call_user_func($data);
        }
    }
    }else{
        return $data;
    }
  
}

// function functionname($hello){
//     return 'Yes Working'.$hello; 
// }

// add_filter('Wahid','functionname','var');

// echo apply_filter('Wahid','IQRA');




// for add fields on view page admin

function admin_view_fields($title,$name,$post_type=null){
    if(!isset($post_type)){$post_type = '';}
    global $add_view_fields;
    if(empty($add_view_fields)){$add_view_fields = [];}
    array_push($add_view_fields, [$title=>$name.'|'.$post_type]);
}

function get_admin_view_fields(){
    global $add_view_fields;
    return $add_view_fields;
}

// users view fields
function admin_user_view_fields($title,$name,$post_type=null){
    global $add_user_view_fields;
    if(empty($add_user_view_fields)){$add_user_view_fields = [];}
    array_push($add_user_view_fields, [$title=>$name.'|'.$post_type]);
}

function get_admin_user_view_fields(){
    global $add_user_view_fields;
    return $add_user_view_fields;
}