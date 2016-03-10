// beta
$(function() {
    $("#formReactionUpdate").ajaxForm(options);
    stickySidebar();
});

// 2 tabs in sidebar
$("body").on("click", "[action='showTab']:first-of-type", function() {
    $(this).parents().siblings('.sidebarTabs').hide();
    $(this).parents().siblings('.sidebarTabs.activeTab').show();
    $('.sidebarTabsHeader span').removeClass('activeTabHeader');
    $(this).addClass('activeTabHeader');
});

$("body").on("click", "[action='showTab']:last-of-type", function() {
    $(this).parents().siblings('.sidebarTabs').show();
    $(this).parents().siblings('.sidebarTabs.activeTab').hide();
    $('.sidebarTabsHeader span').removeClass('activeTabHeader');
    $(this).addClass('activeTabHeader');
});


// Save reaction
$("body").on("click", "[action='reactionSave']", function(e) {
    // console.log('*** click reaction save');

    return saveReaction();
});

// Publish reaction
$('body').on('click', "[action='reactionPublish']", function(e){
    // console.log('*** click publish reaction');

    // automatic saving before publish
    $.when(triggerSaveDocument()).done(function(r1) {
        var uuid = $(this).attr('uuid');
        var confirmMsg = "Une fois publié, vous ne pourrez plus modifier votre réponse. Voulez-vous publier votre réponse?";
        smoke.confirm(confirmMsg, function(e) {
            if (e) {
                return publishReaction(uuid);
            }
        }, {
            ok: "Publier",
            cancel: "Annuler"
            // classname: "custom-class",
            // reverseButtons: true
        });
    });
});

// Delete reaction
$('body').on('click', "[action='reactionDelete']", function(e){
    // console.log('*** click delete reaction');

    var xhrPath = getXhrPath(
        ROUTE_REACTION_DELETE,
        'document',
        'reactionDelete',
        RETURN_URL
        );

    var uuid = $(this).attr('uuid');
    var confirmMsg = "Êtes-vous sûr de vouloir supprimer votre brouillon?";
    smoke.confirm(confirmMsg, function(e) {
        if (e) {
            return reactionDelete(uuid);            
        }
    }, {
        ok: "Supprimer",
        cancel: "Annuler"
        // classname: "custom-class",
        // reverseButtons: true
    });
});

//  PHOTO

// Clic bouton upload photo local
$("body").on("click", "[action='fileSelect']", function() {
    // console.log('click file select');
    $("#fileName").trigger('click');
    return false;
});

// Upload simple
$("body").on("change", "#fileName", function() {
    // console.log('change file name');
    $('#formReactionUpdate').submit();
});

// Delete photo
$('body').on('click', "[action='fileDelete']", function(e){
    // console.log('*** click file delete');

    $('.actionSave').removeClass('saved');

    $('#uploadedPhoto').html('');
    $('#reaction_photo_info_file_name').val(null);

    triggerSaveDocument();    
});

