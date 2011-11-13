for (var i in Api_Onenote.config.contexts) {
    chrome.contextMenus.create({
        "title": Api_Onenote.config.titles[i],
        "contexts": [Api_Onenote.config.contexts[i]],
        "onclick": Api_Onenote.userShare
    });
}

/*
chrome.tabs.onRequest.addListener(function(request, sender, sendResponse) {
    if (request.method)
       bgMethods[request.method](request, sender, sendResponse);
});

var bgMethods = {
	
};
*/