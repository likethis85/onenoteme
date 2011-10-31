chrome.contextMenus.create({
    "title": '转发到挖段子',
    "contexts": ['selection'],
    //"onclick": Onenote.uploadPost
	"onclick": function(info, tab){
		chrome.tabs.sendRequest(tab.id, {method:'init'});
	}
});

var bgMethods = {
    'insert_script': function(){alert('insert_script');}
};
chrome.extension.onRequest.addListener(function(request, sender, sendResponse) {
	alert(request.method);
    if (request.method)
       bgMethods[request.method](request, sender, sendResponse);
});


var categories = localStorage.getItem('categories');
console.log(categories);
if (!categories)
    Onenote.getCategories();
