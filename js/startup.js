function updateImage()
{
    x = parseInt($("div#content").width() * 0.95);
    y = parseInt($("div#content").height() * 0.95);

    imagepath = "image.php?x=" + x + "&y=" + y;

    $("img#imagegraph").attr("src", imagepath);
}

function setPageDivHeight()
{
    h = window.innerHeight - $("div#footer").height();
    $("div#page").css("height", h + "px");
}

function setContentDivHeight()
{
    h = $("div#page").height() - $("div#header").height();
    $("div#content").css("height", h + "px");
}

$(document).ready(function() {
    setPageDivHeight();
    setContentDivHeight();
    updateImage();
});

$(window).resize(function() {
    setPageDivHeight();
    setContentDivHeight();
    updateImage();
});
