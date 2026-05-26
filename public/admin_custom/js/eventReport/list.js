function getAjaxPaginationData() {
    if (1 == $("#isFilterApply").length && 1 == $("#isFilterApply").val())(a = new FormData($("#filterForm")[0])).append("isFilterApply", $("#isFilterApply").val());
    else var a = new FormData;
        e = $(".tabClick.active").attr("id");
    if (
        (a.append("activeTab", e),
        $("#recordLimit").length > 0 && "" != $("#recordLimit").val() && 0 != $("#recordLimit").val() ? a.append("limit", $("#recordLimit").val()) : a.append("limit", 10),
        (t = 1),
        $("#page").length > 0 && "" != $("#page").val() && 0 != $("#page").val())
    )
    if ($("#eventId").length > 0) {
        a.append("eventId", $("#eventId").val())
    }

        var t = $("#page").val();
    "" != $("#searchText").val() && a.append("searchKeyword", $("#searchText").val()),
        a.append("page", t),
        a.append("conditionColumn", $(".tabClick.active").attr("data-conditionColumn")),
        a.append("conditionVal", $(".tabClick.active").attr("data-conditionVal"));
    ajaxRequest("#resultData", a, $("#ajaxRequestUrl").val(), "ajaxPaginationResponse");
}
function getFilterFormResponse(e, t) {
    "success" == t.status ? ($("#filterModal").html(t.html), $($(e).attr("target-modal")).modal("show"), $(".single").select2({
        placeholder: "Please Select"
    })) : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"))
}
function ajaxPaginationResponse(a, e) {
    "success" == e.status && ($(".all_check").prop("checked", !1), $(a).html(e.html)),
        scrollTop("#myTab"),
        $.each(e.data.tabCount, function (a, e) {
            $("#" + a)
                .find(".countRecord")
                .text(e);
        });
}
function ajaxChangeStatusResponse(a, e) {
    "success" == e.status
        ? ($(".all_check").prop("checked", !1),
          $(".checkboxId").prop("checked", !1),
          showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", e.msg, "Success"),
          setTimeout(function () {
              getAjaxPaginationData();
          }, 1500))
        : ($(".all_check").prop("checked", !1), $(".checkboxId").prop("checked", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", e.msg, "Failed"));
}
$(document).ready(function () {
    getAjaxPaginationData();
    $("body").on("click", ".closeFilterPopup", (function() {
        $("#filterModal").modal("hide")
    })), $(".getfilterModal").click((function() {
        let e = $(this).attr("data-action"),
            t = new FormData;
        1 == $("#isFilterApply").length && 1 == $("#isFilterApply").val() ? $("#filterModal").modal("show") : ajaxRequest($(this), t, e, "getFilterFormResponse")
    })), $(document).on("submit", "#filterForm", (function(e) {
        e.preventDefault(), $("#isFilterApply").val(1), getAjaxPaginationData(), setTimeout((function() {
            setTimeout((function() {
                $("#filterModal").modal("hide")
            }), 100)
        }), 100)
    }))
});
