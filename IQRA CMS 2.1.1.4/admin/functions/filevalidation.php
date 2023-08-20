<?php 

function filevalidation($title, $acceptable = null, $max_size = null, $direction = null){
if(!isset($direction)){ $direction = 'reload';}else{ $direction = '';}
    
    if(!isset($acceptable) || isset($acceptable) && empty($acceptable)){   $acceptable = array('image/jpeg','image/jpg','image/gif','image/png'); }
    if(!isset($max_size) || isset($max_size) && empty($max_size)){ $max_size = 109145728; }



    foreach($title as $dta => $val){
        // print_r($_FILES[$dta]['type']); die();  // file forment checker

      global $adminurl;

      if(is_array($_FILES[$dta]['name'])){
        
            $count = 0;
            foreach($_FILES[$dta]['name'] as $files){
                $tmp=$_FILES[$dta]['tmp_name'][$count];
                $fileSize = filesize($tmp);   
                $directory = UPLOADDIR.$val;
                if(!is_dir($directory)){ mkdir($directory);  }
                $target_file = $directory.'/'.basename($filename);
                if ($fileSize == 0) { 
                  $_SESSION['error_notice'] = 'File Not Found Please upload File';
                  $_SESSION['error'] = 'File Not Found Please upload File';
                  back($direction);
                  die();
                }

                if ($fileSize > $max_size) { 
                     $_SESSION['error_notice'] = 'File is Too Large';
                     back($direction);
                      die();
                }
        
                if(!in_array($_FILES[$dta]['type'][$count], $acceptable) && (!empty($_FILES[$dta]["type"][$count]))) {
                    $_SESSION['error_notice'] = 'File format not allowed';
                    $_SESSION['error'] = 'File format not allowed';
                        back($direction);
                        die();
                }
                move_uploaded_file($tmp, $target_file);
                $count++;
                return 1;
            }
            
        }else{


        $filename = $_FILES[$dta]['name'];
        $file_tmp = $_FILES[$dta]['tmp_name'];
        $fileSize = filesize($file_tmp);    
        $directory = UPLOADDIR.$val;
        if(!is_dir($directory)){ mkdir($directory);  }
        $target_file = $directory.'/'.basename($filename); 
        if ($fileSize == 0) { 
            $_SESSION['error_notice'] = 'File Not Found Please upload File';
            $_SESSION['error'] = 'File Not Found Please upload File';
            back($direction);
            die();
         }

        if ($fileSize > $max_size) { 
            $_SESSION['error_notice'] = 'File is Too Large';
            $_SESSION['error'] = 'File is Too Large';
            back($direction);
            die();
         }
        

    if(!in_array($_FILES[$dta]['type'], $acceptable) && (!empty($_FILES[$dta]["type"]))) {
        $_SESSION['error_notice'] = 'File format not allowed';
        $_SESSION['error'] = 'File format not allowed';
        back($direction);
            die();
    }

  move_uploaded_file($file_tmp, $target_file);
        }
     
}

}


// example
// $data = ['file_name'=> 'file_path'];
// filevalidation($data, extra acceptable, size);


?>