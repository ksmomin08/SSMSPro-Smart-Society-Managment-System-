let selectedType, selectedPlacement, selectedPlacementAllign, toastPlacement;
let toastPlacementExample = document.querySelector(".toast-placement-ex");
$(document).ready(function () {
    $(".select2").select2({ placeholder: "Please Select", allowClear: true });
    let e = "Are you sure to proceed ?";

    // Editor Class
    if ($('.page-editor').length > 0) {
        $('.page-editor').trumbowyg({
            btns: [
                ['viewHTML'],
                ['formatting'],
                ['strong', 'em', 'del'],
                ['foreColor', 'backColor'],
                ['link'],
                ['image'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['superscript', 'subscript'],
                ['emoji'],
                ['fontfamily'],
                ['fontsize'],
                ['historyUndo','historyRedo'],
                ['indent', 'outdent'],
                ['lineheight'],
                ['noembed'],
                ['fullscreen']
            ],
            plugins: {
                fontfamily: {
                    fontList: [
                        {name: 'Arial', family: 'Arial, Helvetica, sans-serif'},
                        {name: 'Comic Sans', family: '\'Comic Sans MS\', Textile, cursive, sans-serif'},
                        {name: 'Open Sans', family: '\'Open Sans\', sans-serif'},
                        {name: 'Poppins', family: '\'Poppins\', sans-serif'},
                        {name: 'Georgia', family: 'Georgia, serif'},
                        {name: 'Impact', family: 'Impact, Charcoal, sans-serif'},
                        {name: 'Tahoma', family: 'Tahoma, Geneva, sans-serif'},
                        {name: 'Times New Roman', family: '\'Times New Roman\', Times, serif'},
                        {name: 'Trebuchet MS', family: '\'Trebuchet MS\', Helvetica, sans-serif'},
                        {name: 'Verdana', family: 'Verdana, Geneva, sans-serif'},
                        {name: 'Courier New', family: '\'Courier New\', Courier, monospace'},
                        {name: 'Lucida Console', family: '\'Lucida Console\', Monaco, monospace'}
                    ]
                }
            },
            semantic: false,
            removeformatPasted: false
        });
    }

    $(document).on("click", ".clearFilter", function() {
        if($('#isFilterApply').val() == 1){
            $('#filterModal').find('form')[0].reset();
            $('#isFilterApply').val(0);
            $('#filterModal').modal('hide');
            getAjaxPaginationData();
        }
    });

    $("body").on("click", ".all_check", function () {
        if ($(this).prop("checked") == true) {
            $(".checkboxId").prop("checked", true);
        } else {
            $(".checkboxId").prop("checked", false);
        }
    });
    $("#notificationUnread").click(function () {
        let e = new FormData();
        $(this).prop("disabled", true);
        let t = $("#base_url").val();
        let a = t + "/notification-read";
        $('.notification_elert').html('0');
        ajaxRequest($(this), e, a, "");
    });
    $("#resultData").on("click", ".checkboxId", function () {
        if ($(this).prop("checked") == true) {
            if ($(".checkboxId:checked").length == $(".checkboxId").length) {
                $(".all_check").prop("checked", true);
            }
        } else {
            $(".all_check").prop("checked", false);
        }
    });
    $(".actionBtn").click(function () {
        let t = new FormData();
        if ($(".checkboxId:checked").length <= 0) {
            showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "Please select at least one record to process!", "Failed");
            return false;
        }
        let a = [];
        $(".checkboxId:checked").each(function () {
            a.push($(this).val());
        });
        t.append($(this).attr("data-column"), $(this).attr("data-value"));
        t.append("id", a);
        let l = $("#changeStatusUrl").val();
        if ($(this).attr("isconfirm") && $(this).attr("isconfirm") == 1) {
            if (confirm(e)) {
                ajaxRequest("#resultData", t, l, "ajaxChangeStatusResponse");
            }
        } else {
            ajaxRequest("#resultData", t, l, "ajaxChangeStatusResponse");
        }
    });
    $("#resultData").on("click", ".ajaxPagination", function (e) {
        e.preventDefault();
        $("#page").val($(this).find("a").attr("data-page"));
        getAjaxPaginationData();
    });
    $("#commonSearch").click(function () {
        $("#page").val(1);
        getAjaxPaginationData();
    });
    $("#searchText").keyup(function (e) {
        if (e.keyCode === 13 || $(this).val() == "") {
            $("#commonSearch").click();
        }
    });
    $("#recordLimit").change(function () {
        if ($(this).val() != "") {
            $("#page").val(1);
            getAjaxPaginationData();
        }
    });
    $(".tabClick").click(function () {
        if (!$(this).hasClass("active")) {
            $(".tabClick").removeClass("active");
            $(this).addClass("active");
            $("#page").val(1);
            getAjaxPaginationData();
        }
    });
    $("#formSubmitBtn").click(function (e) {
        e.preventDefault();
        if ($("#addEditForm").valid()) {
            $("#addEditForm").trigger("submit");
        } else {
            return false;
        }
    });
    $("#generateSiteMap").click(function () {
        let formData = new FormData();
        $(this).prop("disabled", true);
        let baseUrl = $("#base_url").val();
        let action = baseUrl + "/generate-site-map";
        ajaxRequest($(this),formData,action,'ajaxResponseMsg');
    });

    $("#clearCache").click(function () {
        let formData = new FormData();
        $(this).prop("disabled", true);
        let action = $(this).data('action');
        ajaxRequest($(this),formData,action,'ajaxResponseMsg');
    });

    // Sidebar Menu Active
    if ($(".menu-inner").find("li").length > 0) {
        var current = window.location.href.split('?')[0];
        if (current.endsWith('/add') || current.endsWith('/edit') || current.endsWith('/view')) {
            var current = current.substring(0, current.lastIndexOf('/'));
        } else {
            var pathSegments = current.split('/');
            if (pathSegments[pathSegments.length - 2] === 'edit' || pathSegments[pathSegments.length - 2] === 'view') {
                current = current.substring(0, current.lastIndexOf('/' + pathSegments[pathSegments.length - 2]));
            }
        }
        $(".layout-menu ul li a").filter(function () {
            var link = $(this).attr("href").split('?')[0];
            if (current === link) {
                $(this).parents().addClass("active");
                $(this).parents().parents().parents().addClass("open active");
                return false;
            }
        });
    }

    // Image Upload Validation
    $("#homepage_logo2,#upload_logo,#homepage_logo3,#image,#og_image,#upload_white_logo,#upload_default_logo,#upload_favicon").on("change", function (e) {
        e.preventDefault();
        imageValidation($(this));
    });

    // Csv file upload validation
    $('#import_file').on('change', function(e) {
        var fileInput = $(this)[0];
        var file = fileInput.files[0];

        if (!file) {
            var errMsg = 'Please Upload File.';
            alert(errMsg);
            $(this).val('');
            return false;
        }
        var fileExtension = file.name.split('.').pop().toLowerCase();
        if (fileExtension !== 'csv') {
            var errMsg = 'Please upload CSV file.';
            alert(errMsg);
            $(this).val('');
            return false;
        }
    });

    $(".clickPunchIn").click(function () {
        var _formId = '#punchingForm';
        if($(_formId).valid()) {
            let formData = new FormData($(_formId)[0]);
            $(this).prop("disabled", true);
            formData.append('punching_type',$(this).attr('data-type'));
            let baseUrl = $("#base_url").val();
            let action = baseUrl + "/staff/attendance/update-punching";
            ajaxRequest($(this),formData,action,'ajaxResponsePunchingMsg');
        }else{
            return false;
        }
    });
});

function ajaxResponsePunchingMsg(_this, response) {
    $(_this).prop("disabled", false);
    if (response.status == 'success') {
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
        setTimeout(function () {
            location.reload();
        }, 1000);
    }else{
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-check", response.msg, "Success");
    }
}


function checkAuthentication(){
    $('#checkAuthentication').modal('show');
}
$("#authenticationBtn").click(function () {
    if($('#authenticationForm').valid()) {
        $(this).prop('disabled', true);
        var formData = new FormData($('#authenticationForm')[0]);
        var url = $('#authenticationForm').attr('action');
        $(this).text('');
        $(this).append("<div class='spinner-border' role='status'></div>");
        ajaxRequest($(this),formData,url,'responseCheckAuthentication');
    }else{
        return false;
    }
});

function responseCheckAuthentication(_this,response){
	$(_this).prop("disabled", false);
    $(_this).text("Submit");
    if (response.status == 'success') {
        // showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
        $('#checkAuthentication').modal('hide');
        if ($("#addEditForm").valid()) {
            $("#addEditForm").trigger("submit");
        } else {
            showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", 'Something went wrong!', "Failed");
        }
    }else{
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}


function ajaxResponseMsg(_this, response) {
    $(_this).prop("disabled", false);
    if (response.status == 'success') {
        showNotification("top-0", "end-0", "bg-dark", "withicon", "fa fa-check", response.message, "Success")
    }else{
        showNotification("top-0", "end-0", "bg-dark", "withicon", "fa fa-check", response.message, "Success")
    }
}

function ajaxRequest(e, t, a, l = "") {
    t.append("isPost", 1);
    t.append("_token", $("input[name=_token]").val());
    $.ajax({
        url: a,
        type: "POST",
        processData: false,
        contentType: false,
        data: t,
        success: function (t) {
            let a = t;
            if (a.redirectUrl != "" && a.redirectUrl != undefined) {
                window.location.href = a.redirectUrl;
            }
            if (l != "") {
                window[l](e, a);
            }
            setTimeout(function () {
                $("#overlay").fadeOut(300);
            }, 500);
        },
        error: function (t, a, l) {
            $(e).prop("disabled", false);
            if (t.status == 422) {
                showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", "The upload file must be less than 10 MB", a);
            } else {
                showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", t.status + " " + l, a);
            }
        },
    });
}
function scrollTop(e) {
    $("html, body").animate({ scrollTop: $(e).offset().top }, 1e3);
}
function showNotification(e = "top-0", t = "end-0", a = "bg-info", l = "plain", o = "fa fa-bell", c = "", i = "") {
    if (toastPlacement) {
        toastDispose(toastPlacement);
    }
    $(".toast-title").text(i);
    $(".toast-body").text(c);
    selectedType = a;
    selectedPlacement = e;
    selectedPlacementAllign = t;
    toastPlacementExample.classList.add(selectedType);
    toastPlacementExample.classList.add(selectedPlacement);
    toastPlacementExample.classList.add(t);
    toastPlacement = new bootstrap.Toast(toastPlacementExample);
    toastPlacement.show();
}
function toastDispose(e) {
    if (e && e._element !== null) {
        if (toastPlacementExample) {
            toastPlacementExample.classList.remove(selectedType);
            toastPlacementExample.classList.remove(selectedPlacement);
            toastPlacementExample.classList.remove(selectedPlacementAllign);
        }
        e.dispose();
    }
}
function dropdownChange(e, t, a) {
    let l = $("#base_url").val();
    let o = l + "/commonRequest/getList";
    let c = $("#" + e).val();
    if (c != "" && c != null) {
        let e = new FormData();
        e.append("get_list", a);
        e.append("currnet_val", c);
        e.append("disp_on", t);
        ajaxRequest("#resultData", e, o, "getListCommonResponse");
    } else {
        $("#" + t).html('<option value="">Select Value</option>');
    }
}
function getListCommonResponse(e, t) {
    if (t.status == "success") {
        $("#" + t.data.disp_on).html(t.html);
    }
}
function copyToClipboard() {
    let e = $("<input>");
    $("body").append(e);
    let t = $("#copyText1").val();
    e.val(t).select();
    document.execCommand("copy");
    e.remove();
    showNotification("top-0", "end-0", "bg-dark", "withicon", "fa fa-times", "Link Copied SuccessFully", "Success");
}

// Check Two Value Same :
function isSameValue(value1 = '', value2 = '') {
    var returnValue = false;
    if (value1 == value2) {
        returnValue = true;
    }
    return returnValue;
}

function getDashboardData(key='',value=''){
    let formData = new FormData();
    formData.append('dashboardKey',key);
    formData.append('dashboardValue',value);
    let action = $("#base_url").val()+'/dashboard-data';
    ajaxRequest($(this),formData,action,'ajaxChangeStatusResponse');
}

function ajaxChangeStatusResponse(_this, response) {
    if (response.status == 'success') {
        window.location.href = response.redirectUrl;
    }
}

// File Upload Validation
function imageValidation(_this, size = 3 * 1024 * 1024) {
    var fileInput = $(_this);
    var file = fileInput.get(0).files[0];
    if (!file) {
        var errMsg = 'Please Upload File.';
        alert(errMsg);
        $(_this).val('');
        return false;
    }

    var allowedExt = ['image/jpeg', 'image/png', 'image/jpg','image/webp'];
    var maxSize = size;
    if (allowedExt.indexOf(file.type) === -1) {
        var errMsg = 'Please upload a JPG, PNG, JPEG file.';
        alert(errMsg);
        $(_this).val('');
        return false;
    }
    if (file.size > maxSize) {
        var errMsg = 'File size exceeds the maximum limit of 3MB.';
        alert(errMsg);
        $(_this).val('');
        return false;
    }
}

//  Cropping:
var $modal = $('#cropImageModalpop');
var image = document.getElementById('croppingImage');
var cropper;
let isCropped = false;

$("body").on("change", ".crop_image", function(e){
    $('#crop').prop('disabled', false);
    var files = e.target.files;
    croppingImageId = e.target.id;
    croppingImagewidth = parseInt($('#'+croppingImageId).attr('data-width'));
    croppingImageheight = parseInt($('#'+croppingImageId).attr('data-height'));
    var styles = `
    .ctms-prevuisfa .previewImage {
        width: 100% !important;
        overflow: hidden !important;
        height: ${croppingImageheight}px !important;
        max-width: ${croppingImagewidth}px !important;
        max-height: ${croppingImageheight}px !important;
    }
    `;
    $('<style>').prop('type', 'text/css').html(styles).appendTo('head');
    var done = function (url) {
        image.src = url;
        $modal.modal('show');
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
      file = files[0];
      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
});

$modal.on('shown.bs.modal', function () {
    var width = parseInt($('#'+croppingImageId).attr('data-width'));
    var height = parseInt($('#'+croppingImageId).attr('data-height'));
    var aspectRatio = width / height;
    cropper = new Cropper(image, {
      aspectRatio: aspectRatio,
      viewMode: 0,
      preview: '.previewImage',
      cropBoxResizable: false,
      movable: true,
      rotatable: true,
      scalable: true,
      zoomOnWheel: true,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: true,
      cropmove: true,
    });
}).on('click', '.rotateRight', function() {
    cropper.rotate(90);
}).on('click', '.rotateLeft', function() {
    cropper.rotate(-90);
}).on('click', '.zoomIn', function() {
    cropper.zoom(0.1);
}).on('click', '.zoomOut', function() {
    cropper.zoom(-0.1);
}).on('click', '.cropMoveLeft', function() {
    cropper.move(-10,0);
}).on('click', '.cropMoveRight', function() {
    cropper.move(10,0);
}).on('click', '.cropMoveUp', function() {
    cropper.move(0, 10);
}).on('click', '.cropMoveDown', function() {
    cropper.move(0, -10);
}).on('hidden.bs.modal', function () {
    cropper.destroy();
    cropper = null;
    cropper = null;
    if (!isCropped) {
        $('.crop_image').val('');
    }
    isCropped = false;
});

$("#crop").click(function(){
    canvas = cropper.getCroppedCanvas({
        cropWidth : parseInt($('#'+croppingImageId).attr('data-width')),
        cropHeight : parseInt($('#'+croppingImageId).attr('data-height')),
        // fillColor: '#fff',
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    canvas.toBlob(function(blob) {
        var uniqueName = 'crop_img-'+Date.now()+'-'+Math.floor(Math.random()*1000)+'.png';
        var file = new File([blob], uniqueName, { type: blob.type });
        var ImageKey = document.getElementById(croppingImageId);
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        ImageKey.files = dataTransfer.files;
        $(ImageKey).trigger('change');
        isCropped = true;
        $modal.modal('hide');
    });
});
// Cropping

// Admin Left Menu Search :
$('#menuSearch').on('keyup', function() {
    let searchTerm = $(this).val().toLowerCase();
    let menuInner = $('.menu-inner');
    menuInner.scrollTop(0);
    // Show/hide menu headers
    $('.menu-header').toggle(!searchTerm);
    function filterMenu($items) {
        let anyVisible = false;
        $items.each(function() {
            let $item = $(this);
            let $link = $item.children('.menu-link');
            let text = $link.text().toLowerCase();
            let $subItems = $item.children('.menu-sub').children('.menu-item');
            // Recursively filter children first
            let childVisible = filterMenu($subItems);
            // If parent matches OR any child matches
            if (text.includes(searchTerm) || childVisible) {
                $item.show();
                // Show all submenus if parent matches, otherwise only matching children
                if ($subItems.length) {
                    $item.addClass('open');
                    if (text.includes(searchTerm)) {
                        // Show all children if parent matches
                        $subItems.show();
                    } else {
                        // Show only matching children
                        $subItems.each(function() {
                            if ($(this).is(':visible')) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                    $item.children('.menu-sub').show();
                }
                anyVisible = true;
            } else {
                $item.hide();
                if ($subItems.length) {
                    $item.children('.menu-sub').hide();
                    $item.removeClass('open');
                }
            }
        });
        return anyVisible;
    }

    // Start filtering from top-level menu items
    filterMenu($('.menu-inner > .menu-item'));
});

$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();
    $('#filedownloadDate').daterangepicker({
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
});

$(document).ready(function () {
    var $menuInner = $('.menu-inner');
    var $activeItem = $(".menu-item.active");

    if ($activeItem.length) {
        // Smoothly scroll to active menu item
        $menuInner.animate({
            scrollTop: $activeItem.offset().top - $menuInner.offset().top + $menuInner.scrollTop()
        }, 500);
    }
});

function showLoader(){
    $('#adminLoader').show();
}
function hideLoader(){
    $('#adminLoader').hide();
}

// Master Data Language Change:
$(document).ready(function () {
    $('#lang_change_master').change(function() {
        let selectedOption = $(this).find('option:selected');
        let dataId = selectedOption.data('id');
        let tableName = selectedOption.data('tableName');
        let languageColumn = selectedOption.data('languageColumn');
        let selectedLangCode = selectedOption.val();
        let formData = new FormData();
        formData.append("id", dataId);
        formData.append("langCode", selectedLangCode);
        formData.append("tableName", tableName);
        formData.append("languageColumn", languageColumn);

        let baseUrl = $("#base_url").val();
        let action = baseUrl + "/commonRequest/getLangData";
        ajaxRequest($(this), formData, action, "ajaxgetLangDataMasterResponce");
    });
});

function ajaxgetLangDataMasterResponce(_this, response){
    $('#lang_id').val(response.lang_id);
    $('#lang_code').val(response.lang_code);
    $('#'+response.key).val(response.value);
}

// Send Custom Manual Email Event Handlers
$(document).ready(function () {
    $("#resultData").on("click", ".sendCustomEmail", function (e) {
        e.preventDefault();
        let memberId = $(this).attr("data-id");
        let memberName = $(this).attr("data-name");
        let memberEmail = $(this).attr("data-email");
        
        $("#send_email_member_id").val(memberId);
        $("#send_email_member_name").text(memberName);
        $("#send_email_member_email").text(memberEmail);
        
        $("#email_subject").val("");
        $("#email_message").val("");
        
        $("#send_emailModal").modal("show");
    });

    $("body").on("submit", "#sendCustomEmailForm", function (e) {
        e.preventDefault();
        let submitBtn = $("#sendCustomEmailSubmit");
        submitBtn.prop("disabled", true).html('<i class="bx bx-loader-alt bx-spin mr-1"></i> Sending...');
        
        let formData = new FormData(this);
        let actionUrl = $(this).attr("action");
        
        ajaxRequest(submitBtn, formData, actionUrl, "responseSendCustomEmail");
    });
});

function responseSendCustomEmail(submitBtn, response) {
    $(submitBtn).prop("disabled", false).html("<i class='bx bx-send'></i> Send Email");
    if (response.status === "success") {
        $("#send_emailModal").modal("hide");
        showNotification("top-0", "end-0", "bg-success", "withicon", "fa fa-check", response.msg, "Success");
    } else {
        showNotification("top-0", "end-0", "bg-danger", "withicon", "fa fa-times", response.msg, "Failed");
    }
}