<?php 

function register_shortcode($action_name, $function_name){
    global $action_names;
    if(!isset($action_names)){$action_names = [];}

    if(isset($action_name) && isset($function_name)){
        if(isset($action_names[$action_name])){  
        array_push($action_names[$action_name], $function_name);


        }else{
        $action_names[$action_name] =  [$function_name];
        }
}
}

// example
// function hllox($val){
//     echo 'samar';
//     print_r($val);
// }

// register_shortcode('shortcode','hllox');

function do_shortcode($shortcode_name){
    global $action_names;
    $content = $shortcode_name;

    if(empty($content)){ return 'Shortcode is Empty'; }

	if ( false === strpos( $content, '[' ) ) { return $shortcode_name; }
	if ( false === strpos( $content, ']' ) ) { return $shortcode_name; }
    $content = explode( '[', $content );
    $content = explode(']', $content[1]);
    

    // add veriables to array
    $content = explode(' ', $content[0],2);
    $variables = $content;
    unset($variables[0]);
    if(!empty($variables[1])){
        $variables = explode('" ', $variables[1]);
        if(count($variables) === 0){ $variables = explode("' ", $variables);  }
    foreach($variables as $variable=>$val){

           $vales = explode('=',$val);
           $values =  str_replace('"','',$vales[1]);
           $values =  str_replace("'",'',$values);
           $variables_array[$vales[0]] = $values;
           
    }
    $variable_name = $variables_array;

     }else{
        $variable_name = '';
    }

    // print_r($variable_name);

    $content = $content[0];

    if(isset($action_names[$content])){
        $function_name = $action_names[$content][0];
        return call_user_func($function_name,$variable_name);
                 
    }else{
        return $shortcode_name;

    }
   
}

// do_shortcode('[shortcode hello="My nam" y="Samar Saifi Saiadsafadf adfsasdf"]');

