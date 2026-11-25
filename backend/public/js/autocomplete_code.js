var slug = function(value){
    return value.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');
}
$(function(){
    $('input[data-toggle="autocomplete-code"]:not(.autocomplete-code-processed)').each(function(index, input){
        var $codeInput = $(input);
        $codeInput
            .addClass('autocomplete-code-processed')
            .addClass('text-danger')
        ;
        $codeInput.data('dirty', ($codeInput.val().length>0));
        if(!$codeInput.attr('disabled')){
            var $titleInput = $($codeInput.data('target'));
            $codeInput.on('change keypress keydown keyup', function(e){
                $codeInput.data('dirty', true);
            });
            $titleInput.on('change keypress keydown keyup', function(e){
                if(!$codeInput.data('dirty')){
                    $codeInput.val(slug($titleInput.val()));
                }
            });
        }
    });
});