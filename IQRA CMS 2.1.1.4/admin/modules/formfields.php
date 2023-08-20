
<?php function galleryfield($gallerytype = null, $enable = null){  ?>
<div class="row align-items-center">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="featured_img">Gallery Type</label>
            <select name="gallery_type" id="gallerytype" class="form-control" <?php if(isset($enable)){print('disabled');} ?> <?php if(isset($gallerytype)){ print('disabled');} ?>>
                <option value="image" <?php if(isset($enable) && $enable == 'image'){print('selected');} ?> <?php //if(isset($gallerytype) && $gallerytype == 'image'){ print('selected');} ?>>Image</option>
                <option value="audio" <?php if(isset($enable) && $enable == 'audio'){print('selected');} ?> <?php //if(isset($gallerytype) && $gallerytype == 'audio'){ print('selected');} ?>>Audio</option>
                <option value="youtube" <?php if(isset($enable) && $enable == 'youtube'){print('selected');} ?> <?php //if(isset($gallerytype) && $gallerytype == 'youtube'){ print('selected');} ?>>Youtube</option>
                <option value="video" <?php if(isset($enable) && $enable == 'video'){print('selected');} ?> <?php //if(isset($gallerytype) && $gallerytype == 'video'){ print('selected');} ?>>Video</option>
            </select>
        </div>
 </div>

<div class="col-md-6 mb-3" id="upload_gallery_field">
    <div class="form-group" >
        <label for="gallery_cat">Upload Images Only</label>
        <input class="form-control" id="gallery_upload_files" type="file" name="gallery_img[]" multiple accept="image/png, image/jpeg">
    </div>
</div>
<!-- youtube fields -->
<div class="col-md-6 mb-3 youtube_links" id="youtube_gallery_field" style="display: none">
<div class="row align-items-center">
    <div class="col-md-8">
    <div class="form-group" >
        <label for="youtube_upload_files">Youtube Links Only</label>
        <input class="form-control" id="youtube_upload_files" type="url" name="gallery_youtube[]" Placeholder="Youtube Links Only">
    </div>
    </div>
    <div class="col-md-4" id="btnarea">
    <button type="button" class="btn btn-gray-800" id="addmore"> + </button>
    </div>
</div>
</div>
</div>

<!-- gallery field -->
<?php if(isset($gallerytype) && !empty($gallerytype)){?>
        <div class="row">
<?php global $db; $fetch_img = $db->fetch_all('galleries', 'post_id='.$_GET['id']);

    foreach($fetch_img as $images){
        
    ?>
    <?php if($gallerytype == 'image'){ ?>
            <div class="col-md-3">
                 <div class="card border-0 shadow p-4" draggable="false">
                    <div class="card-header d-flex align-items-center justify-content-between border-0 p-0 mb-3">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm fs-6 px-1 py-0 dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="icon icon-xs text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd">
                            </path>
                            </svg>
                            </button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                                <a class="dropdown-item fw-normal text-danger rounded-bottom" href="<?php echo 'action.php?title=galleries&action=del&id='.$images['id']; ?>" draggable="false">
                                    <span class="fas fa-trash-alt"></span>Remove
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                    <img src="<?php echo adminassets('galleries/image/'.$images['gallery'], 'img');?>" title="<?php echo $images['gallery']; ?>" class="card-img-top mb-2 mb-lg-3" alt="" draggable="false">
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if($gallerytype == 'audio'){ ?>
            <div class="col-md-3">
                 <div class="card border-0 shadow p-4" draggable="false">
                    <div class="card-header d-flex align-items-center justify-content-between border-0 p-0 mb-3">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm fs-6 px-1 py-0 dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="icon icon-xs text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd">
                            </path>
                            </svg>
                            </button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                                <a class="dropdown-item fw-normal text-danger rounded-bottom" href="<?php echo 'action.php?title=gallery&action=del&id='.$images['id']; ?>" draggable="false">
                                    <span class="fas fa-trash-alt"></span>Remove
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                    <audio src="<?php echo adminassets($_GET['title'].'/audio/'.$images['gallery'], 'img');?>" controls title="<?php echo $images['gallery']; ?>" class="card-img-top mb-2 mb-lg-3" alt="" draggable="false">
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if($gallerytype == 'video'){ ?>
            <div class="col-md-3">
                 <div class="card border-0 shadow p-4" draggable="false">
                    <div class="card-header d-flex align-items-center justify-content-between border-0 p-0 mb-3">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm fs-6 px-1 py-0 dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="icon icon-xs text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd">
                            </path>
                            </svg>
                            </button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                                <a class="dropdown-item fw-normal text-danger rounded-bottom" href="<?php echo 'action.php?title=gallery&action=del&id='.$images['id']; ?>" draggable="false">
                                    <span class="fas fa-trash-alt"></span>Remove
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                    <video controls src="<?php echo adminassets($_GET['title'].'/video/'.$images['gallery'], 'img');?>" title="<?php echo $images['gallery']; ?>" class="card-img-top mb-2 mb-lg-3" alt="" draggable="false">
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if($gallerytype == 'youtube'){ ?>
            <div class="col-md-3 mt-3">
                 <div class="card border-0 shadow p-4" draggable="false">
                    <div class="card-header d-flex align-items-center justify-content-between border-0 p-0 mb-3">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm fs-6 px-1 py-0 dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="icon icon-xs text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd">
                            </path>
                            </svg>
                            </button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                                <a class="dropdown-item fw-normal text-danger rounded-bottom" href="<?php echo 'action.php?title=gallery&action=del&id='.$images['id']; ?>" draggable="false">
                                    <span class="fas fa-trash-alt"></span>Remove
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                    <iframe width="100%" height="100%" src="<?php echo $images['gallery']; ?>"> </iframe>
                    </div>
                </div>
            </div>
            <?php } ?>


<?php } ?>
            

             

        </div>
    <?php }?>

<!-- youtube fields -->


    <script>
        youtube_calling();
        $('#gallerytype').change('click',function(){
            youtube_calling();
        });
        function youtube_calling(){
            gallery = $('#gallerytype').val();
            if(gallery == 'image'){
                    $('#upload_gallery_field').find('label').text('Upload Images Only');
                    $('#upload_gallery_field').find('#gallery_upload_files').attr('accept', 'image/png, image/jpeg');
                    $('#upload_gallery_field').attr('style', 'display:block');
                    $('.youtube_links').attr('style', 'display:none');
                }
                if(gallery == 'audio'){
                    $('#upload_gallery_field').find('label').text('Upload Audio Only');
                    $('#upload_gallery_field').find('#gallery_upload_files').attr('accept', 'audio/ogg, audio/mpeg');
                    $('#upload_gallery_field').attr('style', 'display:block');
                    $('.youtube_links').attr('style', 'display:none');
                }
                if(gallery == 'youtube'){
                    $('#upload_gallery_field').attr('style', 'display:none');
                    $('.youtube_links').attr('style', 'display:block');
                }
                if(gallery == 'video'){
                    $('#upload_gallery_field').find('label').text('Upload Video Only');
                    $('#upload_gallery_field').find('#gallery_upload_files').attr('accept', 'video/mp4,video/x-m4v,video/*');
                    $('#upload_gallery_field').attr('style', 'display:block');
                    $('.youtube_links').attr('style', 'display:none');
                }
        }

        function addmoreyoutube(){
            
            $('#addmore').click(function () { 
                clone = $( "#youtube_gallery_field").clone();
                clone.find('#addmore').remove();
                clone.find('.youtube_links').attr('id', '');
                clone.find('#btnarea').append('<button type="button" class="btn btn-danger" id="btnremove"> X </button>');
                clone.insertAfter('#youtube_gallery_field');  
                  
            });
           
            
            $(document).on('click', '#btnremove', function () { 
                                    $(this).closest('.youtube_links').remove();
                
            });
           
            
            
             
           
        }
        addmoreyoutube();
       
    </script>

<?php } ?>


<?php

// form field  global 

// $form_data = array(
//     'title'=>'',
//     'name'=>'',
//     'type'=>'',
    // 'id'=>'',
    // 'value'=>'',
    // 'label'=>0,
    // 'class'=>'',
     // 'required'=>'',
     // 'field_class'=>'',
     // 'field_id'=>'',
     // 'placeholder'=>'',
    // 'extra_input_data'=>'',
// );

// $form_isset = array(
//     'isset'=>array(),
//      'post_type'=>'',

        // 'value'=>'', (Not Need)
// )


// $form_button = array(
//     'title'=>'',
//     'name'=>'',
//     'type'=>'',
    // 'id'=>'',
    // 'class'=>'',
     // 'field_class'=>'',
    // 'extra_input_data'=>'',
// );

// $form_range = array(
    // 'min'=>'',
    // 'max'=>'',
// );

// $select = array(
    // 'options'=>array(array('value','key','extra')),
    // 'multi/multipart'=>'',
// );



function get_field($form_data){

    $run = 1;
    // field check is exist
    if(isset($form_data['isset'])){ 
        if(isset($form_data['isset'][$form_data['name']])){ $form_data['value'] = $form_data['isset'][$form_data['name']]; }else{
            if(isset($form_data['isset'][0]['meta_key'])){
                 foreach($form_data['isset'] as $data){
                    if($data['meta_key'] == $form_data['name']){
                        $form_data['value'] = $data['meta_value'];
                    }
                 }
            }
        } }
    // end field check if exist

    if(isset($form_data['post_type']) && $_GET['post_type'] != $form_data['post_type']){ $run = 0; }

    if($run == 1){

    if(!isset($form_data['name'])){ $form_data['name'] = ''; }

    $name = $form_data['name'];
    $fields = '';

    if(!isset($form_data['type'])){ $form_data['type'] = 'text'; }
    if(isset($form_data['min'])){ $min = $form_data['min'];}else{ $min = ''; }
    if(isset($form_data['multiple']) || isset($form_data['multi'])){ $multi_array = '[]'; $multiple = 'multiple';}else{ $multi_array = ''; $multiple = ''; }
    if(isset($form_data['title'])){ $title = $form_data['title'];}else{ $title = 'Unkonwn Field'; }
    if(isset($form_data['max'])){ $max = $form_data['max'];}else{ $max = ''; }
    if(isset($form_data['value'])){ $value = $form_data['value'];}else{ $value = ''; }
    if(isset($form_data['field_class'])){ $field_class = 'form-control '.$form_data['field_class'];}else{ $field_class = 'form-control'; }
    if(isset($form_data['field_class'])){ $field_class_default = $form_data['field_class'];}else{ $field_class_default = ''; }
    if(isset($form_data['img_url'])){ $img_url = $form_data['img_url'];}else{ $img_url = ''; }
    if(isset($form_data['class'])){ $class = $form_data['class'];}else{ $class = ''; }
    if(isset($form_data['id'])){ $id = $form_data['id'];}else{ $id = $name.$form_data['type']; }
    if(isset($form_data['required'])){ $required = 'required';}else{ $required = ''; }
    if(isset($form_data['placeholder'])){ $placeholder = $form_data['placeholder'];}else{ $placeholder = ''; }
    if(isset($form_data['extra_input_data'])){ $extra_input_data = $form_data['extra_input_data'];}else{ $extra_input_data = ''; }
    if(isset($form_data['label']) && $form_data['label'] == 0){ $label = ''; }else{ $label = '<label for="'.$id.'">'.$title.'</label>'; }
    $fields .=  '<div class="'.$class.'">';
    $fields .= $label;

    
  

    if($form_data['type'] == 'text' || $form_data['type'] == 'tel' || $form_data['type'] == 'email' || $form_data['type'] == 'password' || $form_data['type'] == 'number' || $form_data['type'] == 'url'){
        $fields .=  '<input class="'.$field_class.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$extra_input_data.' type="'.$form_data['type'].'" placeholder="'.$placeholder.'" '.$required.'>';
    }

    if($form_data['type'] == 'reset' || $form_data['type'] == 'submit' || $form_data['type'] == 'button'){
        $fields .=  '<button class="'.$field_class_default.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$extra_input_data.' type="'.$form_data['type'].'" >'.$title.'</button>';
    }

    if($form_data['type'] == 'file'){
        $fields .=  '<input class="'.$field_class.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$extra_input_data.' type="'.$form_data['type'].'" '.$required.'>';
        if(!empty($img_url)){ $fields .= '<img src="'.$img_url.'" class="w-50 img-thumbnail img-fluids">'; }
    }


    if($form_data['type'] == 'range'){
        $fields .=  '<input class="'.$field_class.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$extra_input_data.' type="'.$form_data['type'].'" min="'.$min.'" max="'.$max.'" '.$required.'>';
    }

    if($form_data['type'] == 'checkbox' || $form_data['type'] == 'radio'){
        $fields .=  ' <input class="form-check-input '.$field_class_default.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$extra_input_data.' type="'.$form_data['type'].'" '.$required.'>';
    }


    if($form_data['type'] == 'textarea'){
        $fields .=  '<textarea class="'.$field_class.'" id="'.$id.'" name="'.$name.'" '.$extra_input_data.' '.$required.'>'.$value.'</textarea>';
        
    }

    if($form_data['type'] == 'week' || $form_data['type'] == 'time' || $form_data['type'] == 'color' || $form_data['type'] == 'date' || $form_data['type'] == 'datetime-local' || $form_data['type'] == 'month'){
        $fields .=  '<input class="'.$field_class.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$extra_input_data.' type="'.$form_data['type'].'" '.$required.'>';
    }

    if($form_data['type'] == 'select'){
        $fields .=  '<select class="'.$field_class.'" id="'.$id.'" name="'.$name.$multi_array.'" '.$multiple.' '.$extra_input_data.' type="'.$form_data['type'].'" '.$required.'>';
        if(isset($form_data['options'])){
        foreach($form_data['options'] as $options){
            if(!isset($options[2])){ $options[2] = '';}
            if($value == $options[0]){ $selected = 'selected'; }else{ $selected = '';}
            $fields .= '<option value="'.$options[0].'" '.$selected.$options[2].'>'.$options[1].'</option>';
        }
        }
        $fields .= '</select>';
    }
   


    $fields .=  ' </div>';
   
    echo $fields;
}
}





?>