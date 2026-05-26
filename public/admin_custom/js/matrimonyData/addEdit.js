$(document).ready(function () {
    $(".single").select2({
        placeholder: "Please Select",
        allowClear: true
    });

    setTimeout(function () {
        $("input[name=match_type]").trigger("change");
    }, 100),
    $("input[name=match_type]").change(function () {
        let matchType = $("input[name=match_type]:checked").val();
        if(matchType == 1){
            $(".matri_id_groom").show();
            $(".matri_id_bride").show();
        }else{
            $(".matri_id_groom").hide();
            $(".matri_id_bride").hide();
        }
    });

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

function dropdownChangeCom(event, dropdownId) {
    const baseUrl = $("#base_url").val();
    const url = `${baseUrl}/commonRequest/getList`;
    let selectedValue = $(`#${dropdownId}`).val();
    // Map the selected value to the corresponding list type
    if (selectedValue === "Religion") {
        selectedValue = "religion_lists";
    } else if (selectedValue === "Caste") {
        selectedValue = "caste_dropdown";
    } else if (selectedValue === "Mother-Tongue") {
        selectedValue = "mothertongue_lists";
    } else if (selectedValue === "Country") {
        selectedValue = "country_lists";
    } else if (selectedValue === "State") {
        selectedValue = "state_lists";
    } else if (selectedValue === "City") {
        selectedValue = "city_lists";
    }
    // Handle valid and invalid cases
    if (selectedValue) {
        const formData = new FormData();
        formData.append("get_list", selectedValue);
        ajaxRequest("#resultData", formData, url, "getListMatrimonyResponse");
    } else {
        $(`#${event}`).html('<option value="">Select Search Type First</option>');
    }
}

function getListMatrimonyResponse(t, e) {
    "success" == e.status && ($("#matrimony_name").html(e.html), $("#matrimony_name").trigger("refresh"));
}

function ajaxgetLangDataResponce(_this, response){
    $('#lang_id').val(response.lang_id);
    $('#lang_code').val(response.lang_code);
    $('#pagename').val(response.pagename);
    $('#title').val(response.title);
    $('#slug').val(response.slug);
    $('#matrimony_description').val(response.matrimony_description);
    $('#matrimony_name').val(response.matrimony_name);
    $('#og_title').val(response.og_title);
    // $('#og_image').val(response.og_image);
    $('#og_description').val(response.og_description);
    $('#meta_keyword').val(response.meta_keyword);
    $('#meta_title').val(response.meta_title);
    $('#meta_description').val(response.meta_description);

    if(response.defaultLanguage == response.lang_code){
        $('input[name="match_type"]').removeAttr('disabled');
        $('#matri_id_groom').removeAttr('disabled');
        $('#matri_id_bride').removeAttr('disabled');
    }else{
        $('input[name="match_type"]').attr('disabled', true);
        $('#matri_id_groom').attr('disabled', true);
        $('#matri_id_bride').attr('disabled', true);
    }
    // For Images :
    $('.og_image').attr('src', response.og_image);
    $('.banner_img1').attr('src', response.banner_img1);
    $('.banner_img2').attr('src', response.banner_img2);
}