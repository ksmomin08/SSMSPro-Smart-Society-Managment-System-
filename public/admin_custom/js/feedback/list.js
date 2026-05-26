$(document).ready(function () {
    getAjaxPaginationData();

    $("body").on("click", ".closeFilterPopup", function() {
        $("#filterModal").modal("hide")
    }),
    $(".getfilterModal").click(function() {
        let e = $(this).attr("data-action"),
            t = new FormData;
        1 == $("#isFilterApply").length && 1 == $("#isFilterApply").val() ? $("#filterModal").modal("show") : ajaxRequest($(this), t, e, "getFilterFormResponse")
    }), $(document).on("submit", "#filterForm", function(e) {
        e.preventDefault(), $("#isFilterApply").val(1), getAjaxPaginationData(), setTimeout(function() {
            setTimeout(function() {
                $("#filterModal").modal("hide")
            }, 100)
        }, 100)
    })
});

function getFilterFormResponse(e, t) {
    "success" == t.status ? (
        $("#filterModal").html(t.html),
        $($(e).attr("target-modal")).modal("show"),
        $(".single").select2({
            placeholder: "Please Select"
        })) : ($(e).prop("disabled", !1),
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"))
}

function getAjaxPaginationData() {
    if (1 == $("#isFilterApply").length && 1 == $("#isFilterApply").val()) {
        var a = new FormData($("#filterForm")[0]);
        a.append("isFilterApply", $("#isFilterApply").val())
    } else var a = new FormData;
    // let a = new FormData();
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
    if ($("#memberMatriId").length > 0) {
        a.append("memberMatriId", $("#memberMatriId").val());
    }
    a.append("page", t);
    a.append("conditionColumn", $(".tabClick.active").attr("data-conditionColumn"));
    a.append("conditionVal", $(".tabClick.active").attr("data-conditionVal"));
    let i = $("#ajaxRequestUrl").val();
    ajaxRequest("#resultData", a, i, "ajaxPaginationResponse");
}
function ajaxPaginationResponse(a, e) {
    if (e.status == "success") {
        $(".all_check").prop("checked", false);
        $(a).html(e.html);
    }
    scrollTop("#myTab");
    $.each(e.data.tabCount, function (a, e) {
        $("#" + a)
            .find(".countRecord")
            .text(e);
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
