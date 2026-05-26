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
    $.each(response, function(index, value) {
        $('#'+index).val(value);
    });
}