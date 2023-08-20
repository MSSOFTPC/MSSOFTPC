
<?php 

include('include.php');
head('IQ Dashboard');
loginonly('');

?>        

<?php get_sidebar(); ?>
        <main class="content">
<?php get_header(); ?>

<!-- main body start -->
<?php 
$welcome = apply_filter('welcome_dashboard_text','Welcome to '.site_options('return_title'));
?>
        <h1 class="text-center title" style="margin: 50px 0"><?= $welcome; ?></h1>

        <div class="row IQ_dashbard_widget_area">
                <?php 
                        add_action('IQ_register_dashboard_widgets');
                ?>
               
               
        
             
        </div>


    


<?php footer(); ?>