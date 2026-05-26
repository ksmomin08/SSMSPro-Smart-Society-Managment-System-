$(document).ready(function () {
    getAjaxPaginationData();

    $("#resultData").on("click", ".sendSalarySlipEmail", function () {
        let baseUrl = $("#base_url").val();
        let actionUrl = baseUrl + "/staff/salary-slips/send-email";
        let formData = new FormData();
        formData.append("id", $(this).data("id"));
        ajaxRequest($(this), formData, actionUrl, 'ajaxSalarySlipResponse');
    });

    // Close Filter Popup
    $("body").on("click", ".closeFilterPopup", function () {
        $("#filterModal").modal("hide");
    });

    // Open Filter Modal
    $(".getfilterModal").click(function () {
        let actionUrl = $(this).attr("data-action"),
            formData = new FormData();

        if ($("#isFilterApply").length && $("#isFilterApply").val() == 1) {
            $("#filterModal").modal("show");
        } else {
            ajaxRequest($(this), formData, actionUrl, "getFilterFormResponse");
        }
    });

    // Submit Filter Form
    $(document).on("submit", "#filterForm", function (event) {
        event.preventDefault();
        $("#isFilterApply").val(1);
        getAjaxPaginationData();
        setTimeout(() => $("#filterModal").modal("hide"), 200);
    });
});

function getFilterFormResponse(element, response) {
    if (response.status === "success") {
        $("#filterModal").html(response.html);
        $($(element).attr("target-modal")).modal("show");
        $(".single").select2({ placeholder: "Please Select" });
    } else {
        $(element).prop("disabled", false);
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}

function ajaxSalarySlipResponse(element, response) {
    if (response.status === "success") {
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
    } else {
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}

function getAjaxPaginationData() {
    let formData = new FormData();
    let activeTab = $(".tabClick.active").attr("id");
    let recordLimit = $("#recordLimit").val();
    let page = $("#page").val();
    let searchText = $("#searchText").val();
    let ajaxRequestUrl = $("#ajaxRequestUrl").val();
    formData.append("activeTab", activeTab);
    formData.append("limit", recordLimit && recordLimit != 0 ? recordLimit : 10);
    formData.append("page", page && page != 0 ? page : 1);
    if (searchText) {
        formData.append("searchKeyword", searchText);
    }
    formData.append("conditionColumn", $(".tabClick.active").attr("data-conditionColumn"));
    formData.append("conditionVal", $(".tabClick.active").attr("data-conditionVal"));
    ajaxRequest("#resultData", formData, ajaxRequestUrl, "ajaxPaginationResponse");
}

function ajaxPaginationResponse(target, response) {
    if (response.status === "success") {
        $(".all_check, .checkboxId").prop("checked", false);
        $(target).html(response.html);
        scrollTop("#myTab");
        $.each(response.data.tabCount, function (id, count) {
            $("#" + id).find(".countRecord").text(count);
        });
    }
}

function ajaxChangeStatusResponse(target, response) {
    $(".all_check, .checkboxId").prop("checked", false);
    if (response.status === "success") {
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
        setTimeout(getAjaxPaginationData, 1500);
    } else {
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}