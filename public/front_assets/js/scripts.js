// Mobile menu trigger
$(".nav-toggler").on("click", function () {
    $(this).toggleClass("expanded");
    $(".navigation-content").toggleClass("open");
});


$(document).ready(function() {
$(".side-menu").click(function(){
  $('#sidenav').removeClass('d-none');
});
$('.closebtn').click(function(){
  $('#sidenav').addClass('d-none');
});
$('#sidenav').click(function(){
$('#sidenav').addClass('d-none');
});
});