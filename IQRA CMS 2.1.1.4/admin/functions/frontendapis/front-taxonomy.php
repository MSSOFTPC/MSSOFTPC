<?php

function IQ_post_delete($id){
    // delete post
    deletedata('post', 'id='.$id); 
    deletedata('post_meta', 'post_id='.$id); 
    unlink_taxonomy(array('id'=>$id));
    return 1;
}

function IQ_post_clone($id){
    // clone post
//    return 'Currunty Working';
}

// $arry = array(
//     'title'=>'', required
//     'post_type'=>'', required
//     'permalink'=>'', optional
//     'content'=>'', optional
//     'status'=>'', optional
//     'layout'=>'', optional
//     'featured_img'=>'', optional
//     'post_meta'=>array(array('meta'=>'value'),) or $array[] = [''=>''] or $meta = += [''=>'']  options
// );




function IQ_post_insert($array){   
    global $conn,$db;
    $arry = [];
    $arry += ['title' => $array['title']];
    $arry += ['post_type' => $array['post_type']];

          // permalink area
    if(isset($array['permalink'])){ 
        if(empty($array['permalink'])){ $permalink = $array['title']; }else{$permalink = $array['permalink']; }
        global $db;
        $permalink = str_replace(' ', '-', $permalink);
        $permalink = strtolower($permalink);
        $fetch = $db->fetch_all('post', 'permalink="'.$permalink.'"');
        if(count($fetch) != 0){
            $array['permalink'] = $fetch[0]['permalink'].'-'.IQ_random_generator('10');
        }else{
            $array['permalink'] = $permalink;
        }
        $arry += ['permalink' => htmlspecialchars($array['permalink'])];
     }
    //  permalink area end

        
       // author id
       if(!isset($array['author_id']) || isset($array['author_id']) && empty($array['author_id'])){     $arry += ['author_id' => loggedinuser('return_id')] ;}else{ $arry += ['author_id' => $array['author_id']] ; }
       

    if(isset($array['content'])){ $arry += ['content' => htmlspecialchars($array['content'])];}
    if(isset($array['status'])){ $arry += ['status' => $array['status']];}
    if(isset($array['layout'])){ $arry += ['layout' => $array['layout']]; }
    if(isset($array['featured_img'])){ $arry += ['featured_img' => $array['featured_img']]; }
    if(isset($array['parent'])){ $arry += ['parent' => $array['parent']]; }
    if(isset($array['post_excerpt'])){ $arry += ['post_excerpt' => $array['post_excerpt']]; }
    if(isset($array['comment_status'])){ $arry += ['comment_status' => $array['comment_status']]; }
    if(isset($array['ping_status'])){ $arry += ['ping_status' => $array['ping_status']]; }
    if(isset($array['post_password'])){ $arry += ['post_password' => $array['post_password']]; }
    if(isset($array['pinged'])){ $arry += ['pinged' => $array['pinged']]; }
    if(isset($array['post_content_filtered'])){ $arry += ['post_content_filtered' => $array['post_content_filtered']]; }
    if(isset($array['guid'])){ $arry += ['guid' => $array['guid']]; }
    if(isset($array['menu_order'])){ $arry += ['menu_order' => $array['menu_order']]; }
    if(isset($array['post_mime_type'])){ $arry += ['post_mime_type' => $array['post_mime_type']]; }
    if(isset($array['comment_count'])){ $arry += ['comment_count' => $array['comment_count']]; }

    
        $arry += ['post_date'=> IQ_date_time('Y-m-d H:i:s')];

        insertdata($arry, 'post');  
        $insert_id = $conn->insert_id;
    //  insert data to post_meta
    if(isset($array['post_meta'])){
        $result=$array['post_meta'];
        foreach($result as $post_meta=>$val){
            if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }
                $meta_data = [];
                $meta_data += ['post_id' => $insert_id];
                $meta_data += ['meta_key' => $post_meta];
                $meta_data += ['meta_value' => $val];
                insertdata($meta_data, 'post_meta'); 
           
        }
    }
    

    return $insert_id;
    
}

// update
// $arry = array(
//     'id'=>'', required
//     'title'=>'', optional
//     'post_type'=>'', optional
//     'content'=>'', optional
//     'status'=>'', optional
//     'layout'=>'', optional
//     'featured_img'=>'', optional
//     'post_meta'=>array()  options
// );

function IQ_post_update($array){
    $status = 1;
    $arry = [];
    $id = $array['id'];
    if(isset($array['title'])){ $arry += ['title' => htmlspecialchars($array['title'])];}
    if(isset($array['post_type'])){ $arry += ['post_type' => htmlspecialchars($array['post_type'])];}
    $arry += ['id' => htmlspecialchars($array['id'])];


        // permalink area
       if(isset($array['permalink'])){ 
        if(empty($array['permalink'])){ $permalink = $array['title']; }else{$permalink = $array['permalink']; }
        global $db;
        $permalink = str_replace(' ', '-', $permalink);
        $permalink = strtolower($permalink);
        $fetch = $db->fetch_all('post', 'permalink="'.$permalink.'"');
        if(count($fetch) != 0){
            if($permalink == $fetch[0]['permalink']){
                $data['permalink'] = $fetch[0]['permalink'];
            }else{
                $array['permalink'] = $fetch[0]['permalink'].'-'.IQ_random_generator('10');
            }
            
        }else{
            $data['permalink'] = $permalink;
        }

        if(isset($array['permalink'])){ $arry += ['permalink' => htmlspecialchars($array['permalink'])];}
     }
    //  permalink area end

       // author id
       if(!isset($array['author_id']) || isset($array['author_id']) && empty($array['author_id'])){     $arry += ['author_id' => loggedinuser('return_id')] ;}else{ $arry += ['author_id' => $array['author_id']] ; }


    if(isset($array['content'])){ $arry += ['content' => htmlspecialchars($array['content'])];}
    if(isset($array['status'])){ $arry += ['status' => $array['status']];}
    if(isset($array['layout'])){ $arry += ['layout' => $array['layout']]; }
    if(isset($array['featured_img'])){ $arry += ['featured_img' => $array['featured_img']]; }
    if(isset($array['parent'])){ $arry += ['parent' => $array['parent']]; }
    if(isset($array['post_excerpt'])){ $arry += ['post_excerpt' => $array['post_excerpt']]; }
    if(isset($array['comment_status'])){ $arry += ['comment_status' => $array['comment_status']]; }
    if(isset($array['ping_status'])){ $arry += ['ping_status' => $array['ping_status']]; }
    if(isset($array['post_password'])){ $arry += ['post_password' => $array['post_password']]; }
    if(isset($array['pinged'])){ $arry += ['pinged' => $array['pinged']]; }
    if(isset($array['post_content_filtered'])){ $arry += ['post_content_filtered' => $array['post_content_filtered']]; }
    if(isset($array['guid'])){ $arry += ['guid' => $array['guid']]; }
    if(isset($array['menu_order'])){ $arry += ['menu_order' => $array['menu_order']]; }
    if(isset($array['post_mime_type'])){ $arry += ['post_mime_type' => $array['post_mime_type']]; }
    if(isset($array['comment_count'])){ $arry += ['comment_count' => $array['comment_count']]; }

        updatedata($arry,'id='.$array['id'], 'post');  
        $status = 1;
    //  insert data to post_meta
    
    // post meta area
    if(isset($array['post_meta'])){

    $result=$array['post_meta'];
  
    foreach($result as $post_meta=>$val){
        if(is_array($val) == 1){ $val = htmlentities(serialize($val)); }

        $meta_data = [];
        $meta_data += ['meta_value' => $val];
        $fetch = fetch('post_meta','post_id='.$id.' and meta_key="'.$post_meta.'"');
        if(empty($fetch)){
                $meta_data += ['post_id'=>$id];
                $meta_data += ['meta_key'=>$post_meta];
            insertdata($meta_data, 'post_meta'); 
        }else{
            updatedata($meta_data, 'post_id='.$id.' and meta_key="'.$post_meta.'"', 'post_meta'); 
        }
    }
    }
    
    return $status;
}
    
// get post
function get_post($data){
    global $db;
    if(isset($data['post_type'])){ $query = 'post_type="'.$data['post_type'].'"';}
    if(isset($data['author_id'])){ $query = 'author_id="'.$data['author_id'].'"';}
    if(isset($data['query'])){ $query = $data['query'];}
    if(isset($data['id'])){ $query = 'id="'.$data['id'].'"';}
    if(isset($data['pagination']) && $data['pagination'] == true){ $pagination = 'pagination';}else{ $pagination = ''; }
    if(isset($data['search']) && $data['search'] == true){ $search = 'search';}else{ $search = ''; }
    if(isset($data['limit']) && !empty($data['limit'])){ $limit = $data['limit'];}else{ $limit = ''; }

    // Post by taxonomy 
    if(isset($data['tax_id']) && !empty($data['tax_id'])){ $tax_query = ['id'=>$data['tax_id']]; }
    if(isset($data['tax_query']) && !empty($data['tax_query'])){ $tax_query = ['query'=>$data['tax_query']]; }


        if(isset($tax_query) && !empty($tax_query)){
        $tax_posts = get_post_by_tax($tax_query);
       
        $i = 0; $dot = ''; $sqldata = '';
        foreach($tax_posts as $tax_ftch){
            $i++;
                if($i % 2 == 0){ $dot = ' or '; }
                $sqldata .=  $dot.'id'.'="'.$tax_ftch['object_id'].'" '; 
            }
            if(empty($sqldata)){ return array(); }
            $query = $sqldata;
    }


    if(isset($data['status']) == true){ $query .= ' and status="publish"';}
    $fetch = $db->fetch_all('post',$query,$pagination,$limit,$search);
    $i = 0;
    foreach($fetch as $post_meta_add){
        $fetch[$i] = [];
        $meta = get_meta('post_meta', 'post_id="'.$post_meta_add['id'].'"');
        $fetch[$i] += array_merge($post_meta_add,$meta);
        $i++;
    }
     return $fetch;
}

// taxonomy
// print_r(get_post(array('tax_query'=>'permalink="music" or permalink="ecommerce"')));

// $data['post_type'] or $data['id']
// $data['pagination']
// $data['search']
// $data['query']

// get_post(array())


// for get single post meta

function get_post_meta($where){
    Global $db;
    
    $fetch = $db->fetch_all('post_meta', $where);
    $i = 0;
    foreach($fetch as $post_meta_add){
        $fetch[$i] = [];
        $meta = get_meta('post_meta', 'post_id="'.$post_meta_add['id'].'"');
        $fetch[$i] += array_merge($post_meta_add,$meta);
        $i++;
    }
     return $fetch;
    
}

// get_post_meta(meta_value)



// <<<<<<<<<<------------   ####    images section ######## -->>>>>>>>>>>>>>>>>>


// $array = array(
//     'media'=>'', required 
//     'dir'=>'', required ;
//     'validation'=>'', optional 
//     'size'=>'', optional 
//     'direction'=>'', optional  (reload or back after success);
//     'post_meta'=>array(array('meta'=>'value'),) or $array[] = [''=>''] or $meta = += [''=>'']  options
// );


// default validation
// 'application/pdf','image/gif', 'image/jpeg', 'image/jpg', 'image/png','image/x-png','audio/midi','audio/mpeg','audio/ogg','video/mp4','video/mpeg','video/ogg'


function IQ_media_insert($array){
    global $conn;
    $file = [$array['media']=>$array['dir']];

    if(!isset($array['validation'])){
    $validation = array('application/pdf','image/gif', 'image/jpeg', 'image/jpg', 'image/png','image/x-png','audio/midi','audio/mpeg','audio/ogg','video/mp4','video/mpeg','video/ogg');
    }

    if(!isset($array['size'])){
        $size = '';
        }
   
    if(!isset($array['direction'])){
    $direction = '';
    }

    filevalidation($file,$validation, $size, $direction);

    // insert images
    $mime_type = $_FILES[$array['media']]['type'];
    $image_name = $_FILES[$array['media']]['name'];
    $post_status = 'inherit';
    $post_type = 'attachment'; 
    
    $images_array = array();
    $images_array['title'] =  $image_name;
    $images_array['featured_img'] =  $image_name;
    $images_array['post_mime_type'] =  $mime_type;
    $images_array['status'] =  'inherit';
    $images_array['post_type'] =  'attachment';
    $images_array['guid'] =  $array['dir'];
    IQ_post_insert($images_array);
    return $conn->insert_id;
}


// get media

// filter by dir / type

function get_media($array){
    
    if(!isset($array['pagination'])){ $array['pagination'] = false; }
    if(!isset($array['limit'])){ $array['limit'] = ''; }
    if(!isset($array['search'])){ $array['search'] = false; }
    if(!isset($array['query']) || isset($array['query']) && empty($array['query'])){ $query = $array['query'] = 'post_type="attachment"'; }else{ $query = $array['query'].' and post_type="attachment"'; }
    if(isset($array['id'])  && !empty($array['id'])){ $query .= 'and id="'.$array['id'].'"'; }
    // filter by mime type
    if(isset($array['filter_type']) &&  !empty($array['filter_type'])){ 

        $images = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png','image/x-png');
                if($array['filter_type'] === 'images' || $array['filter_type'] === 'img'){
                    
                    $i = 0;
                    foreach($images as $imgs){
                        if($i === 0){ $query .= ' and post_mime_type="'.$imgs.'"';  }else{
                            $query .= ' or post_mime_type="'.$imgs.'"'; }
                          $i++;
                    } }

        // audio
        $audio = array('audio/midi','audio/mpeg','audio/ogg');
                if($array['filter_type'] === 'audio'){
                    
                    $i = 0;
                    foreach($audio as $imgs){
                        if($i === 0){ $query .= ' and post_mime_type="'.$imgs.'"';  }else{
                            $query .= ' or post_mime_type="'.$imgs.'"'; }
                          $i++;
                    } }


        // video
        $video = array('video/mp4','video/mpeg','video/ogg');
                if($array['filter_type'] === 'video'){
                    
                    $i = 0;
                    foreach($video as $imgs){
                        if($i === 0){ $query .= ' and post_mime_type="'.$imgs.'"';  }else{
                            $query .= ' or post_mime_type="'.$imgs.'"'; }
                          $i++;
                    } }

        // doc
        $doc = array('application/pdf');
        if($array['filter_type'] === 'doc' || $array['filter_type'] === 'documents'){
            
            $i = 0;
            foreach($doc as $imgs){
                if($i === 0){ $query .= ' and post_mime_type="'.$imgs.'"';  }else{
                    $query .= ' or post_mime_type="'.$imgs.'"'; }
                  $i++;
            } }
                
                
                }

        
        // filter by directory
        if(isset($array['filter_dir']) &&  !empty($array['filter_dir'])){ $query .= ' and guid="'.$array['filter_dir'].'"';  }



        //  $data = $query;

    $data = get_post(array('query'=>$query,'pagination'=>$array['pagination'],'limit'=>$array['limit'], 'search'=>$array['search']));

    return $data; 
   
}


// get media link
// fullpath

function get_media_link($data){
     $all_media = get_media($data);

     $paths = array();
    foreach($all_media as $img){
        $imgpath = $img['guid'].'/'.$img['featured_img'];
        if(isset($data['fullpath']) && $data['fullpath'] === true){
            $paths[] = assets($imgpath,'upload');
        }else{
            $paths[] = $imgpath;
        }

    }

    

    return $paths;
}

// delete media
function del_media($id){
    $img = get_media(array('id'=>$id));
    $img = $img[0];
    if(IQ_filepermission(realpath(UPLOADDIR.$img['guid'].'/'.$img['featured_img'])) === '0644'){
        unlink(realpath(UPLOADDIR.$img['guid'].'/'.$img['featured_img']));
        IQ_post_delete($id);
        return true;
    }else{
        return false;
    }
    
    
}


// taxonomies get terms by taxonomy
function get_terms($data){
    global $db;
    if(!isset($data['pagination'])){ $data['pagination'] = ''; }
    if(!isset($data['search'])){ $data['search'] = ''; }
    if(!isset($data['limit'])){ $data['limit'] = ''; }


    $fetch = $db->fetch_all('taxonomy', 'taxonomy="'.$data['taxonomy'].'"');
            if(!empty($fetch)){
                $i = 0; $dot = ''; $sqldata = '';
                foreach($fetch as $ftch){
                    $i++;
                        if($i % 2 == 0){ $dot = ' or '; }
                        $sqldata .=  $dot.'id'.'="'.$ftch['term_id'].'" '; 
                    }
                     
            $rows = $db->fetch_all('terms', $sqldata,$data['pagination'],$data['limit'],$data['search']);
                        
            }else{
                $rows = [];
            }

            return $rows;
}

// fetch terms by id or query or post id
function fetch_terms($data){
    // id or query or post_id
    global $db;
    if(isset($data['id']) && !empty($data['id'])){ $query = 'id="'.$data['id'].'"'; }
    if(isset($data['query']) && !empty($data['query'])){ $query = $data['query']; }

    // fetch via post id
    if(isset($data['post_id']) && !empty($data['post_id'])){
    $post_ids = $db->fetch_all('term_relationships','object_id="'.$data['post_id'].'"');

    if(!empty($post_ids)){
        $i = 0; $dot = ''; $sqldata = '';
        foreach($post_ids as $ftch){
                $i++;
                if($i % 2 == 0){ $dot = ' or '; }
                $sqldata .=  $dot.'id'.'="'.$ftch['term_taxonomy_id'].'" '; 
            }

        $taxonomies = $db->fetch_all('taxonomy',$sqldata);

        if(!empty($taxonomies)){
            $i = 0; $dot = ''; $sqldata = '';
            foreach($taxonomies as $tax_ftch){
                    $i++;
                    if($i % 2 == 0){ $dot = ' or '; }
                    $sqldata .=  $dot.'id'.'="'.$tax_ftch['term_id'].'" '; 
                }
    
                $query = $sqldata;

            }else{
                return array();
            }    


            }else{ return array(); }

    }


    // terms    
    if(!empty($query)) {   
        $fetch = $db->fetch_all('terms',$query);
        $i = 0;
        foreach($fetch as $post_meta_add){
            $fetch[$i] = [];
            $meta = get_meta('terms_meta', 'term_id="'.$post_meta_add['id'].'"');
            $fetch[$i] += array_merge($post_meta_add,$meta);
            $i++;
        }
    }else{
        $fetch = array();
    }
         return $fetch;

}

// echo '<pre>';
// print_r(fetch_terms(array('post_id'=>'33')));



// delete taxonomy by post
function unlink_taxonomy($data){
    global $db;
    if(isset($data['id'])){
        $id = $data['id'];
            // remove all objects tax
            $fetch_relations_post = $db->fetch_all('term_relationships', 'object_id="'.$id.'"');          
            if(!empty($fetch_relations_post)){
            foreach($fetch_relations_post as $relations_post_fetch){
                $tax_term_count = fetch('taxonomy','id="'.$relations_post_fetch['term_taxonomy_id'].'"');
                $tax_term_count['count'] = $tax_term_count['count'] - 1;
                $tax_term_data = ['count'=>$tax_term_count['count']];
                updatedata($tax_term_data, 'term_id='.$tax_term_count['term_id'],'taxonomy');
            }
            deletedata('term_relationships','object_id="'.$id.'"');

        }
    }
}


function get_post_by_tax($data){
        global $db;
        $term_ids = fetch_terms($data);
        $post_ids = [];
        if(!empty($term_ids)){

            $i = 0; $dot = ''; $sqldata = '';
            foreach($term_ids as $term_ftch){
                $i++;
                    if($i % 2 == 0){ $dot = ' or '; }
                    $sqldata .=  $dot.'term_id'.'="'.$term_ftch['id'].'" '; 
                }

        $taxonomy = $db->fetch_all('taxonomy',$sqldata);
     
        $i = 0; $dot = ''; $sqldata = '';
                foreach($taxonomy as $ftch){
                    $i++;
                        if($i % 2 == 0){ $dot = ' or '; }
                        $sqldata .=  $dot.'term_taxonomy_id'.'="'.$ftch['id'].'" '; 
                    }


        $post_ids = $db->fetch_all('term_relationships',$sqldata);
   
        

    }

    return $post_ids;
}
// print_r(get_post_by_tax(array('query'=>'permalink="ecommerce"')));