// Mobile menu trigger
$(".nav-toggler").on("click", function () {
    $(this).toggleClass("expanded");
    $(".navigation-content").toggleClass("open");
});