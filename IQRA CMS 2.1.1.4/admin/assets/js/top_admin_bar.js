// top admin bar
$('#IQ_top_admin_bar .menupop').mouseover((e)=>{
  targetclass =  $(e.target)
  targetclass.closest('.menupop').addClass('hover')
})

$('#IQ_top_admin_bar .menupop').mouseout((e)=>{
    $('#IQ_top_admin_bar .menupop').removeClass('hover')
})


window.addEventListener('offline',()=>{
    $('body').append(`
    <div class="IQ_connection_checker">
  <ul class="">
    <li class="" >
    <div class="IQ_connection_inner">
      <div class="IQ_connection_icon">
      <i class="ri-wifi-off-line"></i>
    </div>
      
      <div class="IQ_connection_text">
            You are currently offline. <span onclick="location.reload()">Refresh</span>
      </div>

      <div class="IQ_connection_close_btn">
        <div aria-label="Close" class="IQ_connection_close_btn_inner " onclick="this.closest('.IQ_connection_checker').remove()">
        <i class="ri-close-line"></i>
              </div>
      </div>
    </div>
  </li>
</ul>
<div>`)
   
// online
window.addEventListener('online',()=>{
    $('body').append(`
    <div class="IQ_connection_checker">
  <ul class="">
    <li class="" >
    <div class="IQ_connection_inner">
      <div class="IQ_connection_icon">
      <i class="ri-wifi-line"></i>
    </div>
      
      <div class="">
            Your connection was restore.
      </div>

      <div class="IQ_connection_close_btn">
        <div aria-label="Close" class="IQ_connection_close_btn_inner " onclick="this.closest('.IQ_connection_checker').remove()">
        <i class="ri-close-line"></i>
              </div>
      </div>
    </div>
  </li>
</ul>
<div>`)

setTimeout(()=>{
        $('.IQ_connection_checker').remove()
},5000)


})


})

// close

