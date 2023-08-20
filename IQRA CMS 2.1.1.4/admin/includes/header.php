<?php function get_header($data = null){
  if(!empty($data)){
    $data = true;
  }
  if($data == false){
  
// top admin bar
IQ_top_admin_bar();




?>

    
    <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
         <div class="container-fluid px-0">
    <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">

       
      </div>
  
    </div>
  </div>
</nav>
<?php } ?>
    <?php }?>