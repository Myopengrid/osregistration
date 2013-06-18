$(document).ready(function() {

    $("tbody").sortable({
        // Esse helper eh necessario
        // porque quando arastando
        // uma das table row a row fica
        // com o mesmo tamanho da tabela
        // helper : 'td.handle',
        helper : function(e, tr)
        {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        },
        update: function(e, ui) {
            href = SITE_URL + ADM_URI + 'osregistration/avatars/sort';

            sorted = new Array();
            
            $('table tr.handle').each(function(){
                var avatar_id = $.trim($(this).data('id'));
                sorted.push(avatar_id);
            });
            sorted = sorted.join(',');

            //$.post(href, {_method: 'PUT', csrf_token: CSRF_TOKEN, order: sorted });
            $.ajax({
                type: 'POST',
                url: href,
                data: {_method: 'PUT', csrf_token: CSRF_TOKEN, order: sorted },
                success: function(msg) {

                    $(ui.item[0]).fadeOut('fast').fadeIn('fast');
                }
            });
        }
    });
});