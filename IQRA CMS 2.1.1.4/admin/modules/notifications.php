<?php 
function IQ_notice($status, $msg){
    $_SESSION[$status] = $msg;
}

function msg(){
    if(isset($_SESSION['error'])){ ?>
           <script>
        notyf = new Notyf({ position: { x: 'right', y: 'top', }, types: [{ type: 'error', 
            background: 'red', icon: { className: 'fas fa-comment-dots',  tagName: 'span', color: '#fff' }, dismissible: false }]
        });
        notyf.open({ type: 'error', message: '<?php echo $_SESSION['error']; ?>' })
    </script>
    <?php unset($_SESSION['error']);}
     if(isset($_SESSION['success'])){ ?>
  <script>
        notyf = new Notyf({ position: { x: 'right', y: 'top', }, types: [{ type: 'success', 
            background: 'green', icon: { className: 'fas fa-comment-dots',  tagName: 'span', color: '#fff' }, dismissible: false }]
        });
        notyf.open({ type: 'success', message: '<?php echo $_SESSION['success']; ?>' })
    </script>

<?php unset($_SESSION['success']); }?> 

<?php  function alert($icon, $title, $desc, $footer =null, $footerlink = null){
    if(!isset($footer)){$footer = '';}
    if(empty($icon)){$icon = 'error';}
    if(!isset($footerlink)){$footerlink = '';}
       echo "	<script>Swal.fire({
        icon: '".$icon."',
        title: '".$title."',
        text: '".$desc."',
        footer: '<a href=".$footerlink.">".$footer."</a>'
    })</script>";
 }
 
 if(isset($_SESSION['popup_success'])){
    if(!isset($_SESSION['popup_success_msg'])){$_SESSION['popup_success_msg'] = ''; }
    if(!isset($_SESSION['popup_success_msg'])){$_SESSION['popup_success_footer'] = ''; }
    if(!isset($_SESSION['popup_success_msg'])){$_SESSION['popup_success_footerlink'] = ''; }
    alert('success', $_SESSION['popup_success'], $_SESSION['popup_success_msg'], $_SESSION['popup_success_footer'], $_SESSION['popup_success_footerlink']);
    unset($_SESSION['popup_success_msg']); unset($_SESSION['popup_success']); unset($_SESSION['popup_success_footer']); 
    unset($_SESSION['popup_success_footerlink']);
 }
 if(isset($_SESSION['popup_error'])){
    if(!isset($_SESSION['popup_error_msg'])){$_SESSION['popup_error_msg'] = ''; }
    if(!isset($_SESSION['popup_error_footer'])){$_SESSION['popup_error_footer'] = ''; }
    if(!isset($_SESSION['popup_error_footerlink'])){$_SESSION['popup_error_footerlink'] = ''; }
    alert('error', $_SESSION['popup_error'], $_SESSION['popup_error_msg'], $_SESSION['popup_error_footer'], $_SESSION['popup_error_footerlink']);
    unset($_SESSION['popup_error_msg']); unset($_SESSION['popup_error']); unset($_SESSION['popup_error_footer']); 
    unset($_SESSION['popup_error_footerlink']);
 }?>

<?php }

function popupmsg($for, $msg, $shortmsg = null, $footer = null, $footerlink = null){
    $_SESSION['popup_'.$for] = $msg;
    $_SESSION['popup_'.$for.'_msg'] = $shortmsg;
    $_SESSION['popup_'.$for.'_footer'] = $footer;
    $_SESSION['popup_'.$for.'_footerlink'] = $footerlink;
 }
?>

<?php
//  die function
function IQ_die($msg = null){
    if(!isset($msg)){ $msg = ''; }
    if($msg == ''){ $msg = "You Can't Access This Area"; }
     die($msg);
}
 
?>


<?php 
// IQ_admin_notice

// global notice
function IQ_add_admin_notice($data){
    if(isset($_SESSION['success_'.$data.'_notice'])){ IQ_admin_notice($_SESSION['success_'.$data.'_notice'], 'success'); unset($_SESSION['success_'.$data.'_notice']);}
    if(isset($_SESSION['error_'.$data.'_notice'])){ IQ_admin_notice($_SESSION['error_'.$data.'_notice'], 'error'); unset($_SESSION['error_'.$data.'_notice']);}
    IQ_admin_notice_show();
    add_action('IQ_admin_'.$data.'_notice_show');
}


function IQ_admin_notice_show(){
    if(isset($_SESSION['success_notice'])){ IQ_admin_notice($_SESSION['success_notice'], 'success'); unset($_SESSION['success_notice']);}
    if(isset($_SESSION['error_notice'])){ IQ_admin_notice($_SESSION['error_notice'], 'error'); unset($_SESSION['error_notice']);}
    add_action('IQ_admin_notice_show');
}

function IQ_admin_notice($title, $status = null, $url = null){ 
    if(isset($url)){ $url = '<span style="font-size: 15px"><a href="'.$url.'" style="color: red">Visit</a></span>';}else{ $url = '';}
    if(!isset($status) || empty($status)){ $status = 'success';}
    
    ?>
<div class="card notice instant-indexing-notice notice-<?= $status; ?> is-dismissible">
    <p><?= $title;?>&nbsp; <?= $url; ?></p>
    <button type="button" class="notice-dismiss"><i class="bi bi-x-circle-fill"></i><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>


<?php }

// example
// function function_name(){ 
//     IQ_admin_notice('any msg', 'sucess/error', 'path_url');
//     // or use here custom code
// }
// add_action('notice_name', 'function_name');
// or use session
// $_SESSION['success_addons_notice'] = 'value';



// 2nd
// function custom_notice(){
//     IQ_admin_notice('Hello', 'error', 'sadf');
// }

// add_action('IQ_admin_IQ_mailchimp_notice_show','custom_notice');

// 3 rd
// $_SESSION['success_IQ_mailchimp_notice'] ='hello';

?>