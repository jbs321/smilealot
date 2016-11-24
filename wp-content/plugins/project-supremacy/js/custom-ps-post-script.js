(function ($) {

    $(document).ready(function(){

        $(document).on('change keyup', '#ps_seo_title', function(e){

            if($(this).val() == ''){
                $('#psseosnippet_title').html($(this).attr('placeholder'));
            }else{
                $('#psseosnippet_title').html($(this).val());
            }

        });

        $(document).on('change keyup', '#ps_seo_desc', function(e){

            $('#psseosnippet .desc .content').html($(this).val());

        });

    });

})(jQuery);