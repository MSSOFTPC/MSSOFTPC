
<?php include('include.php');
head('IQ Themes');
loginonly('themes.php');
?>        

<?php get_sidebar(); ?>
        <main class="content">
<?php get_header(); ?>
<?php 
function theme_view(){
    add_admin_dashboard_breadcrumb_menu(array(array('name'=>'View','url'=> site_url())));
}
add_action('admin_dashboard_breadcrumb', 'theme_view');
dashbreadcrumb('themes.php?action=new','','Themes'); 
IQ_add_admin_notice('theme');
?>

<?php
   if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'new'){?>
        
        <div class="col-12 col-xxl-12 mb-4">
                <div class="card border-0 shadow">
                <div class="card-header border-bottom">

                <form  id="theme_uploader"  action="<?php echo site_url('admin','function.php'); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Upload Theme </label> 
                    <input class="form-control" type="file" name="theme_upload" id="theme_upload" accept=".zip" required>
                </div>
                <div class="row">
                    <div class="col-md-1">
                <button type="submit" name="theme_uploader" class="btn btn-primary" >Upload</button>
                </div>
                <div class="col-md-1">
                <small class="btn" onclick="window.open('<?php echo site_url('admin', 'themes.php?title=theme');?>', '_self')">Close</small>
                </div>

                </div>
                </form>


   </div>
   </div>
   </div>



   <?php }


?>



<div class="row">

<?php 

        $currentthemedir = current_active_theme('dir');
        $get_current_theme_dir = get_current_theme_dir();
        $currenttheme_name = current_active_theme();
        
        // check active theme exist
    if(file_exists($currentthemedir.'style.css')){

    // check screenshot exist
    $screenshot = '';
    if(file_exists($currentthemedir.'screenshot.jpg')){ $screenshot = $get_current_theme_dir.'screenshot.jpg'; }
    if(file_exists($currentthemedir.'screenshot.png')){ $screenshot = $get_current_theme_dir.'screenshot.png'; }

   $style_data = style_extractor($currentthemedir.'style.css');
?>

<div class="col-12 col-xxl-4 mb-4">
                <div class="card border-0 shadow">
                        <img src="<?php echo $screenshot; ?>" alt="" srcset="">

                        <div class="card-header border-bottom">
                            <div class="row">
                                <div class="col-md-8">
                                <h2 class="fs-5 fw-bold mb-1"><?php echo $style_data['themename']; ?><span style="font-size:14px"><i class="fa fa-eye" aria-hidden="true"></i></span></h2>
                                    <small><? php if(isset($style_data['version'])){print($style_data['version']);};?></small>
                                    <small><button class="btn" onclick="window.open('<?php if(isset($style_data['themeuri'])){print($style_data['themeuri']);}; ?>')">Demo</button></small>
                                </div>
                                <div class="col-md-4">
                                <button class="btn btn-secondary themes_controller" disabled>Activated</button>
                                </div>
                            </div>
                                
                        </div>
                </div>
</div>
    
<?php } ?>

<?php 
$theme_scandir = scandir(THEMEDIR);

foreach($theme_scandir as $path){
    if(($path != '.') && ($path != '..') && ($path != '__MACOSX' && $path != $currenttheme_name)){
    
    if(is_dir(THEMEDIR.$path)){
   
    $theme_path = site_url('','content/themes/'.$path);
    $currentdir = scandir(THEMEDIR.$path);
    $screenshot = $theme_path.'/';
    foreach($currentdir as $data){
        if($data == 'screenshot.jpg'){ $screenshot .= $data; }
        if($data == 'screenshot.png'){ $screenshot .= $data; }
    }
    if(file_exists((THEMEDIR.$path.'/style.css'))){
        
       $theme_data =  style_extractor(THEMEDIR.$path.'/style.css');
    
    if(isset($theme_data['themename'])){
    $theme_name_lower = $theme_data['themename'];

    $theme_name_lower = ltrim($theme_name_lower);
    $theme_name_lower = rtrim($theme_name_lower);
    

    if($theme_name_lower === $path){

    $theme_btn = 'Deactive';
   $theme_del_btn = '<button class="btn text-danger del_controller " data-id="'.$theme_name_lower.'">Delete</button>'; 
    
     ?>
     
   <div class="col-12 col-xxl-4 mb-4">
                <div class="card border-0 shadow">
                        <img src="<?php echo $screenshot; ?>" alt="" srcset="">

                        <div class="card-header border-bottom">
                            <div class="row">
                                <div class="col-md-8">
                                <h2 class="fs-5 fw-bold mb-1"><?php echo $theme_data['themename']; ?><span style="font-size:14px"><i class="fa fa-eye" aria-hidden="true"></i></span></h2>
                                    <small><? php if(isset($theme_data['version'])){print($theme_data['version']);};?></small>
                                    <small><button class="btn" onclick="window.open('<?php if(isset($theme_data['themeuri'])){print($theme_data['themeuri']);}; ?>')">Demo</button></small>
                                    <small><?php echo $theme_del_btn;?></small>
                                </div>
                                <div class="col-md-4">
                                <button class="btn btn-secondary themes_controller" data-id="<?= $theme_name_lower; ?>" <?php if($theme_btn == 'Activated'){ echo 'disabled'; } ?> ><?= $theme_btn;?></button>
                                </div>
                            </div>
                                
                        </div>
                </div>
</div>
    
       
        
<?php } } } }} }?>


<!-- add more themees -->
<div class="col-12 col-xxl-4 mb-4" onclick="window.open('<?php echo site_url('admin','themes.php?title=themes&action=new');?>', '_self')">
<div class="icon-demo mb-4 border rounded-3 d-flex align-items-center justify-content-center p-3 py-6" style="font-size: 10em height: 100%" role="img" aria-label="Upload - large preview">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>
          </div>
</div>


<!-- end add more -->
</div>

     <script>
        // activate
  $('.themes_controller').on('click', function(){
                    $.ajax({
            type: "POST",
            url: "<?php echo site_url('admin', 'functions/function_api.php'); ?>",
            data: {theme_controller: "text", theme_name:$(this).data('id')},
            success: function(data){
                // console.log(data)
                    if(data == 1){
                        location.reload();
                    }
            }
              })


})

            // delete

  $('.del_controller').on('click', function(){
                 if (confirm("Are You Sure want to Delete "+$(this).data('id')+" Theme")) {
                    $.ajax({
		type: "POST",
		url: "<?php echo site_url('admin', 'functions/function_api.php'); ?>",
		data: {theme_controller: "text", theme_delete:$(this).data('id')},
		success: function(data){
            // console.log(data)
                if(data == 1){
                    location.reload();
                }else{
                    location.reload();
                }
        }
    })

                    } 
               



            })

     </script>

  
<?php footer(); ?>