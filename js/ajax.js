$(document).ready(function () {
    $.ajax({
        url: "process.php?action=getimages", success: function (result) {
            $(".thumbnail_images").html(result);
        }
    });
});

function showcontent(image, element) {
    $("tr").removeClass('active');
    $(element).addClass('active');

    $.ajax({
        url: "process.php?action=individualimage&image=" + image + "", success: function (result) {
            $(".image-details").html(result);
        }
    });
}

function paginate(pagenum) {
    $.ajax({
        url: "process.php?action=getimages&pagenum=" + pagenum, success: function (result) {
            $(".thumbnail_images").html(result);
        }
    });
}


$(document).ready(function () {
    $("form").submit(function (event) {
        var data = $('form').serialize();
        $.ajax({
            type: "POST",
            url: "process.php?action=filter",
            data: data,
            success: function (response) {
                $(".thumbnail_images").html(response);
                $(".image-details").html('<h2 class="mt-5" style="text-align: center"> Click on Images for details</h2>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
        event.preventDefault();
    });
});