$(document).ready(function () {
    $(".checkLimit").keyup(function () {
        let e = parseInt($(this).attr("maxlength"));
        let a = $(this).val().length + " / " + e + " Remaining Character";
        $("#add-char").text(a);
    });
    $(".getPlanData").change(function () {
        if ($(this).val() != "") {
            let e = new FormData();
            e.append("planId", $(this).val());
            let a = $("#getPlanDataUrl").val();
            ajaxRequest("#resultData", e, a, "ajaxGetPlanResponse");
        } else {
            showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one plan!", "Failed");
            return false;
        }
    });
    $("#planAssignSubmit").click(function () {
        if ($("#plan_id").val() == "" || $("#plan_id").val() == undefined) {
            showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one plan!", "Failed");
            return false;
        }
        if ($("#payment_mode").val() == "" || $("#payment_mode").val() == undefined) {
            showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select payment mode!", "Failed");
            return false;
        }
        if ($("#payment_note").val() == "" || $("#payment_note").val() == undefined) {
            showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please enter payment note!", "Failed");
            return false;
        }
        $(this).text("");
        $(this).append('<i class="mr-1 fa fa-spin fa-refresh h3 jqvmap-region"></i>').append("Loading...");
        $(this).prop("disabled", true);
        var e = new FormData($("#planAssignForm")[0]);
        var a = $("#planAssignForm").attr("action");
        ajaxRequest("#planAssignForm", e, a, "responseAddEdit");
    });
});
function ajaxGetPlanResponse(e, a) {
    if (a.status == "success") {
        $("#resultData").html(a.html);
    }
}
function responseAddEdit(e, a) {
    $("#planAssignSubmit").text("Submit");
    if (a.status == "success") {
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", a.msg, "Success");
        setTimeout(function () {
            $("#planAssignSubmit").prop("disabled", false);
            window.location.href = a.redirect;
        }, 2000);
    } else {
        $("#planAssignSubmit").prop("disabled", false);
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", a.msg, "Failed");
    }
}
