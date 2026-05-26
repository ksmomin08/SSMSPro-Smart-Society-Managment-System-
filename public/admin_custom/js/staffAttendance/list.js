$(document).ready(function () {
    // Initial Data Load
    getAjaxPaginationData();

    $('[data-bs-toggle="tooltip"]').tooltip();
    $(document).on('mouseenter', '[data-bs-toggle="tooltip"]', function () {
        $(this).tooltip('show');
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

    $("#resultData").on("click", "#addEditRemarks", function () {
        let baseUrl = $("#base_url").val();
        let actionUrl = baseUrl + "/staff/attendance/get-edit-data";
        let formData = new FormData();
        formData.append("id", $(this).data("id"));
        formData.append("punching_type", $(this).data("type"));
        ajaxRequest($(this), formData, actionUrl, 'ajaxAddCommentResponse');
    });

    $("body").on("click", "#addAttendanceRemarkSubmit", function () {
        if (!$("body").find("#addAttendanceRemarkForm").valid()) return !1;
        {
            $(this).text(""), $(this).append('<i class="mr-1 fa fa-spin fa-refresh h3 jqvmap-region"></i>').append("Loading..."), $(this).prop("disabled", !0);
            let e = new FormData($("#addAttendanceRemarkForm")[0]),
                t = $("#addAttendanceRemarkForm").attr("action");
            ajaxRequest($(this), e, t, "responseAddRemarks");
        }
    });
});

function responseAddRemarks(_this, response) {
    $(_this).prop("disabled", false);
    $("#addAttendanceRemarkSubmit").text("Save");
    if (response.status === "success") {
        // Show success notification
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
        setTimeout(function () {
            location.reload();
        }, 1000);
    } else {
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}

function ajaxAddCommentResponse(element, response) {
    if (response.status === "success") {
        // Update the modal content with the response HTML
        $("#addRemarksModal").html(response.html);
        const targetModal = $(element).attr("target-modal");
        $('#addRemarksModal').modal("show");
    } else {
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}

function getFilterFormResponse(element, response) {
    if (response.status === "success") {
        $("#filterModal").html(response.html);
        $($(element).attr("target-modal")).modal("show");
        $(".single").select2({ placeholder: "Please Select" });
        // $('#filter_date_range').trigger('change');
        var start = moment().subtract(29, 'days');
        var end = moment();
        $('#filter_date_range').daterangepicker({
            startDate: start,
            endDate: end,
            maxDate: moment(),
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
    } else {
        $(element).prop("disabled", false);
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}

function getAjaxPaginationData() {
    let formData = $("#isFilterApply").length && $("#isFilterApply").val() == 1
        ? new FormData($("#filterForm")[0])
        : new FormData();

    formData.append("activeTab", $(".tabClick.active").attr("id"));
    formData.append("limit", $("#recordLimit").val() || 10);
    formData.append("page", $("#page").val() || 1);
    if ($("#searchText").val()) formData.append("searchKeyword", $("#searchText").val());
    if ($("#memberMatriId").length) formData.append("memberMatriId", $("#memberMatriId").val());
    formData.append("conditionColumn", $(".tabClick.active").attr("data-conditionColumn"));
    formData.append("conditionVal", $(".tabClick.active").attr("data-conditionVal"));

    let requestUrl = $("#ajaxRequestUrl").val();
    ajaxRequest("#resultData", formData, requestUrl, "ajaxPaginationResponse");
}

function ajaxPaginationResponse(element, response) {
    if (response.status === "success") {
        $(".all_check").prop("checked", false);
        $(element).html(response.html);
    }
    scrollTop("#myTab");
    $.each(response.data.tabCount, function (tabId, count) {
        $("#" + tabId).find(".countRecord").text(count);
    });
}

function ajaxChangeStatusResponse(element, response) {
    if (response.status === "success") {
        $(".all_check, .checkboxId").prop("checked", false);
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
        setTimeout(getAjaxPaginationData, 1500);
    } else {
        $(".all_check, .checkboxId").prop("checked", false);
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}
