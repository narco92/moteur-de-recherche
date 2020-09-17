function cloud(id) {
    if ($("#" + id).is(":visible")) {
        $("#" + id).hide();
    } else {
        $("#" + id).show();
    }
}