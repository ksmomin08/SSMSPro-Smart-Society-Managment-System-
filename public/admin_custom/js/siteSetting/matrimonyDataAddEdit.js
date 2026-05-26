$(document).ready(function () {
    // For Language Change:
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
    $('#matrimony_title').val(response.matrimony_title);
    $('#matrimony_subtitle').val(response.matrimony_subtitle);
    $('#matrimony_icon1_text').val(response.matrimony_icon1_text);
    $('#matrimony_icon1_subtitle').val(response.matrimony_icon1_subtitle);
    $('#matrimony_icon2_text').val(response.matrimony_icon2_text);
    $('#matrimony_icon2_subtitle').val(response.matrimony_icon2_subtitle);
    $('#matrimony_icon3_text').val(response.matrimony_icon3_text);
    $('#matrimony_icon3_subtitle').val(response.matrimony_icon3_subtitle);
}