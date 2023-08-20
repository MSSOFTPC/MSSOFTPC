// IQ_admin_notice close
$('.notice-dismiss').on('click', function(e){
    $(this).parent('.is-dismissible').remove();
})


// conform tab before close

function IQ_conform_tab(){
    window.onbeforeunload = function(e) {
        return 'Dialog text here.';
     };
}

