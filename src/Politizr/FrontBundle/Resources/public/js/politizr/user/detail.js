// on document ready
$(function(){
    // @todo html/css height en px > wont work
    $('#profileBio').css({overflow: "hidden", height:"360px" });
    $('#hideCompleteBio').hide();
    $('#showCompleteBio').show();        
});

// show full bio
$("body").on("click", "[action='showCompleteBio']", function() {
    $('#profileBio').css({overflow: "visible", height:"auto" });
    $('#hideCompleteBio').show();
    $('#showCompleteBio').hide();
});

// hide full bio
$("body").on("click", "[action='hideCompleteBio']", function() {
    $('#profileBio').css({overflow: "hidden", height:"360px" });
    $('#hideCompleteBio').hide();
    $('#showCompleteBio').show();
});

    
// toggle confidential info text
$("body").on("mouseover", "[action='confidentialToggle']", function() {
    $(this).next('.confidentialInfo').show();
});

$("body").on("mouseout", "[action='confidentialToggle']", function() {
    $(this).next('.confidentialInfo').hide();   
});
