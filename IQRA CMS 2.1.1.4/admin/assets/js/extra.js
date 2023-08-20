function copydata(e){
    copytext = $(e).parent().find('[data-type="copy"]').val();
    navigator.clipboard.writeText(copytext);
    $(e).text('Copied');
    setTimeout(function(){
        $(e).text('Copy');
    },2000)
}

// copydata(this);

// download function


// cart button remove product from cart
$('.cart_remove_single').on('click',function(e){
    productid = $(this).data('id');
    $.ajax({
        type: "POST",
        url: "./admin/function.php",
        data: {
          product_id: productid,
          remove_add_to_cart: "remove_add_to_cart",
        },
        success: function(data){
            // console.log(data);
            location.reload();
        }
      })
})
