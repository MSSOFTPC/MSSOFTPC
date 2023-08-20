<?php


// $data['url'];
// $data['rename_file'];
// $data['location_path'];
function filedownloadfromanotherserver($data){
    $ch = curl_init($data['url']);
    if(!isset($data['rename_file'])){ $data['rename_file'] = ''; }
    $file_name = basename($data['url']).$data['rename_file'];
    
    // Save file into file location
    $save_file_loc = $data['location_path'] . $file_name;
    
    // Open file
    $fp = fopen($save_file_loc, 'wb');
    
    // It set an option for a cURL transfer
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // Perform a cURL session
    curl_exec($ch);
    
    // Closes a cURL session and frees all resources
    curl_close($ch);
    
    // Close file
    fclose($fp);
    return 1;

}

// copy full directory
function IQ_directory_copy( $source, $target ) {
  if ( is_dir( $source ) ) {
      @mkdir( $target );
      $d = dir( $source );
      while ( FALSE !== ( $entry = $d->read() ) ) {
          if ( $entry == '.' || $entry == '..' ) {
              continue;
          }
          $Entry = $source . '/' . $entry; 
          if ( is_dir( $Entry ) ) {
            IQ_directory_copy( $Entry, $target . '/' . $entry );
              continue;
          }
          copy( $Entry, $target . '/' . $entry );
      }

      $d->close();
  }else {
      copy( $source, $target );
  }
}


class IQ{
    private $version;

    function __construct(){

    }

    function version(){
        $IQ_update = curl_init();
        curl_setopt($IQ_update,CURLOPT_URL, 'https://iqra.mssoftpc.com/content/themes/iqratheme/function.php');
        curl_setopt($IQ_update,CURLOPT_RETURNTRANSFER,true);
        $arry = array('IQ_latest_version'=> '');
        curl_setopt($IQ_update,CURLOPT_POSTFIELDS,$arry);
        $return = curl_exec($IQ_update);
        return $return;
        curl_close($IQ_update);
    }
} 

$IQ = new IQ();

// Start update IQ CMS

if(isset($_POST['IQ_release_update_auto_submit'])){
    $data['url'] = IQ_data('IQ_update_download');
    $data['location_path'] = CACHEDIR;
    $copiedstatus = filedownloadfromanotherserver($data);
    if( $copiedstatus == 1){

      // maintainance mode
        IQ_site_options_update(array('admin_maintenance_mode'=>1));


        $filepath = realpath(CACHEDIR.IQ_data('update_file_name'));
        $cachedir = CACHEDIR.'IQ_cms_update/';
        
        if(file_exists($filepath)){

            $zip = new ZipArchive;  
            if($zip->open($filepath))  
            {  
             
              if(!empty($zip->getFromName('changelog.json'))){

                if(!file_exists($cachedir)){  mkdir($cachedir); } 

                // extract and delete zip
                $zip->extractTo($cachedir);  
                $zip->close();  
                unlink($filepath);

                
                // get changelog data
                $changelog = file_get_contents($cachedir.'changelog.json');
                $changelog = json_decode($changelog);
                // echo '<pre>';
                // print_r($changelog);

                $cachedir_realpath = realpath($cachedir);
                // update files
                foreach($changelog as $files){

                  // check is this file / folder
          if($files->filetype == 'file'){

                    // check file exist
                    $copyfile = $cachedir_realpath.'/'.$files->filepath;
                    $pastefile = $files->movedtopath;
                    if(file_exists($copyfile)){
                      
                       
                        if(file_exists($pastefile)){
                                unlink($pastefile);
                                if(!copy($copyfile,$pastefile)){
                                  die('Updating error:- '.$pastefile);
                            }
                                
                        }else{
                          if(!copy($copyfile,$pastefile)){
                                die('Updating error:- '.$pastefile);
                          }
                        }
                           
                    }else{
                      IQ_site_options_update(array('admin_maintenance_mode'=>0));
                      die('Updating Failed. '.$copyfile.' File Not Exist in Update');
                    }

          }

          // directory checker
          if($files->filetype == 'folder'){

            // check this is folder
             $foldercache = $cachedir_realpath.'/'.$files->filepath;
             $moveddirectorypath = $files->movedtopath;
            //  echo realpath($foldercache);
             if(file_exists($foldercache)){
               
                 if(file_exists($moveddirectorypath)){
                     IQ_directory_copy($foldercache,$moveddirectorypath);
                     
                         
                 }else{
                   
                      IQ_directory_copy($foldercache,$moveddirectorypath);
                 }
                    
             }else{
              IQ_site_options_update(array('admin_maintenance_mode'=>0));
               die('Updating Failed. '.$foldercache.' <strong> Directory</strong> Not Exist in Update');
             }
            


          }
                  
                 
                }
                // die();


              }

                


                  removeDir($cachedir);
                  
                


            }  
           
       
        
       

        

      // maintainance mode disabled
      
        IQ_site_options_update(array('admin_maintenance_mode'=>0));
        updatedata(array('option_value'=>$IQ->version()), 'option_name="IQ_cms_version"', 'site_options'); 
       IQ_notice('success','Thanks for Update IQRA CMS');
        back('reload');
    }

    }
    

}