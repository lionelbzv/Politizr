// on document ready
$(function(){
    // $('#profileBio').css({overflow: "hidden", height:"360px" });
    $('#hideCompleteBio').hide();
    // $('#showCompleteBio').show();        
    $('#showCompleteBio').hide();
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
