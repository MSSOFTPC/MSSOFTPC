
<?php include('include.php');
if(isset($_GET['page']) && !empty($_GET['page'])){
head('IQ Admin Page');
loginonly('');
?>        

<?php get_sidebar(); ?>
        <main class="content">
<?php get_header(); ?>

<!-- main body start -->

        <?php 
               
        function register_index($title, $slug, $function){
                if($_GET['page'] == $slug){
                    echo '<div style="margin: 20px 0px"><h3>'.$title.'</3></div>';
                        call_user_func($function);
                        global $IQ_register_index_status;
                        $IQ_register_index_status =  true;
                        return $IQ_register_index_status;
                }
        }

       
        add_action('admin_init');
        
        global $IQ_register_index_status;
        if($IQ_register_index_status != true){ back('/admin',''); }


        ?>
  

<?php  footer(); }else{
         if(!isset($_GET['page']) || empty($_GET['page'])){ back('/admin',''); }
} ?>