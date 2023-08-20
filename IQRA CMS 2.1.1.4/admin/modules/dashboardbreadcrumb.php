<?php function dashbreadcrumb($path = null, $data = null,$title=null){ 
    $post_type = '';
        if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
            $post_type = '&post_type='.$_GET['post_type']; } 
    if(!isset($path)){        $path = 'action.php';    }
    ?>
    
    <div class="d-flex flex-wrap flex-md-nowrap align-items-center py-4" style="justify-content: space-between">
   
                <div class="d-flex">
                <?php if(isset($title)){ ?>
                        <h3><?= $title.' &nbsp;'; ?></h3>
                        <?php } ?>
             
                    <div class="dropdown">
             
                        <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Action
                        </button>
                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                           
                        <a class="dropdown-item d-flex align-items-center" href="<?php echo site_url('admin', $path);?>">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                              <?php 
                                   echo apply_filter('admin_dashboard_breadcrumb_btn_txt','New');
                              ?>
                            </a>
                            <?php if(isset($data) && is_array($data)){
                            foreach($data as $multidata){
                                 foreach($multidata as $data=>$val){ ?>
                                <a class="dropdown-item d-flex align-items-center" href="<?= $val;?>">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                               <?= $data ;?>
                            </a>

                            <?php }}} ?>
                            
                            <!-- function add data -->
                            <?php add_action('admin_dashboard_breadcrumb'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="breadcrumb-search" style="">
                    
               

                <!-- Search form -->
                <form class="navbar-search form-inline" id="navbar-search-main" method="get">
                <div class="input-group input-group-merge search-bar">
                    <span class="input-group-text" id="topbar-addon">
                        <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="hidden" class="form-control" name="post_type" id="topbarInputIconLeft" value="<?php if(isset($_GET['post_type'])){ echo $_GET['post_type']; }?>" placeholder="Search" aria-label="Search" aria-describedby="topbar-addon">
                    <input type="search" class="form-control" name="search" id="topbarInputIconLeft" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; }?>" placeholder="Search" aria-label="Search" aria-describedby="topbar-addon">
                    <input type="submit" class="form-control" style="display: none" value="submit">
                    </div>
                </form>
                <!-- / Search form -->
                             


                </div>
             
            </div>


<?php } 

// example
function add_admin_dashboard_breadcrumb_menu($array){

    foreach($array as $fetch){
        $name = $fetch['name'];
        $url = $fetch['url'];
        if(!isset($fetch['icon'])){ $fetch['icon'] = '<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>'; }
        $icon = $fetch['icon'];

    echo '<a class="dropdown-item d-flex align-items-center" href="'.$url.'">';
    echo $icon;
    echo $name;
    echo '</a>';
    }
    
}

// example
// add_admin_dashboard_breadcrumb_menu(array(array('name'=> 'link')));


?>