<?php

function IQ_site_options_delete($array){
    // delete site options
    if(isset($array['id'])){ $query = 'id='.$array['id']; }
    if(isset($array['option_name'])){ $query = 'option_name="'.$array['option_name'].'"'; }
    if(isset($array['option_value'])){ $query = 'option_value="'.$array['option_value'].'"'; }
    if(isset($array['query'])){ $query = 'query="'.$array['query'].'"'; }
    deletedata('site_options', $query); 
    return 1;
}

function IQ_site_options_update($array){   
    foreach($array as $option=>$val){
    $update_data = ['option_value'=>$val];
    $fetch = fetch('site_options','option_name="'.$option.'"');
   
    if(!empty($fetch)){
    updatedata($update_data, 'option_name="'.$option.'"', 'site_options'); 
}else{
    $update_data += ['option_name'=>$option];
    insertdata($update_data,'site_options');
    
}

}
    return 1;
}

