$(document).ready(function () {
    getAjaxPaginationData();

    $("#resultData").on("click", "#addLeaveReply", function () {
        let baseUrl = $("#base_url").val();
        let actionUrl = baseUrl + "/staff/leave/get-edit-data";
        let formData = new FormData();
        formData.append("id", $(this).data("id"));
        // formData.append("punching_type", $(this).data("type"));
        ajaxRequest($(this), formData, actionUrl, 'ajaxLeaveReplyResponse');
    });

    $("body").on("click", "#addAttendanceRemarkSubmit", function () {
        if (!$("body").find("#addAttendanceRemarkForm").valid()) return !1;
        {
            $(this).prop("disabled", true);
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

function ajaxLeaveReplyResponse(element, response) {
    if (response.status === "success") {
        // Update the modal content with the response HTML
        $("#addLeaveReplyModal").html(response.html);
        const targetModal = $(element).attr("target-modal");
        $('#addLeaveReplyModal').modal("show");
    } else {
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}


function getAjaxPaginationData() {
    let a = new FormData();
    let e = $(".tabClick.active").attr("id");
    a.append("activeTab", e);
    if ($("#recordLimit").length > 0 && $("#recordLimit").val() != "" && $("#recordLimit").val() != 0) {
        a.append("limit", $("#recordLimit").val());
    } else {
        a.append("limit", 10);
    }
    let t = 1;
    if ($("#page").length > 0 && $("#page").val() != "" && $("#page").val() != 0) {
        t = $("#page").val();
    }
    if ($("#searchText").val() != "") {
        a.append("searchKeyword", $("#searchText").val());
    }
    a.append("page", t);
    a.append("conditionColumn", $(".tabClick.active").attr("data-conditionColumn"));
    a.append("conditionVal", $(".tabClick.active").attr("data-conditionVal"));
    let c = $("#ajaxRequestUrl").val();
    ajaxRequest("#resultData", a, c, "ajaxPaginationResponse");
}
function ajaxPaginationResponse(_this, response) {
    if (response.status == "success") {
        $(".all_check").prop("checked", false);
        $(_this).html(response.html);
    }
    scrollTop("#myTab");
    $.each(response.data.tabCount, function (key, value) {
        $("#"+key).find(".countRecord").text(value);
    });
}
function ajaxChangeStatusResponse(a, e) {
    if (e.status == "success") {
        $(".all_check").prop("checked", false);
        $(".checkboxId").prop("checked", false);
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", e.msg, "Success");
        setTimeout(function () {
            getAjaxPaginationData();
        }, 1500);
    } else {
        $(".all_check").prop("checked", false);
        $(".checkboxId").prop("checked", false);
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", e.msg, "Failed");
    }
}
