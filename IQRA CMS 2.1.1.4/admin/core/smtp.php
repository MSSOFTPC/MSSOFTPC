<?php 
function add_IQ_SMTP(){
    function IQ_smtp(){
        get_field(array('title'=>'Host','name'=>'smtp_host', 'class'=>'col-md-6 mb-3','value'=>site_options('return_smtp_host'),'required'=>''));
        get_field(array('title'=>'Port','name'=>'smtp_port', 'class'=>'col-md-6 mb-3','value'=>site_options('return_smtp_port'),'required'=>''));
        get_field(array('title'=>'Username','name'=>'smtp_username', 'class'=>'col-md-6 mb-3','value'=>site_options('return_smtp_username'),'required'=>''));
        get_field(array('title'=>'Password','name'=>'smtp_password', 'class'=>'col-md-6 mb-3','value'=>site_options('return_smtp_password'),'required'=>''));
    }
    register_site_options(array('title'=>'SMTP Settings', 'function'=>'IQ_smtp','slug'=>'smtp'));
    }
    
    add_action('init','add_IQ_SMTP');