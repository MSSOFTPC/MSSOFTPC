
<?php

class IQ_view_action_global_function {
   private $supports;
   private $query;
   private $fields;
   private $title;
   private $unique_id;
   private $extra_fields;

    function __construct($data){
        $this->query = $data['data']; 
        $this->extra_fields = $data['extra_fields']; 
        $this->fields = explode(',',$data['fields']); 
        $this->title = $data['title']; 
        $this->unique_id = $data['unique_id']; 
        if(isset($data['supports'])){ $this->supports = $data['supports']; }
        

     
    }
     function action(){
       

    }
// end action function
    public function view($data = null){
        global $db;
        
        if(!empty($this->supports)){ $supports = explode(',',$this->supports); }
        

        // extra
        if(!isset($supports) || isset($supports) && in_array('breadcrumb', $supports) ){
        dashbreadcrumb('view.php?post_type=&action=new',''); 
        IQ_admin_notice_show();
        }
        // end extra
        $rows = $this->query;

        echo '<div class="card card-body shadow border-0 table-wrapper table-responsive">';

// action button
        echo ' <div class="d-flex mb-3">
       
        <form action="'.site_url('currentURL').'" method="post" id="bulkActionform">
        <select class="form-select fmxw-200" aria-label="Message select example" name="bulk_action_type">
            <option selected="" value="">Bulk Action</option>
            <option  value="delete" >Delete</option>
        </select>
        <input type="hidden" name="bulk_action_ids" value="">
        <input type="hidden" name="bulk_action_table" value="'.$this->unique_id.'">
        </form>
        <button class="btn btn-sm px-3 btn-secondary ms-3" value="" id="bulkActionBtn">Apply</button>

    </div>';

       echo '
                <table class="table user-table table-hover align-items-center">
                    <thead class="thead-light">';
        echo  '<tr>';
        echo '<th class="border-0 fw-bold rounded-start"><input type="checkbox" class="form-check-input" id="checkAll"></th>';
        
        foreach($this->fields as $fiels_data){
            $fiels_data = str_replace('_',' ',$fiels_data);
            echo '<th class="border-0">'.$fiels_data.'</th>';    
    }

    if(isset($this->extra_fields) && !empty($this->extra_fields) && is_array($this->extra_fields)){
    foreach($this->extra_fields as $extrafiels_data){
        echo '<th class="border-0">'.$extrafiels_data['parent_name'][0].'</th>';    
    }
}

      
        echo '</tr></head><body>';
        if(count($rows) == 0){echo '<td class="border-0 fw-bold text-center" colspan="6"><h1>Data Not Found</h1></td>';}else{
            global $viewdata;
            foreach($rows as $viewdata){ 

                echo '<tr>';
                echo '<td class="border-0 fw-bold"><input type="checkbox" class="form-check-input check" value="'.$viewdata['id'].'"></td>';
           
                foreach($this->fields as $fiels_data){
                    if(isset($viewdata[$fiels_data])){
                    echo '<td class="border-0 fw-bold">'.$viewdata[$fiels_data].'</td>';
                    }else{
                        echo '<td class="border-0 fw-bold"></td>';
                    }
                }

                if(isset($this->extra_fields) && !empty($this->extra_fields) && is_array($this->extra_fields)){
                    foreach($this->extra_fields as $extrafiels_data){
                        $template = str_replace('{id}', $viewdata['id'], $extrafiels_data['parent_name'][1]);
                        echo '<td class="border-0 fw-bold">'.$template.'</td>';
                    }
                }
                
            
               
        }
            
        }

            echo '</tbody></table>';

            // pagination 
    //         echo ' <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
    //                     <nav aria-label="Page navigation example">
    //                         <ul class="pagination mb-0">';
        
    //         $paginationdata = $db->paginationlast();  
    //         print_r($paginationdata);
    //         foreach($paginationdata as $paginationcount){
    //             foreach($paginationcount as $data=>$val){ 
    //                     if(isset($_GET['page'])){$page = $_GET['page'];}else{ $page = 1;}
    //                     if($data == $page){$pagistatus = 'active';}else{$pagistatus = '';} 
    //                      echo    '<li class="page-item '.$pagistatus.'"><a class="page-link" href="'. $val .'">'.$data.'</a></li>';
    //            }}
    //                           echo  ' </ul></nav>';
    //                            $totalcount = $db->paginationlast('total'); 
    //        echo ' <div class="fw-normal small mt-4 mt-lg-0">Showing <b>'. $totalcount[0].'</b> out of <b>'. $totalcount[1].'</b> entries</div>';
        
        
       echo  '</div> </div>  ';




   ?>
   
   <script>
    $("#checkAll").click(function () {
      
    $("input.check").prop('checked', $(this).prop('checked'));
});


$('#bulkActionBtn').on('click', function(){
    if($('input.check:checked').length  != 0){
        if($('#bulkActionform select[name=bulk_action_type]').val() != ''){
        checkeddataid = '';
        for(x=0; x < $('input.check:checked').length; x++){
            checkeddataid += $('input.check:checked')[x].value+',';
        }
      $('#bulkActionform input:hidden[name=bulk_action_ids]').val(checkeddataid);
      $('#bulkActionform').submit()
        }  
}else{
        notyf = new Notyf({ position: { x: 'right', y: 'top', }, types: [{ type: 'error', 
            background: 'red', icon: { className: 'fas fa-comment-dots',  tagName: 'span', color: '#fff' }, dismissible: false }]
        });
        notyf.open({ type: 'error', message: 'Please Select <?= $this->title; ?>' })
    }
    
})
</script>

       
<?php


        
    

    }

    function __destructor(){

      

    }

}


// used parametors
// view function
// $data['supports'] = 's';
// $data['data'] = get_post(array('query'=>''));
// $data['fields'] = 'title,post_type,post_date';


// $extra_menu[] = ['parent_name'=>array('delete', 'View')];
// $extra_menu[] = ['parent_name'=>array('edit', 'edit')];
// $data['extra_fields'] = $extra_menu;
// $data['title'] = 'Contact Form Entries';
// $data['unique_id'] = 'IQcontactformentries';
// {id}


?>






