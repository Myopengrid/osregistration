$(document).ready(function() {

    // Do we have an avatar selected
    // alread? if we do set its class
    // to active
    var avatarId = $('#avatar_appearance').val();
    var img = $('img[data-avatar-id="'+avatarId+'"]').parent().addClass("active");


    $('img').click(function(event) {
        event.preventDefault();
        $(".thumbnails li a").removeClass("active");
        $(this).parent().addClass("active");
        $('#avatar_appearance').val($(this).data('avatar-id'));
        return false;
    });
});