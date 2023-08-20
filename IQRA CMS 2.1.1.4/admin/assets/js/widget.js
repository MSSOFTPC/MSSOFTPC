$('.IQ_admin_card .card-header').on('click', (e)=> {
    console.log($(e.target))
        $(e.target).siblings('.card-body').toggle(100)
})

// admin 

// sidebar active
$("#sidebarMenu a").each(function() {   
    if (this.href == window.location.href) {
        $(this).addClass('sidebar-active')
        $(this).closest('.collapse').addClass("show");
    }
});