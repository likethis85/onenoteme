//chrome.extension.onRequest.addListener(function(request, sender, sendResponse) {
//    if (request.method)
//	   requestMethods[request.method](request, sender, sendResponse);
//});
//
//var requestMethods = {
//	showPostPage: function(request, sender, sendResponse){
//		var html = '<div id="wabu-post-page" style="width:200px; height:200px; background:gray; position:absolute; top:0px; left:0px;">xx</div>';
//		$('body').append(html);
//		if (request.context)
//		  postPages[request.context](request);
//	}
//};
//
//
//var postPages = {
//	image: function(request){alert(request.pageUrl);},
//	page: function(request){alert(request.pageUrl);},
//	selection: function(request){alert(request.pageUrl);},
//	link: function(request){alert(request.pageUrl);}
//};
