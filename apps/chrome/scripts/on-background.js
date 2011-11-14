var contextsMenus = {
	apiHost: 'http://onenote.me/api/',
	contexts: ['page', 'image', 'selection', 'link'],
	titles: ['分享此网页到挖段子', '分享此图片到挖段子', '分享此文字到挖段子', '分享此链接到挖段子'],
	methods: [Api_Onenote.sharePage, Api_Onenote.shareImage, Api_Onenote.shareText, Api_Onenote.shareLink]
};

for (var i in contextsMenus.contexts) {
    chrome.contextMenus.create({
        "title": contextsMenus.titles[i],
        "contexts": [contextsMenus.contexts[i]],
        "onclick": contextsMenus.methods[i]
    });
}

chrome.extension.onRequest.addListener(function(request, sender, sendResponse) {
    if (request.method)
		bgMethods[request.method](request, sender, sendResponse);
	else
		console.log('request is invalid');
});

var bgMethods = {
	'shareText': function(request, sender, sendResponse) {
		Api_Onenote.createPost(request.data, request.tab);
	}
};
