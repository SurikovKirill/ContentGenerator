$(document).ready(function (){

    $(".news").fancybox();  

    $('.more').click(function() {
        var rel = $(this).attr("rel");
        location.href = rel;
    });

    $('.delete').click(function () {
        var rel = $(this).attr("rel");
                location.href = rel;
    });

});