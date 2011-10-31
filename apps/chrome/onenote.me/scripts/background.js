chrome.contextMenus.create({
    "title": '转发到挖段子',
    "contexts": ['selection'],
    "onclick": Onenote.uploadPost
});

//chrome.tabs.onRequest.addListener(function(request, sender, sendResponse) {
//    if (request.method)
//       bgMethods[request.method](request, sender, sendResponse);
//});
//
//var bgMethods = {
//	
//};
