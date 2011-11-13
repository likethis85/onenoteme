
var Api_Onenote = {
	/*
	config: {
		apiHost: 'http://onenote.me/api/',
		contexts: ['page', 'image', 'selection', 'link'],
		titles: ['分享此网页', '分享此图片', '分享此文字', '分享此链接'],
		shareApi: 'share',
		postApi: 'post',
    },*/
	debug: 1,
	config: {
		apiHost: 'http://onenote.me/api/',
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
	windowInit: function() {
		var user = Api_Onenote.userinfo();
        if (user.token) {
            $('#userinfo').html(user.name);
            $('#userinfo, #logout').show();
			$('#main-container').fadeIn('slow');
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
	loadBooks: function(offset, count) {
		var params = [['methods', 'book.getlist'], ['debug', Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('GET', params, true);
		jqXhr.always(function(){
			console.log('always');
		});
		jqXhr.success(function(data){
			console.log(data);
			if (data != '') {
				var tmplBooklist = $('#tmpl-booklist').template();
				$.tmpl(tmplBooklist, data).appendTo('#booklist');
				$('#sidebar').empty().append($('#booklist').clone(true).removeAttr('id'));
				$('#booklist').empty();
			}
			Api_Tip.successShow('请求成功');
		});
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
	},
	loadNotes: function(bookid){
		var params = [['methods', 'note.getlistofbook'], ['bookid', bookid], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('GET', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
			console.log(data);
			var tmplNotelist = $('#tmpl-notelist').template();
            $('#notelist').empty().append($.tmpl(tmplNotelist, data));
            $('#container').empty().append($('#notelist').clone(true).removeAttr('id'));
			$('#notelist').empty();
			Api_Tip.successShow('请求成功');
        });
		jqXhr.fail(function(jqXhr){
			console.log(jqXhr);
		});
	},
	loadNote: function(noteid){
		var params = [['methods', 'note.getone'], ['noteid', noteid], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('GET', params);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
            console.log(data);
            Api_Tip.successShow('请求成功');
        });
        jqXhr.fail(function(jqXhr){
            //console.log(jqXhr);
        });
		
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
		var params = [['methods', 'note.create'], ['title', title], ['content', content], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('POST', params, true);
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
	newCategory: function(){
        var jqXhr = $.get('templates/newbook.html', function(data){}, 'html');
        jqXhr.success(function(data, textStatus, jqXHR){
            $('#container').html(data);
			Api_Tip.successShow('载入成功');
        });
    },
    saveNewCategory: function(){
        var name = $('#book-create input[name=bookname]').val();
        var isdefault = ($('#book-create input:checked[name=default]').attr('checked')) ? 1 : 0;
        var params = [['methods', 'book.create'], ['name', name], ['isdefault', isdefault], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
            console.log(data);
			Api_Onenote.loadBooks();
			var html = data ? '创建成功' : '未知错误';
			Api_Tip.successShow(html);
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
    },
	newTopic: function(){
        var jqXhr = $.get('templates/newbook.html', function(data){}, 'html');
        jqXhr.success(function(data, textStatus, jqXHR){
            $('#container').html(data);
			Api_Tip.successShow('载入成功');
        });
    },
    saveNewTopic: function(){
        var name = $('#book-create input[name=bookname]').val();
        var isdefault = ($('#book-create input:checked[name=default]').attr('checked')) ? 1 : 0;
        var params = [['methods', 'book.create'], ['name', name], ['isdefault', isdefault], ['debug',  Api_Onenote.debug]];
        var jqXhr = Api_Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
            console.log(data);
			Api_Onenote.loadBooks();
			var html = data ? '创建成功' : '未知错误';
			Api_Tip.successShow(html);
        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
    },
	loadAbout: function(){
	    $('#container').load('templates/about.html');
		Api_Tip.successShow('载入成功');
	},
	signup: function() {
		var jqXhr = $.get('templates/signup.html', function(data){}, 'html');
        jqXhr.success(function(data, textStatus, jqXHR){
            $('#top-container').html(data).animate({top: "0px"});
            Api_Tip.successShow('载入成功');
        });
	},
	saveNewUser: function() {
		var name = $('#user-create input[name=username]').val();
		var passwd = $('#user-create input[name=password]').val();
		var email = $('#user-create input[name=email]').val();
		if ($.trim(name).length == 0 || $.trim(passwd).length == 0 || $.trim(email).length == 0) {
			$('#signup-tip').html('<li>用户名、密码、邮箱必须填写。</li>').fadeIn('fast');
			return false;
		}
		else if ($.trim(name).length < 5 || $.trim(name).length > 30) {
			$('#signup-tip').html('<li>用户名长度必须为5－30位。</li>').fadeIn('fast');
            return false;
		}
		else if (!(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test($.trim(email)))) {
			$('#signup-tip').html('<li>邮箱格式不正确。</li>').fadeIn('fast');
            return false;
		}
		
        var params = [['methods', 'user.create'], ['name', name], ['password', hex_md5(passwd)], ['email', email], ['debug',  Api_Onenote.debug]];
		var jqXhr = Api_Onenote.sendRequest('POST', params, true);
        jqXhr.always(function(){
            console.log('always');
        });
        jqXhr.success(function(data){
            console.log(data);
			var html = data ? '创建成功' : '未知错误';
            console.log(html);
			if (data != 0) {
				localStorage.setItem('user', JSON.stringify(data));
				$('#top-container').animate({top: "-500px"});
				Api_Onenote.windowInit();
			}

        });
        jqXhr.fail(function(jqXhr){
            console.log(jqXhr);
        });
	},
	login: function() {
		var jqXhr = $.get('templates/login.html', function(data){}, 'html');
        jqXhr.success(function(data, textStatus, jqXHR){
            $('#top-container').html(data).animate({top: "0px"});
            Api_Tip.successShow('载入成功');
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
        jqXhr.success(function(data){
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
	userShare: function(info, tab){
		var context = Api_Onenote.config.contexts[info.menuItemId - 1];
        var apiUrl = Api_Onenote.config.apiHost + Api_Onenote.config.shareApi;
		
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
				Api_Onenote.showToast('收藏失败', '很抱歉，这些宝贝没有收藏成功！');
			},
			success: function(data){
				if (data == 'error')
				    Api_Onenote.showToast('收藏失败', '很抱歉，这些宝贝没有收藏成功！');
				else
				    Api_Onenote.showToast('收藏成功', '这些宝贝已经属于您的了！');
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
