<?php
// <!-- dashboard widget  -->
// $data['title'];
// $data['header_btn'] = array('link','title');
// $data['function'] = 'function_name';

function IQ_dashboard_widget($data){
?>
<div class="card-col">
<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_widget">
    <!-- header -->
    <div class="card-header d-flex align-items-center">
        <h2 class="fs-5 fw-bold mb-0"><?= $data['title']; ?></h2>
        <?php if(isset($data['header_btn'])){ ?>
        <div class="ms-auto">
            <a class="fw-normal d-inline-flex align-items-center" href="<?= $data['header_btn'][0]; ?>"> <?= $data['header_btn'][1]; ?></a>
        </div>
        <?php } ?>
    </div>

    <!-- body -->
        <div class="card-body">
             <?php call_user_func($data['function']); ?>
        </div>
 

        <!-- end body -->



    </div>
    
    </div>

<?php 

}

// example
// function chat(){
//         echo 'yes';
// }
// IQ_dashboard_widget(array('title'=>'Chat Details','header_btn'=>array('link','Views'), 'function'=>'chat'));





// #####  Popup Widget Define ######

// modalid (required) (target modal);
// function (required) (target function);
// style (optional ) (modal style for width height position)
// class (optional ) (modal class)

function IQ_register_modal($data){ 
    if(!isset($data['style'])){ $data['style'] = ''; }
    if(!isset($data['class'])){ $data['class'] = ''; }
    ?>
    
    <div class="modal fade" id="<?php echo $data['modalid']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered <?php echo $data['class']; ?>" role="document" style="<?php echo $data['style']; ?>">
            <div class="modal-content">
                       
                  <?php call_user_func($data['function']); ?>
            
               
            </div>
        </div>
    </div>
<?php } 


// <!-- example -->
// <!-- function hello(){
//   echo '<span width="1000px;">samar</span>';
// }

// IQ_register_modal(array('modalid'=>'samar','function'=>'hello')); -->

// <!-- // <div class="modal-header">
// // <h2 class="h6 modal-title">Terms of Service</h2>
// // <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
// // </div>
// // <div class="modal-body">
// // <p>With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.</p>
// // <p>The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as
// //     soon as possible of high-risk data breaches that could personally affect them.</p>
// // </div>
// // <div class="modal-footer">
// // <button type="button" class="btn btn-secondary">Accept</button>
// // <button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
// // </div> -->

?>