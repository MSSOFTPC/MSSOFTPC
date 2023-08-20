
<?php include('include.php');
head('IQ Addons');
loginonly('themes.php');
?>        

<?php get_sidebar(); ?>
        <main class="content">
<?php get_header(); ?>
<?php
dashbreadcrumb('addons.php?action=new','','Addons');

// upload data
  // Addons_installation
    // Addons_uploader
    if(isset($_POST['addons_uploader'])){
        $path = ADDONSDIR;
        $addons = $_FILES['addons_upload'];
        $name = $addons['name'];
        if(substr(strrchr($name, '.'), 1) == 'zip'){
            $realpath = realpath($path);
            if(move_uploaded_file($addons['tmp_name'], $path.$name)){
                $invalidaddons = false;
                $zip = new ZipArchive;  
                         if($zip->open($path.$name))  
                         {  
                            
                            //   check file support
                            $total = $zip->count();
                            
                                for ($i=0; $i<$total; $i++) {
                                    $zipdata = explode('/',$zip->statIndex($i)['name']);
                                    $zipdata =  $zipdata[0].'/'.$zipdata[0].'.php';
                                    if($zip->statIndex($i)['name'] == $zipdata){
                                          $zip->extractTo($path);  
                                          removeDir($path.'__MACOSX');
                                          removeDir($path.$name);
                                        $_SESSION['success_addons_notice'] = 'Successfully Addons Uploaded';   
                                        $invalidaddons = true;                             
                                    }

                                
                                }
                                

                                $zip->close();  
    
    
                         }  

                         if($invalidaddons == false){
                            unlink($path.$name);
                            $_SESSION['error_addons_notice'] = 'Uploaded Adoons not Supported.';   
                        }
                         back('reload');
    
    
            }else{
                $_SESSION['error_addons_notice'] = 'Upload Error! Please try again after few minutes';
            }
        }else{
          $_SESSION['error_addons_notice'] = 'Upload Zip Only';
          back('reload');
        }
        
      
      }


// end upload data



IQ_add_admin_notice('addons');
?>

<?php
   if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'new'){?>
        
        <div class="col-12 col-xxl-12 mb-4">
                <div class="card border-0 shadow">
                <div class="card-header border-bottom">

                <form  id="addons_uploader"  action="<?php echo site_url('currentURL');?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Upload Addons </label> 
                    <input class="form-control" type="file" name="addons_upload" id="theme_upload" accept=".zip" required>
                </div>
                <div class="row">
                    <div class="col-md-1">
                <button type="submit" name="addons_uploader" class="btn btn-primary" >Upload</button>
                </div>
                <div class="col-md-1">
                <small class="btn" onclick="window.open('<?php echo site_url('admin', 'addons.php?');?>', '_self')">Close</small>
                </div>

                </div>
                </form>


   </div>
   </div>
   </div>



   <?php }


?>

<!-- Tables All Addons -->

<div class="card card-body shadow border-0 table-wrapper table-responsive">
    <div class="d-flex mb-3">
        <select class="form-select fmxw-200" aria-label="Message select example">
            <option selected="">Bulk Action</option>
           
        </select>
        <button class="btn btn-sm px-3 btn-secondary ms-3">Apply</button>
    </div>
    <table class="table user-table table-hover align-items-center">
        <thead>
            <tr>
                <th class="border-bottom">
                    <div class="form-check dashboard-check">
                        <input class="form-check-input" type="checkbox" value="" id="userCheck55">
                        <label class="form-check-label" for="userCheck55">
                        </label>
                    </div>
                </th>
                <th class="border-bottom">Addons Name</th>
                <th class="border-bottom">Description</th>
            
            </tr>
        </thead>
        <tbody>
            
        <?php 
$addons_location = ADDONSDIR;
$addons_scandir = scandir($addons_location);
$is_data = 0;
foreach($addons_scandir as $path){
    if(($path != '.') && ($path != '..') && ($path != '__MACOSX')){
    if(is_dir($addons_location.$path)){
   
    $addons_path = site_url('','content/addons/'.$path);
    $currentdir = scandir(ADDONSDIR.$path);
    
if(file_exists((ADDONSDIR.$path.'/'.$path.'.php'))){
    
    $styledata = file_get_contents('../content/addons/'.$path.'/'.$path.'.php');
    $styledata = substr($styledata, 1, strpos($styledata, "*/"));
    if(!empty($styledata)){
    $styledata = str_replace('/*','', $styledata);
    $styledata = str_replace('<','', $styledata);
    $styledata = str_replace('?php','', $styledata);
    $styledata = str_replace('*','', $styledata);

    $styledata = explode("\n", $styledata);
    $theme_data = []; 
    foreach($styledata as $data){
        $data = explode(':', $data,2);
        if(!isset($data[1])){ $data[1] = '';}
        $data[0] = strtolower(str_replace(' ', '', $data[0]));
        $theme_data += [$data[0] => $data[1]];
    }

    if(isset($theme_data['addonsname'])){
        $theme_data['addonsname'] = ltrim($theme_data['addonsname']);
    $addons_name_lower = $theme_data['addonsname'];
    $addons_path_lower = $path;
    if($addons_name_lower == $addons_path_lower){

        if(is_addons_active($theme_data['addonsname']) == 0){ $addons_btn = 'Active'; }else{ $addons_btn = 'Deactive'; }


    $is_data = 1;
     ?>


            <tr>
                <td width="5%">
                    <div class="form-check dashboard-check">
                        <input class="form-check-input" type="checkbox" value="" id="userCheck1">
                        <label class="form-check-label" for="userCheck1">
                        </label>
                    </div>
                </td>
                <td width="35%"> 
                            <span class="fw-bold"><?php if(isset($theme_data['addonsname'])){ echo $theme_data['addonsname']; } ?></span>
                            <div>
                            <button class="small text-gray btn-primary btn-sm btn <?php if($addons_btn == 'Active'){ echo 'addons_active'; }else{ echo 'addons_deactive';} ?>" data-id="<?= $theme_data['addonsname']; ?>" ><?= $addons_btn;?></button> 
                            <?php if($addons_btn == 'Active'){ ?> |
                            <button class="small text-white btn btn-sm btn-danger addons_del" data-id="<?=$theme_data['addonsname']; ?>" >Del</button>
                      <?php  } ?>

                            </div>
                </td>
                <td width="60%">
                    <p><?php if(isset($theme_data['description'])){ print($theme_data['description']); } ?></p>
                    <span class="fw-normal">Version: <?php echo $theme_data['version']; ?></span>
                    <button class="small text-gray btn btn-sm btn-primary" onclick="window.open('<?php if(isset($theme_data['demo'])){ echo $theme_data['demo']; }; ?>')">Demo</button> 

                </td>
               
            </tr>

<?php } }} } }} }
if($is_data == 0){ echo '<tr><td colspan="12"><h1 style="text-align: center">No Addons Available</h1></td></tr>'; }
?>

        </tbody>
    </table>
</div>

<!-- end addons -->





     <script>
        // activate
  $('.addons_active').on('click', function(){
                    $.ajax({
            type: "POST",
            url: "<?php echo site_url('admin', 'functions/function_api.php'); ?>",
            data: {addons_controller: "text", addons_name:$(this).data('id')},
            success: function(data){
                console.log(data)
                    if(data == 1){
                        location.reload();
                    }else{
                        location.reload();
                    }
            }
              })
})

// deactivate
$('.addons_deactive').on('click', function(){
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('admin', 'functions/function_api.php'); ?>",
            data: {addons_controller: "text", addons_deactive:$(this).data('id')},
            success: function(data){
                console.log(data)
                    if(data == 1){
                        location.reload();
                    }else{
                        location.reload();
                    }
            }
              })
})

            // delete

  $('.addons_del').on('click', function(){
                 if (confirm("Are You Sure want to Delete "+$(this).data('id')+" Addon")) {
                    $.ajax({
		type: "POST",
		url: "<?php echo site_url('admin', 'functions/function_api.php'); ?>",
		data: {addons_controller: "text", addons_delete:$(this).data('id')},
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