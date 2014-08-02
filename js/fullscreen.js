function updateImage()
{
    x = parseInt($("div#page").width());
    y = parseInt($("div#page").height());

    imagepath = "image.php?x=" + x + "&y=" + y;

    $("img#imagegraph").attr("src", imagepath);
}

$(document).ready(function() {
    updateImage();
});

$(window).resize(function() {
    updateImage();
});
