
<?php include('include.php');
head('IQ Update Management');
loginonly('');

?>        

<?php get_sidebar(); ?>
        <main class="content">
<?php get_header(); ?>

<!-- main body start -->
        <h1 class="text-center title" style="margin: 100px 0">Auto Update IQRA CMS</h1>
        <form action="<?php echo site_url('currentURL'); ?>" method="post" style="text-align:center">
                <button type="submit" name="IQ_release_update_auto_submit" class="btn btn-primary IQ_update_btn_submit">Update Now</button>
        </form>
        <div class="IQ_update_notice" style="display: none">
        <?php 
             IQ_admin_notice("Please Don't Refresh or Close This Tab", 'error');
        ?>
        </div>
        <h1 class="text-center title" style="margin: 100px 0">Manual update is available <br> <a href="<?php echo IQ_data('IQ_update_download');?>"  style="color:blue">Click here</a></h1>  


        <script>
                $('.IQ_update_btn_submit').on('click',()=>{
                        $('.IQ_update_notice').show()
                })
        </script>
<?php footer(); ?>