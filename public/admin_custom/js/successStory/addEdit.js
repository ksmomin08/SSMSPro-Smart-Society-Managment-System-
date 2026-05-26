$(document).ready(function () {
    $('#lang_change').change(function() {
        let selectedOption = $(this).find('option:selected');
        let dataId = selectedOption.data('id');
        let action = selectedOption.data('action');
        let selectedLangCode = selectedOption.val();
        let formData = new FormData();
        formData.append("id", dataId);
        formData.append("langCode", selectedLangCode);
        ajaxRequest($(this), formData, action, "ajaxgetLangDataResponce");
    });
});

function ajaxgetLangDataResponce(_this, response){
    $('#lang_id').val(response.lang_id);
    $('#lang_code').val(response.lang_code);
    $('#bridename').val(response.bridename);
    $('#groomname').val(response.groomname);
    $('#successmessage').html(response.successmessage);
    // For Images :
    if(response.weddingphoto == ''){
        $('.weddingphoto').hide();
    }else{
        $('.weddingphoto').show();
        $('.weddingphoto').attr('src', response.weddingphoto);
    }
}