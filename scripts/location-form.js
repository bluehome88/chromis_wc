jQuery(document).ready(function($) {
    var val = $('#location').validate();

    $(':input, :radio, :checkbox').blur(function(){
        if( $('#location').valid() ){
            $('.btn-primary').removeAttr('disabled');
        }
    });

    $(document).click(function(){
        if( $('#location').valid() ){
            $('.btn-primary').removeAttr('disabled');
        }
    });
    $('.delete').click(function(){
        if( window.confirm('Are you sure you wish to delete this location?') ){
            return true;
        } else {
            return false;
        }
    });
});
