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
    $('#browse_sec_title').val(response.browse_sec_title);
    $('#browse_sec_1').val(response.browse_sec_1);
    $('#browse_sec_2').val(response.browse_sec_2);
    $('#browse_sec_3').val(response.browse_sec_3);
    $('#browse_sec_4').val(response.browse_sec_4);
    $('#browse_sec_5').val(response.browse_sec_5);
    $('#browse_sec_label').val(response.browse_sec_label);
}