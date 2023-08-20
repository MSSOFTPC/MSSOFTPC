
<?php include('include.php');
head('IQ Media Management');
loginonly('');

if(isset($_POST['IQ_media_del'])){
  if(del_media($_POST['IQ_media_del_id'])){
    IQ_notice('success_media_notice','Media Deleted Successfully');
    back('reload');
  }else{
    IQ_notice('error_media_notice','Media Permission Denied');
    back('reload');
  }

}

?>        



<?php get_sidebar(); ?>
        <main class="content">
<?php get_header(); 
dashbreadcrumb('media.php?action=new'); 
IQ_add_admin_notice('media');

?>
<!-- main body start -->

<!-- upload media -->
<?php if(isset($_POST['media_uploader'])){
  if(!empty($_FILES['media_upload']['name'])){
    
    // insert images
    if(!empty(IQ_media_insert(array('media'=>'media_upload','dir'=>'')))){
      $_SESSION['success_media_notice'] = 'Successfully File Uploaded';
    }


  }else{
    $_SESSION['error_media_notice'] = 'File is Empty! Please Select File';
  }
}


?>

<?php 

if(isset($_GET['action']) && $_GET['action'] == 'new'){ ?>
<div class="col-12 col-xxl-12 mb-4">
                <div class="card border-0 shadow">
                <div class="card-header border-bottom">

                <form id="media_uploader" action="<?php echo site_url('admin','media.php?action=new');?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="media_upload" class="form-label">Upload Gallery </label> 
                    <input class="form-control" type="file" name="media_upload" id="media_upload" required="false">
                </div>
                <div class="row">
                    <div class="col-md-1">
                <button type="submit" name="media_uploader" class="btn btn-primary">Upload</button>
                </div>
                <div class="col-md-1">
                <small class="btn" onclick="window.open('media.php', '_self')">Close</small>
                </div>

                </div>
                </form>


   </div>   </div>   </div>
<?php }

// isset filter
if(isset($_GET['filter_type']) && $_GET['filter_type'] != 'all'){
  $filtertype = $_GET['filter_type'];
}else{$filtertype = ''; }

// isset directories
if(isset($_GET['filter_directories']) && $_GET['filter_directories'] != 'all'){
  $filter_dir_get = $_GET['filter_directories'];
}else{$filter_dir_get = ''; }

// fetch all directories
$all_directories = fetch_distictdata('post','guid');

?>

        <!-- details -->
        <form action="">
        <div class="bg-white rounded shadow media-top" style="">
            <label for="media-attachment-filters" class="screen-reader-text">Filter by type</label>
            <select id="media-attachment-filters" name="filter_type" class="attachment-filters">
              <option value="all" <?php if($filtertype === 'all'){ echo 'selected'; } ?>>All media items</option>
              <option value="image" <?php if($filtertype === 'image'){ echo 'selected'; } ?>>Images</option>
              <option value="audio" <?php if($filtertype === 'audio'){ echo 'selected'; } ?>>Audio</option>
              <option value="video" <?php if($filtertype === 'video'){ echo 'selected'; } ?>>Video</option>
              <option value="documents" <?php if($filtertype === 'documents'){ echo 'selected'; } ?>>Documents</option>
            </select>

            <label for="media-attachment-date-filters" class="screen-reader-text">Filter by date</label>
            <select id="media-attachment-date-filters" name="filter_directories" class="attachment-filters">
              <option value="all" <?php if($filter_dir_get === 'all'){ echo 'selected'; } ?>>All Directories</option>
              <?php
              foreach($all_directories as $dir){
                if($dir['guid'] != ''){
                if($dir['guid'] === $filter_dir_get){ $selected = 'selected'; }else{ $selected = ''; }
                  echo '<option value="'.$dir['guid'].'" '.$selected.'>'.$dir['guid'].'</option>';
                }}
              ?>
            </select>
            <button type="submit" class=" btn btn-primary">Bulk select</button>
            <span class="spinner"></span>
            </form>
        </div>

        <!-- galleries -->
        <div class="media-gallery IQ_media attachments-wrapper rounded bg-white mt-3 p-3">

        <?php
    
        
       $all_media = get_media(array('query'=>'','pagination'=>true,'limit'=>15, 'search'=>true,'filter_type'=>$filtertype,'filter_dir'=>$filter_dir_get));
?>


<!-- Imagies -->
      <ul tabindex="-1" class="attachments ui-sortable ui-sortable-disabled" >
<?php
      if(empty($all_media)){ echo '<h1>No Media Found</h1>';}

        foreach($all_media as $images){

         $image = get_media_link(array('id'=>$images['id'],'fullpath'=>true));
        
          
          ?>
          
          <li tabindex="0" role="checkbox" aria-label="fevicon" aria-checked="false" data-id="<?php echo $images['id']; ?>" class="attachment save-ready">
              <div class="attachment-preview rounded shadow landscape">
                <div class="thumbnail">
                  <div class="centered">
                    <img src="<?=$image[0]; ?>" draggable="false"  alt="">
                  </div>
                </div>
              </div>
          </li>
<?php } ?>
         
        </ul>
        </div>
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between media-gallery-footer rounded bg-white p-3 mt-4 load-more-wrapper">
     
     
     
        <!-- pagination -->
        
<?php 


     echo ' <ul class="pagination">';
		$paginationdata = $db->paginationlast();  
			foreach($paginationdata as $paginationcount){
				foreach($paginationcount as $data=>$val){ 
					if(isset($_GET['page'])){$page = $_GET['page'];}else{ $page = 1;}
					      if($data == $page){$pagistatus = 'active';}else{$pagistatus = '';} 
						echo    '<li class="page-item '.$pagistatus.'"><a class="page-link" href="'. $val .'">'.$data.'</a></li>';
							}}
						
           echo  ' </ul></nav>';
		$totalcount = $db->paginationlast('total'); 
		echo ' <div class="fw-normal small mt-4 mt-lg-0">Showing <b>'. $totalcount[0].'</b> out of <b>'. $totalcount[1].'</b> entries</div>';

			echo  '</ul>';

?>


        
        </div>


        <!-- Media Modal -->

        <?php 
        function modal_media(){ ?>
          <div class="modal-header">
                <h2 class="h6 modal-title">Media Details</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <img src="" class="featured_img" alt="No Preview Data">
                    
                    <form method="post">
                    <a href="" target="blank" class="IQ_media_view_btn mt-1 btn btn-primary btn-small">View</a>
                    <input name="IQ_media_del_id" id="IQ_media_del_id" value="" type="hidden">
                    <button name="IQ_media_del" class="IQ_media_del mt-1 btn btn-danger btn-small">Delete</button>
                    </form>
                  </div>
                  <div class="col-md-6 IQ_media_form">
                    <form action="" method="post">
                      <?php
                            get_field(array('title'=>'Title','name'=>'title','id'=>'title'));
                            get_field(array('title'=>'Media url','extra_input_data'=>'disabled data-type="copy"', 'name'=>'media_url', 'class'=>'mt-3'));
                            echo '<button type="button" onclick="IQ_media_url_copy(this)"  class="mt-1 btn btn-primary btn-small">Copy Clipboard</button>';
                      ?>
                    </form>
                    <div class="note mt-3">
                    <p><strong>More Details</strong> </p>
                      <ul>
                       
                        <li >Uploaded on: <span id="uploadedon"></span></li>
                        <li >Uploaded by: <span id="uploadedby"></span></li>
                        <li >File name: <span id="filename"></span></li>
                        <li >File type: <span id="filetype"></span></li>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
            </div>
        <?php }


        IQ_register_modal(array('modalid'=>'IQ_media_editor','style'=>'max-width: 90%','function'=>'modal_media')); ?>
      

        <script>
  // onlick copy src 

  $('.IQ_media .attachment').on('click', function(){
    dataid = $(this).data('id');

    // media send
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('admin', 'functions/function_api.php'); ?>",
		data: {IQ_media_controller: "get", id:dataid},
		success: function(data){
            // console.log(data)
          data = $.parseJSON(data);
          data = data[0];

          // images
          $('#IQ_media_editor .featured_img').attr('src','<?php echo assets('','upload'); ?>'+data['guid']+'/'+data['featured_img']);
          $('#IQ_media_editor .IQ_media_view_btn').attr('href','<?php echo assets('','upload'); ?>'+data['guid']+'/'+data['featured_img']);
          $('#IQ_media_editor #IQ_media_del_id').val(data['id']);
          // from
          $('#IQ_media_editor .IQ_media_form form input[name=title]').val(data['title']);
          $('#IQ_media_editor .IQ_media_form form input[name=media_url]').val('<?php echo assets('','upload'); ?>'+data['guid']+'/'+data['featured_img']);

          // note
          $('#IQ_media_editor .note #uploadedon').text(data['post_date']);
          $('#IQ_media_editor .note #uploadedby').text(data['author_id']);
          $('#IQ_media_editor .note #filename').text(data['featured_img']);
          $('#IQ_media_editor .note #filetype').text(data['post_mime_type']);

        
        }
    })

    $('#IQ_media_editor').modal('show');
    
  })


  // copy data
  function IQ_media_url_copy(e){
    copytext = $(e).parent().find('[data-type=copy]').val();
    navigator.clipboard.writeText(copytext);
    $(e).text('Copied');
    setTimeout(function(){
        $(e).text('Copy Clipboard');
    },2000)
  }
</script>

<?php footer(); ?>