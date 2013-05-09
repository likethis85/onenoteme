
var Api_Waduanzi = {
	debug: 1,
	config: {
		apiHost: 'http://api.waduanzi.com/json',
		apiKey: '4f7710dc0f9ac',
		apiSecret: '68c4e213397c009dfcc149e7fee936f7',
		apiFormat: 'json',
		apiGetCategories: 'category.getlist',
        apiCreatePost: 'post.create',
		apiUserLogin: 'user.login',
		apiUserLogout: 'user.logout'
	},
	windowInit: function() {
		var user = Api_Waduanzi.userinfo();
        if (user.token) {
            console.log('user logined.');
        }
        else {
            Api_Waduanzi.login();
        }
	},
	userinfo: function() {
		var user = localStorage.getItem('user');
        user = JSON.parse(user ? user : '{}');
		return user;
	},
	getRequestUrl: function(method, params) {
		var accessor = {consumerSecret: Api_Waduanzi.config.apiSecret, tokenSecret: '', accessorSecret: ''};
        params.push(['oauth_consumer_key', Api_Waduanzi.config.apiKey]);
        params.push(['format', Api_Waduanzi.config.apiFormat]);
        params.push(['apikey', Api_Waduanzi.config.apiKey]);
        params.push(['timestamp', '183632982']);
        var message = {method: method, action: Api_Waduanzi.config.apiHost, parameters: params};
		
        OAuth.completeRequest(message, accessor);
		//var bs = OAuth.SignatureMethod.getBaseString(message);
		//console.log(bs);
		var parameters = OAuth.SignatureMethod.normalizeParameters(message.parameters);
		var url = OAuth.addToURL(Api_Waduanzi.config.apiHost, parameters) + '&oauth_signature=' + OAuth.getParameter(message.parameters, "oauth_signature");
		return url;
    },
    sendRequest: function(method, params/*, requireLogined*/) {
/*
		if (arguments[2]) {
			var user = Api_Waduanzi.userinfo();
			params.push(['token', user.token]);
		}
*/
		params.apikey = Api_Waduanzi.config.apiKey;
		params.sig = Api_Waduanzi.config.apiKey;
		params.timestamp = '183632982';
		params.token = '894159137';
        var url = Api_Waduanzi.getRequestUrl(method, []);
		var jqXhr = $.ajax({
			url: url,
			data: $.param(params),
			dataType: Api_Waduanzi.config.apiFormat,
			type: method
		});
        return jqXhr;
    },
	showToast: function($title, $text){
		var notification  = webkitNotifications.createNotification('images/48.png', $title, $text);
		notification.ondisplay = function(){setTimeout(function(){notification.cancel();}, 3000);};
		notification.show();
	},
	loadCategories: function(offset, count) {
		var params = [['methods', 'category.getlist'], ['debug', Api_Waduanzi.debug]];
        var jqXhr = Api_Waduanzi.sendRequest('GET', params, true);
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
	createPost: function(data, tab){
		console.log(tab);
		var title = data[0].value;
		var content = data[1].value;
		var tags = data[2].value;
		var pic = data[3].value;
		var channel_id = data[4].value;
		var category_id = data[5].value;
		var pageurl = data[6].value;
		var croptop = data[7].value;
		var cropbottom = data[8].value;
		var waterpos = data[9].value;
		var params = {
			'method': Api_Waduanzi.config.apiCreatePost,
			'title': title,
			'content': content,
			'tags': tags,
			'pic': pic,
			'channel_id': channel_id,
			'category_id': category_id,
			'pageurl': pageurl,
			'padding_top': croptop,
			'padding_bottom': cropbottom,
			'water_position': waterpos,
			'debug': Api_Waduanzi.debug
		};
        var jqXhr = Api_Waduanzi.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.done(function(data){
            console.log(data);
			if (data == 1 && !data.errno) {
				var contents = {
					method: 'closePostPage',
					result: 1
				};
				chrome.tabs.sendRequest(tab.id, contents);
				Api_Waduanzi.showToast('发布成功', '投递成功，感谢您的参与');
			}
			else 
				Api_Waduanzi.showToast('发布出错', '投递出错，感谢您的参与，您可以再试一下');
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
			Api_Waduanzi.showToast('发布出错', '投递出错，感谢您的参与，您可以再试一下');
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
		Api_Waduanzi.login();
	},
	submitLogin: function() {
		var name = $('#login-form input[name=username]').val();
		
		if ($.trim(name).length == 0) return false;
		
        var passwd = $('#login-form input[name=password]').val();
        var params = [['methods', 'user.login'], ['name', name], ['password', hex_md5(passwd)], ['debug',  Api_Waduanzi.debug]];
        var jqXhr = Api_Waduanzi.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.done(function(data){
            console.log(data);
            if (data != 'ERROR') {
				console.log('登录成功');
                localStorage.setItem('user', JSON.stringify(data));
                $('#top-container').animate({top: "-500px"});
                Api_Waduanzi.windowInit();
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
    shareImage: function(info, tab){
        var contents = {
            tab: tab,
            info: info,
            context: 'image',
            method: 'showPostPage'
        };
        var data = $.param(contents);
//      alert(data);
//      console.log(data);
        
        chrome.tabs.sendRequest(tab.id, contents);
    },
	sharePage: function(info, tab){
		
	},
	shareLink: function(info, tab){
		
	}
};
