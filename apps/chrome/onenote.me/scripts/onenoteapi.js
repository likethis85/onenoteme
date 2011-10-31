
var Onenote = {
	/*
	config: {
		apiHost: 'http://onenote.com/api/',
		contexts: ['page', 'image', 'selection', 'link'],
		titles: ['分享此网页', '分享此图片', '分享此文字', '分享此链接'],
		shareApi: 'share',
		postApi: 'post',
    },*/
	debug: 1,
	config: {
		apiHost: 'http://onenote.com/api/',
		apiKey: '123',
		apiSecret: '123',
		apiFormat: 'json',
		apiGetBooks: 'book.getlist',
		apiGetOneBooks: 'book.getone',
		apiCreateBook: 'book.create',
		apiDeleteBook: 'book.delete',
		apiGetOneNote: 'note.getone',
		apiGetNotes: 'note.getListOfNote',
        apiCreateNote: 'note.create',
        apiDeleteNote: 'note.delete',
		apiUserLogin: 'user.login',
		apiUserLogout: 'user.logout',
		apiCreateUser: 'user.create'
	},
	uploadPost: function(){
		alert('post page');
	},
	windowInit: function() {
		var user = Onenote.userinfo();
        if (user.token) {
            $('#userinfo').html(user.name);
            $('#userinfo, #logout').show();
			$('#main-container').fadeIn('slow');
        }
        else {
            Onenote.login();
        }
	},
	userinfo: function() {
		var user = localStorage.getItem('user');
        user = JSON.parse(user ? user : '{}');
		return user;
	},
	userLogined: function() {
		var user = Onenote.userinfo();
		return user.token != undefined;
	},
	getRequestUrl: function(method, params) {
		var accessor = {consumerSecret: Onenote.config.apiSecret, tokenSecret: '', accessorSecret: ''};
        params.push(['oauth_consumer_key', Onenote.config.apiKey]);
        params.push(['format', Onenote.config.apiFormat]);
        var message = {method: method, action: Onenote.config.apiHost, parameters: params};
		
        OAuth.completeRequest(message, accessor);
//		var bs = OAuth.SignatureMethod.getBaseString(message);
//		console.log(bs);
		var parameters = OAuth.SignatureMethod.normalizeParameters(message.parameters);
		var url = OAuth.addToURL(Onenote.config.apiHost, parameters) + '&oauth_signature=' + OAuth.getParameter(message.parameters, "oauth_signature");
		return url;
    },
    sendRequest: function(method, params/*, requireLogined*/) {
		if (arguments[2]) {
			var user = Onenote.userinfo();
			params.push(['token', user.token]);
		}
		  
        var url = Onenote.getRequestUrl(method, params);
		var jqXhr = $.ajax({
			url: url,
			dataType: Onenote.config.apiFormat,
			type: method
		});
        return jqXhr;
    },
	showToast: function($title, $text){
		webkitNotifications.createNotification('images/48.png', $title, $text).show();
	},
	newPost: function(){
		var jqXhr = $.get('templates/newnote.html', function(data){}, 'html');
		jqXhr.success(function(data, textStatus, jqXHR){
			$('#container').html(data);
			Api_Tip.successShow('载入成功');
		});
	},
	saveNewPost: function(){
		var title = $('#note-create input[name=title]').val();
		var content = $('#note-create textarea[name=content]').val();
		var params = [['methods', 'note.create'], ['title', title], ['content', content], ['debug',  Onenote.debug]];
        var jqXhr = Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
            console.log(data);
			Api_Tip.successShow('保存成功');
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
	},
	login: function() {
		var jqXhr = $.get('templates/login.html', function(data){}, 'html');
        jqXhr.success(function(data, textStatus, jqXHR){
            $('#login-item').html(data);
        });
	},
	logout: function() {
		var user = localStorage.getItem('user');
		user = JSON.parse(user ? user : '{}');
		delete user.token;
		localStorage.setItem('user', JSON.stringify(user));
		$('#main-container').slideUp('fast');
		Onenote.login();
	},
	submitLogin: function() {
		var email = $('#login-form input[name=email]').val();
		
		if ($.trim(email).length == 0) return false;
		
        var passwd = $('#login-form input[name=password]').val();
        var params = [['methods', 'user.login'], ['email', email], ['password', hex_md5(passwd)], ['debug',  Onenote.debug]];
        var jqXhr = Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
            console.log(data);
            if (data != 'ERROR') {
				console.log('登录成功');
                localStorage.setItem('user', JSON.stringify(data));
            }
            else
                console.log('登录失败')
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
			$('#login-tip').html('<li>用户名不存在或密码错误。</li>').fadeIn('fast').delay(5000).fadeOut('fast');
        });
	},
	userShare: function(info, tab){
		var context = Onenote.config.contexts[info.menuItemId - 1];
        var apiUrl = Onenote.config.apiHost + Onenote.config.shareApi;
		
	    var contents = {
			context: context,
	        pageUrl: info.pageUrl,
	        srcUrl: info.srcUrl,
	        selectionText: info.selectionText,
	        linkUrl: info.linkUrl,
			url: tab.url,
			title: tab.title,
			favIconUrl: tab.favIconUrl,
			method: 'showPostPage'
	    };
	    var data = $.param(contents);
		//alert(data);
		
		chrome.tabs.sendRequest(tab.id, contents);

		return ;
		
	    $.ajax({
			url: apiUrl,
			type: 'post',
			dataType: 'text',
			data: data,
			beforeSend: function(xhr, setting){
				
			},
			error: function(xhr, textStatus, errorThrown){
				Onenote.showToast('收藏失败', '很抱歉，这些宝贝没有收藏成功！');
			},
			success: function(data){
				if (data == 'error')
				    Onenote.showToast('收藏失败', '很抱歉，这些宝贝没有收藏成功！');
				else
				    Onenote.showToast('收藏成功', '这些宝贝已经属于您的了！');
			}
		});
	}
};

var Api_Tip = {
	show: function(html){
		$('#on-tip').html(html).fadeIn('fast');
		return this;
	},
	successShow: function(html){
		$('#on-tip').css('color', 'green').html(html).fadeIn('fast');
		return this;
	},
	errorShow: function(html){
		$('#on-tip').css('color', 'red').html(html).fadeIn('fast');
		return this;
	},
	hide: function(delay, duration){
		$('#on-tip').delay(delay).fadeOut(duration);
		return this;
	},
	empty: function(){
		$('#on-tip').empty();
		return this;
	}
};
