$(function(){if(window.location.hash){var paragraphId=window.location.hash.substr(3);if(paragraphId>0){var clickContext=$("#p-"+paragraphId).find("[action='comments']");clickContext.trigger("click")}else{$("[action='globalComments']").trigger("click")}}});$("body").on("click","[action='comments']",function(){context=$(this).closest(".paragraphHolder");if(context.find(".commentsContent").is(":visible")){$("[action='closeComments']").trigger("click")}else{$(".bubblesComments").hide();$(".commentsCounter").removeClass("activeComment");context.find(".bubblesComments").toggle();return loadParagraphContent(context)}});$("body").on("click","[action='globalComments']",function(){context=$(this).closest(".paragraphHolder");if(context.find(".commentsContent").is(":visible")){context.find("#globalComments").hide();context.find(".commentsContent").first().html("")}else{$(".bubblesComments").hide();$(".commentsCounter").removeClass("activeComment");context.find("#globalComments").toggle();return loadParagraphContent(context)}});$("body").on("click","[action='closeComments']",function(){context=$(this).closest(".paragraphHolder");$(".bubblesComments").hide();$("#globalComments").hide();$(".commentsCounter").removeClass("activeComment");context.find(".commentsContent").html("");context.find("#globalComments").html("")});$("body").on("change keyup keydown paste cut","textarea",function(){$(this).height(0).height(this.scrollHeight)}).find("textarea").change();$("body").on("click","input[action='createComment']",function(e){var context=$(this).closest(".paragraphHolder");createComment(context)});function loadParagraphContent(context){var uuid=context.attr("uuid");var type=context.attr("type");var noParagraph=context.attr("noParagraph");var localLoader=context.find(".ajaxLoader").first();var targetElement=context.find(".commentsContent").first();var targetCounter=context.find(".counterContent").first();var xhrPath=getXhrPath(ROUTE_COMMENTS,"document","comments",RETURN_HTML);return xhrCall(document,{uuid:uuid,type:type,noParagraph:noParagraph},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);targetCounter.html(data["counter"]);fullImgLiquid();commentTextCounter();$("#comment_description").focus()}localLoader.hide()})}function createComment(context){var localLoader=context.find(".formCommentNew").find(".ajaxLoader").first();var targetElement=context.find(".commentsContent").first();var textCount=$(".textCount").text();if(textCount>495||textCount<0){return false}var form=context.find(".formCommentNew").first();var xhrPath=getXhrPath(ROUTE_COMMENT_CREATE,"document","commentNew",RETURN_BOOLEAN);return xhrCall(context,form.serialize(),xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{return loadParagraphContent(context)}localLoader.hide()})}function commentTextCounter(){$("#comment_description").textcounter({type:"character",min:5,max:500,countContainerElement:"div",countContainerClass:"commentCountWrapper",textCountClass:"textCount",inputErrorClass:"error",counterErrorClass:"error",counterText:"Caractères: ",errorTextElement:"div",minimumErrorText:"Minimum: 5 caractères",maximumErrorText:"Maximum: 500 caractères",displayErrorText:true,stopInputAtMaximum:false,countSpaces:true,countDown:true,countDownText:"Caractères restants: ",countExtendedCharacters:true})}
paginatedFunctions[JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS]=debateFollowersListing;paginatedFunctions[JS_KEY_LISTING_USERS_USER_FOLLOWERS]=userFollowersListing;paginatedFunctions[JS_KEY_LISTING_USERS_USER_SUBSCRIBERS]=userSubscribersListing;function listingContentUserFollowers(targetElement,localLoader,uuid){var xhrPath=getXhrPath(ROUTE_USER_LISTING_USER_FOLLOWERS_CONTENT,"user","listingFollowersContent",RETURN_HTML);return xhrCall(document,{uuid:uuid},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);fullImgLiquid()}localLoader.hide()})}function listingContentUserSubscribers(targetElement,localLoader,uuid){var xhrPath=getXhrPath(ROUTE_USER_LISTING_USER_SUBSCRIBERS_CONTENT,"user","listingSubscribersContent",RETURN_HTML);return xhrCall(document,{uuid:uuid},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);fullImgLiquid()}localLoader.hide()})}function lastUserSubscribersListing(targetElement,localLoader,uuid){var xhrPath=getXhrPath(ROUTE_USER_LISTING_LAST_USER_SUBSCRIBERS,"user","lastUserSubscribers",RETURN_HTML);return xhrCall(document,{uuid:uuid},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);fullImgLiquid()}localLoader.hide()})}function lastUserFollowersListing(targetElement,localLoader,uuid){var xhrPath=getXhrPath(ROUTE_USER_LISTING_LAST_USER_FOLLOWERS,"user","lastUserFollowers",RETURN_HTML);return xhrCall(document,{uuid:uuid},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);fullImgLiquid()}localLoader.hide()})}function lastDebateFollowersListing(targetElement,localLoader,uuid){var xhrPath=getXhrPath(ROUTE_USER_LISTING_LAST_DEBATE_FOLLOWERS,"user","lastDebateFollowers",RETURN_HTML);return xhrCall(document,{uuid:uuid},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);fullImgLiquid()}localLoader.hide()})}function userSubscribersListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#userListing .listSubscribers");localLoader=$("#userListing").find(".ajaxLoader").first();uuid=$("#userListing").attr("uuid");var xhrPath=getXhrPath(ROUTE_USER_LISTING_USER_SUBSCRIBERS,"user","userSubscribers",RETURN_HTML);return xhrCall(document,{uuid:uuid,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}function userFollowersListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#userListing .listFollowers");localLoader=$("#userListing").find(".ajaxLoader").first();uuid=$("#userListing").attr("uuid");var xhrPath=getXhrPath(ROUTE_USER_LISTING_USER_FOLLOWERS,"user","userFollowers",RETURN_HTML);return xhrCall(document,{uuid:uuid,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}function debateFollowersListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#userListing .listFollowers");localLoader=$("#userListing").find(".ajaxLoader").first();uuid=$("#userListing").attr("uuid");var xhrPath=getXhrPath(ROUTE_USER_LISTING_DEBATE_FOLLOWERS,"user","debateFollowers",RETURN_HTML);return xhrCall(document,{uuid:uuid,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}
$(function(){stickySidebar()});$("body").on("click","[action='showMenuAllFamily']",function(){$(".answerMenuAllFamily").toggle();$(".answerShowMenuAllFamily").hide();$(".answerHideMenuAllFamily").show()});$("body").on("click","[action='hideMenuAllFamily']",function(){$(".answerMenuAllFamily").toggle();$(".answerHideMenuAllFamily").hide();$(".answerShowMenuAllFamily").show()});