// on document ready
$(function() {
    stickyDate();
});

// date anchor
$("body").on("change", "[action='goToDate']", function(e) {
    console.log('*** goToDate');
    console.log($('option:selected').val());
    location.hash = $('option:selected').val();
});
