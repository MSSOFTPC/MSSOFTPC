<?php 


function footer(){
    msg(); 
    ?>
    <footer class="bg-white rounded shadow p-5 mb-4 mt-4">
            <p class="mb-0 text-center text-center">
                <?php echo site_options('title').' © '.date('Y').'-'.date('y', strtotime('+1 year')).' | IQRA CMS Version : '.IQ_data('version'); ?></p>       
</footer>
        </main>

    <!-- Core -->
<script src="<?php echo adminassets('@popperjs/core/dist/umd/popper.min.js','vendor') ?>"></script>
<script src="<?php echo adminassets('bootstrap/dist/js/bootstrap.min.js','vendor') ?>"></script>

<!-- Vendor JS -->
<script src="<?php echo adminassets('onscreen/dist/on-screen.umd.min.js','vendor') ?>"></script>

<!-- Slider -->
<script src="<?php echo adminassets('nouislider/distribute/nouislider.min.js','vendor') ?>"></script>

<!-- Smooth scroll -->
<script src="<?php echo adminassets('smooth-scroll/dist/smooth-scroll.polyfills.min.js','vendor') ?>"></script>

<!-- Charts -->
<script src="<?php echo adminassets('chartist/dist/chartist.min.js','vendor') ?>"></script>
<script src="<?php echo adminassets('chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js','vendor') ?>"></script>

<!-- Datepicker -->
<script src="<?php echo adminassets('vanillajs-datepicker/dist/js/datepicker.min.js','vendor') ?>"></script>

<!-- Sweet Alerts 2 -->

<!-- Moment JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->
<script src="<?php echo adminassets('vanillajs-datepicker/dist/js/datepicker.min.js','vendor') ?>"></script>

<!-- Notyf -->

<!-- Simplebar -->
<script src="<?php echo adminassets('simplebar/dist/simplebar.min.js','vendor') ?>"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Volt JS -->
<script src="<?php echo adminassets('volt.js','js') ?>"></script>

<!-- widget scripts -->
<script src="<?php echo adminassets('widget.js','js') ?>"></script>


<!-- new scripts -->
<script src="<?php echo adminassets('login.js', 'js')?>"></script>
<script src="<?php echo adminassets('new.js', 'js')?>"></script>
<script src="<?php echo adminassets('top_admin_bar.js', 'js')?>"></script>
    


     <!-- admin footer -->
<?php    add_action('IQ_admin_footer'); ?>

<!-- ckeditor -->
<script>

    tinymce.init({
      selector: '#IQ_tiny_mce_editor',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect | fullscreen | emoticons | searchreplace | quickbars | preview | code',
      toolbar: 'undo redo | forecolor backcolor | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat | fullscreen | emotions | searchreplace | preview | code',
      tinycomments_mode: 'embedded',
      removed_menuitems: 'newdocument',
      relative_urls : false,
      remove_script_host : false,
      convert_urls : true,
      tinycomments_author: 'Author name',
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ]
    });
    
  </script>
                <!-- end ck editor -->

              

            </body>

</html>
<?php } ?>