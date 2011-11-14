
var Api_Onenote = {
	debug: 1,
	config: {
		apiHost: 'http://onenote.me/api/',
		apiKey: '123',
		apiSecret: '123',
		apiFormat: 'json',
		apiGetCategories: 'category.getlist',
        apiCreateNote: 'post.create',
		apiUserLogin: 'user.login',
		apiUserLogout: 'user.logout',
	},
	windowInit: function() {
		var user = Api_Onenote.userinfo();
        if (user.token) {
            console.log('user logined.');
        }
        else {
            Api_Onenote.login();
        }
	},
	userinfo: function() {
		var user = localStorage.getItem('user');
        user = JSON.parse(user ? user : '{}');
		return user;
	},
	getRequestUrl: function(method, params) {
		var accessor = {consumerSecret: Api_Onenote.config.apiSecret, tokenSecret: '', accessorSecret: ''};
        params.push(['oauth_consumer_key', Api_Onenote.config.apiKey]);
        params.push(['format', Api_Onenote.config.apiFormat]);
        var message = {method: method, action: Api_Onenote.config.apiHost, parameters: params};
		
        OAuth.completeRequest(message, accessor);
		//var bs = OAuth.SignatureMethod.getBaseString(message);
		//console.log(bs);
		var parameters = OAuth.SignatureMethod.normalizeParameters(message.parameters);
		var url = OAuth.addToURL(Api_Onenote.config.apiHost, parameters) + '&oauth_signature=' + OAuth.getParameter(message.parameters, "oauth_signature");
		return url;
    },
    sendRequest: function(method, params/*, requireLogined*/) {
		if (arguments[2]) {
			var user = Api_Onenote.userinfo();
			params.push(['token', user.token]);
		}
		  
        var url = Api_Onenote.getRequestUrl(method, params);
		var jqXhr = $.ajax({
			url: url,
			dataType: Api_Onenote.config.apiFormat,
			type: method
		});
        return jqXhr;
    },
	showToast: function($title, $text){
		webkitNotifications.createNotification('images/48.png', $title, $text).show();
	},
	loadCategories: function(offset, count) {
		var params = [['methods', 'category.getlist'], ['debug', Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('GET', params, true);
		jqXhr.always(function(){
			console.log('always');
		});
		jqXhr.done(function(data){
			console.log(data);
		});
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
	},
	newPost: function(){
		var jqXhr = $.get('templates/newnote.html', function(data){}, 'html');
		jqXhr.done(function(data, textStatus, jqXHR){
			$('#container').html(data);
			//Api_Tip.successShow('载入成功');
		});
	},
	saveNewPost: function(){
		var title = $('#note-create input[name=title]').val();
		var content = $('#note-create textarea[name=content]').val();
		var params = [['methods', 'note.create'], ['title', title], ['content', content], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.done(function(data){
            console.log(data);
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
	},
	login: function() {
		var jqXhr = $.get('templates/login.html', function(data){}, 'html');
        jqXhr.done(function(data, textStatus, jqXHR){
            $('#top-container').html(data).animate({top: "0px"});
        });
	},
	logout: function() {
		var user = localStorage.getItem('user');
		user = JSON.parse(user ? user : '{}');
		delete user.token;
		localStorage.setItem('user', JSON.stringify(user));
		$('#main-container').slideUp('fast');
		Api_Onenote.login();
	},
	submitLogin: function() {
		var name = $('#login-form input[name=username]').val();
		
		if ($.trim(name).length == 0) return false;
		
        var passwd = $('#login-form input[name=password]').val();
        var params = [['methods', 'user.login'], ['name', name], ['password', hex_md5(passwd)], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.done(function(data){
            console.log(data);
            if (data != 'ERROR') {
				console.log('登录成功');
                localStorage.setItem('user', JSON.stringify(data));
                $('#top-container').animate({top: "-500px"});
                Api_Onenote.windowInit();
            }
            else
                console.log('登录失败')
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
	},
	shareText: function(info, tab){
	    var contents = {
			tab: tab,
	        info: info,
			context: 'selection',
			method: 'showPostPage'
	    };
	    var data = $.param(contents);
//		alert(data);
//		console.log(data);
		
		chrome.tabs.sendRequest(tab.id, contents);
	},
	sharePage: function(info, tab){
		
	},
	shareImage: function(info, tab){
		
	},
	shareLink: function(info, tab){
		
	}
};
