$(document).ready((function() {
    setTimeout((function() {
        $("input[type=radio]").trigger("change")
    }), 100);
    $("input[type=radio]").change((function() {
        let e = $(".adv_type:checked").val();
        if (e == "Image") {
            $(".image_adv").slideDown();
            $(".google_adv").slideUp()
        } else {
            $(".google_adv").slideDown();
            $(".image_adv").slideUp()
        }
    }))
}));