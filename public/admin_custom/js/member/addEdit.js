$(document).ready(function () {
    $(".single").select2({ placeholder: "Please Select", allowClear: !0 });
    setTimeout(function () {
		$('.disbaledValue').trigger('change');
	}, 100);
    $('.disbaledValue').on('select2:select', function (e) {
        let selectedValues = $(this).val() || [];
        let newSelection = e.params.data.id;
        if (newSelection === 'Does Not Matter' && selectedValues.length > 1) {
            // Clear others and keep only 'Does Not Matter'
            $(this).val(['Does Not Matter']).trigger('change.select2');
        } else if (newSelection !== 'Does Not Matter' && selectedValues.includes('Does Not Matter')) {
            // Remove 'Does Not Matter' and keep newly selected
            let updatedValues = selectedValues.filter(val => val !== 'Does Not Matter');
            $(this).val(updatedValues).trigger('change.select2');
        }
    });

    $(".formSubmitBtn").click(function () {
        let e = "#" + $(this).data("formid");
        if (!$(e).valid()) return !1;
        {
            $(this).prop("disabled", !0);
            let i = new FormData($(e)[0]);
            i.append("mode", $("#mode").val()), i.append("id", $("#id").val());
            let t = $("#formUrl").val();
            ajaxRequest($(this), i, t, "responseAddEditMember");
        }
    });


    // Marital Status:
    setTimeout(function () {
        $("#marital_status").trigger("change");
    }, 100),
    $('.total_children').hide();
    $("#marital_status").change(function (e) {
        let t = $("#marital_status :selected").val();
        if (t == "2" || t == "3" || t == "4") {
            $('.total_children').show();
        } else {
            $('.status_children').hide();
            $('.total_children').hide();
            $("#total_children").val("");
        }
    });
    // Total Children
    setTimeout(function () {
        $("#total_children").trigger("change");
    }, 100),
    $("#total_children").change(function (e) {
        let t = $("#total_children :selected").val();
        if (t == "2" || t == "3" || t == "4" || t == "5") {
            $('.status_children').show();
        } else {
            $('.status_children').hide();
            $("#status_children").val("");
        }
    });

    updateMarriedDropdown("#no_of_brother", "#no_of_married_brother", "Brothers");
    updateMarriedDropdown("#no_of_sister", "#no_of_married_sister", "Sisters");
    $("#no_of_brother").trigger("change");
    $("#no_of_sister").trigger("change");
});

function responseAddEditMember(e, i) {
    "success" == i.status
        ? (showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", i.msg, "Success"),
          setTimeout(function () {
              $(e).prop("disabled", !1);
              var t = $(".nav-link.active");
              if (($(t).removeClass("active"), $(t).closest("li").next("li").find("button").length > 0)) {
                  $(t).closest("li").next("li").find("button").addClass("active");
                  var o = $(t).attr("data-bs-target"),
                      a = $(t).closest("li").next("li").find("button").attr("data-bs-target");
                  console.log(a), "add" == i.data.mode && ($("#mode").val("edit"),
                  $("#id").val(i.data.member_id)), $(o).removeClass("active show"),
                  $(a).addClass("active show");
                if(i.data.mode != 'edit'){
                    $('.memberAlertMsg').addClass("d-none");
                    $('.formAdd').removeClass("d-none");
                }
              } else window.location.href = $("#successUrl").val();
          }, 1e3))
        : ($(e).prop("disabled", !1), showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", i.msg, "Failed"));
}

function updateMarriedDropdown(mainSelector, marriedSelector, label) {
    $(mainSelector).on("change", function () {
        const count = $(this).find("option:selected").text().trim();
        const words = ["Zero", "One", "Two", "Three", "Four"];
        const $dropdown = $(marriedSelector);
        $dropdown.empty();

        if (count === "") {
            $dropdown.prop("disabled", true);
            $dropdown.append(`<option value="">Select No Of Married ${label}</option>`);
            return;
        }

        const isFourPlus = count === "4 +";
        const max = isFourPlus ? 4 : parseInt(count);

        $dropdown.prop("disabled", false);
        $dropdown.append(`<option value="">Select No Of Married ${label}</option>`);
        $dropdown.append(`<option value="1">No married ${label.toLowerCase()}</option>`);

        for (let i = 1; i <= max; i++) {
            const word = words[i];
            const suffix =
                i === 1
                    ? label.slice(0, -1).toLowerCase()
                    : label.toLowerCase(); // singular/plural
            const text = `${word} married ${suffix}`;
            $dropdown.append(`<option value="${i+1}">${text}</option>`);
        }

        if (isFourPlus) {
            const text = `Above four married ${label.toLowerCase()}`;
            $dropdown.append(`<option value="6">${text}</option>`);
        }

        const selectedValue = $dropdown.data('value');
        if (selectedValue) {
            $dropdown.val(selectedValue);
        }
    });
}