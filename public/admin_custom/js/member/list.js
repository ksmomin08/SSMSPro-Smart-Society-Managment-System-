function getFilterFormResponse(e, t) {
    "success" == t.status
        ? ($("#filterModal").html(t.html), $($(e).attr("target-modal")).modal("show"), $(".single").select2({ placeholder: "Please Select" }))
        : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}
function responseAddComment(e, t) {
    $(e).prop("disabled", !1),
        $("#addMemberCommentSubmit").text("Save comments"),
        "success" == t.status
            ? (showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success"),
              $("#addMemberCommentForm")[0].reset(),
              $("#comment").val(""),
              $("#next_followup_date")[0].reset(),
              setTimeout(function () {
                  $("#addMemberCommentSubmit").prop("disabled", !1);
              }, 2e3))
            : ($("#addMemberCommentSubmit").prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}
function ajaxAddCommentResponse(e, t) {
    "success" == t.status ? ($("#add_commentModal").html(t.html), $($(e).attr("target-modal")).modal("show")) : showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed");
}
function ajaxViewCommentResponse(e, t) {
    "success" == t.status ? ($("#view_commentModal").html(t.html), $($(e).attr("target-modal")).modal("show")) : showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed");
}
function getAjaxPaginationData() {
    showLoader();
    if (1 == $("#isFilterApply").length && 1 == $("#isFilterApply").val()) {
        var e = new FormData($("#filterForm")[0]);
        e.append("isFilterApply", $("#isFilterApply").val());
    } else var e = new FormData();
    let t = $(".tabClick.active").attr("id");
    e.append("activeTab", t),
        $("#recordLimit").length > 0 && "" != $("#recordLimit").val() && 0 != $("#recordLimit").val() ? e.append("limit", $("#recordLimit").val()) : e.append("limit", 10),
        $("#orderByList").length > 0 && "" != $("#orderByList").val() && 0 != $("#orderByList").val() ? e.append("order", $("#orderByList").val()) : e.append("order", "id");
    let a = 1;
    $("#page").length > 0 && "" != $("#page").val() && 0 != $("#page").val() && (a = $("#page").val()),
        "" != $("#searchText").val() && e.append("searchKeyword", $("#searchText").val()),
        e.append("page", a),
        e.append("conditionColumn", $(".tabClick.active").attr("data-conditionColumn")),
        e.append("conditionVal", $(".tabClick.active").attr("data-conditionVal"));
    let i = $("#ajaxRequestUrl").val();
    ajaxRequest("#resultData", e, i, "ajaxPaginationResponse");
}
function ajaxPaginationResponse(e, t) {
    hideLoader();
    "success" == t.status && ($(".all_check").prop("checked", !1), $(e).html(t.html)),
        scrollTop("#myTab"),
        $.each(t.data.tabCount, function (e, t) {
            $("#" + e)
                .find(".countRecord")
                .text(t);
        });
}
function ajaxChangeStatusResponse(e, t) {
    "success" == t.status
        ? ($(".all_check").prop("checked", !1),
          $(".checkboxId").prop("checked", !1),
          showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success"),
          setTimeout(function () {
              getAjaxPaginationData();
          }, 1500))
        : ($(".all_check").prop("checked", !1), $(".checkboxId").prop("checked", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}
function responseAssignMember(e, t) {
    $(e).text("Assign Staff"),
        "success" == t.status
            ? ($(".checkboxId").prop("checked", !1),
              showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success"),
              setTimeout(function () {
                  $(e).prop("disabled", !1), getAjaxPaginationData();
              }, 1500))
            : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}
function responseUnassignMember(e, t) {
    $(e).text("Unassign Staff"),
        "success" == t.status
            ? ($(".checkboxId").prop("checked", !1),
              showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success"),
              setTimeout(function () {
                  $(e).prop("disabled", !1), getAjaxPaginationData();
              }, 1500))
            : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}
function responseAssignFranchise(e, t) {
    $(e).text("Assign Franchise"),
        "success" == t.status
            ? ($(".checkboxId").prop("checked", !1),
              showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success"),
              setTimeout(function () {
                  $(e).prop("disabled", !1), getAjaxPaginationData();
              }, 1500))
            : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}
function responseUnassignFranchise(e, t) {
    $(e).text("Unassign Franchise"),
        "success" == t.status
            ? ($(".checkboxId").prop("checked", !1),
              showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success"),
              setTimeout(function () {
                  $(e).prop("disabled", !1), getAjaxPaginationData();
              }, 1500))
            : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed"));
}function responseConfirmationEmail(e, t) {    "success" == t.status ? showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", t.msg, "Success")        : showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.msg, "Failed");}
$(document).ready(function () {
    getAjaxPaginationData(),
    $(".single").select2({ placeholder: "Please Select", allowClear: !0 }),
    $("#orderByList").change(function () {
        "" != $(this).val() && getAjaxPaginationData();
    }),
    $("#assignMember").click(function (e) {
        let t = $(this).attr("data-action"),
            a = new FormData();
        if ($(".checkboxId:checked").length <= 0) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one record to process!", "Failed"), !1;
        if ("" == $("#subAdminId").val()) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select staff to process!", "Failed"), !1;
        $(this).prop("disabled", !0);
        let i = [];
        $(".checkboxId:checked").each(function () {
            i.push($(this).val());
        }),
            a.append("subAdminId", $("#subAdminId").val()),
            a.append("adminType", $("option:selected", "#subAdminId").attr("data-type")),
            a.append("id", i),
            ajaxRequest($(this), a, t, "responseAssignMember");
    }),
    $("#unassignMember").click(function (e) {
        let t = $(this).attr("data-action"),
            a = new FormData();
        if ($(".checkboxId:checked").length <= 0) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one record to process!", "Failed"), !1;
        if ("" == $("#subAdminId").val()) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select staff to process!", "Failed"), !1;
        $(this).prop("disabled", !0);
        let i = [];
        $(".checkboxId:checked").each(function () {
            i.push($(this).val());
        }),
            a.append("subAdminId", $("#subAdminId").val()),
            a.append("adminType", $("option:selected", "#subAdminId").attr("data-type")),
            a.append("id", i),
            ajaxRequest($(this), a, t, "responseUnassignMember");
    }),

    $("#assignFranchise").click(function (e) {
        let t = $(this).attr("data-action"),
            a = new FormData();
        if ($(".checkboxId:checked").length <= 0) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one record to process!", "Failed"), !1;
        if ("" == $("#subAdminFranchiseId").val()) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select franchise to process!", "Failed"), !1;
        $(this).prop("disabled", !0);
        let i = [];
        $(".checkboxId:checked").each(function () {
            i.push($(this).val());
        }),
        a.append("subAdminFranchiseId", $("#subAdminFranchiseId").val()),
        a.append("adminType", $("option:selected", "#subAdminFranchiseId").attr("data-type")),
        a.append("id", i),
        ajaxRequest($(this), a, t, "responseAssignFranchise");
    }),
    $("#unassignFranchise").click(function (e) {
        let t = $(this).attr("data-action"),
            a = new FormData();
        if ($(".checkboxId:checked").length <= 0) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one record to process!", "Failed"), !1;
        if ("" == $("#subAdminFranchiseId").val()) return showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select franchise to process!", "Failed"), !1;
        $(this).prop("disabled", !0);
        let i = [];
        $(".checkboxId:checked").each(function () {
            i.push($(this).val());
        }),
        a.append("subAdminFranchiseId", $("#subAdminFranchiseId").val()),
        a.append("adminType", $("option:selected", "#subAdminFranchiseId").attr("data-type")),
        a.append("id", i),
        ajaxRequest($(this), a, t, "responseUnassignFranchise");
    }),

    $("#resultData,#memberModalDiv").on("click", ".addComment", function () {
        $("." + $(this).data("class")).length > 0 && $("." + $(this).data("class")).remove(), $("#" + $(this).data("removeclass")).modal("hide");
        let e = $(this).data("removeclass");
        setTimeout(function () {
            $("." + e).remove();
        }, 1500);
        let t = new FormData();
        if ("" != $(this).data("id")) {
            if ((t.append("id", $(this).data("id")), void 0 != $(this).data("action") && "" != $(this).data("action"))) {
                let a = $(this).data("action");
                ajaxRequest($(this), t, a, "ajaxAddCommentResponse");
            } else showNotification("top", "right", "danger", "withicon", "fa fa-times", "Something wend wrong!", "Failed");
        }
    }),
    $("#resultData,#memberModalDiv").on("click", ".viewComment", function () {
        $("." + $(this).data("class")).length > 0 && $("." + $(this).data("class")).remove(), $("#" + $(this).data("removeclass")).modal("hide");
        let e = $(this).data("removeclass");
        setTimeout(function () {
            $("." + e).remove();
        }, 1500);
        let t = new FormData();
        if ("" != $(this).data("id")) {
            if ((t.append("id", $(this).data("id")), void 0 != $(this).data("action") && "" != $(this).data("action"))) {
                let a = $(this).data("action");
                ajaxRequest($(this), t, a, "ajaxViewCommentResponse");
            } else showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Something wend wrong!", "Failed");
        }
    }),
    $("body").on("click", "#addMemberCommentSubmit", function () {
        if (!$("body").find("#addMemberCommentForm").valid()) return !1;
        {
            $(this).text(""), $(this).append('<i class="mr-1 fa fa-spin fa-refresh h3 jqvmap-region"></i>').append("Loading..."), $(this).prop("disabled", !0);
            let e = new FormData($("#addMemberCommentForm")[0]),
                t = $("#addMemberCommentForm").attr("action");
            ajaxRequest($(this), e, t, "responseAddComment");
        }
    }),
    $("#memberModalDiv").on("click", ".closeModal", function () {
        $("#" + $(this).data("modal")).modal("hide");
        let e = $(this).data("modal");
        setTimeout(function () {
            $("." + e).remove();
        }, 1500);
    }),
    $("body").on("click", ".closeFilterPopup", function () {
        $("#filterModal").modal("hide");
    }),
    $(".getfilterModal").click(function () {
        let actionUrl = $(this).attr("data-action");
        let formData = new FormData();
        if ($("#isFilterApply").length && $("#isFilterApply").val() == 1) {
            $("#filterModal").modal("show");
        } else {
            ajaxRequest($(this), formData, actionUrl, "getFilterFormResponse");
        }
    }),
    $(document).on("submit", "#filterForm", function (e) {
        e.preventDefault(),
        $("#isFilterApply").val(1),
        getAjaxPaginationData(),
        setTimeout(function () {
            setTimeout(function () {
                $("#filterModal").modal("hide");
            }, 100);
        }, 100);
    }),
    $("body").on("click", ".sendConfirmationEmail", function (e) {	    e.preventDefault();		let i = $(this).data("id");	            let d = new FormData($("#addMemberCommentForm")[0]);		d.append('id',i);            t = $(this).data("action");            ajaxRequest($(this), d, t, "responseConfirmationEmail");        });

    $(document).on("click", ".clearFilter", function() {
        if($('#isFilterApply').val() == 1){
            $('#filterModal').find('form')[0].reset();
            $('#isFilterApply').val(0);
            $('#filterModal').modal('hide');
            getAjaxPaginationData();
        }
    });
});
