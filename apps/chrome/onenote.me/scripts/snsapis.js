/**
 * @author Chris
 */
function makeBasicAuthHeader(user, pwd)
{
	return 'Basic ' + Base64.encode(user + ':' + pwd);
}

var OAUTH_CALLBACK = chrome.extension.getURL('callback.html');
var Api_Sinat = {
	config: {
		api_host: 'http://api.t.sina.com.cn',
		format: '.json',
		oauth_key: '1680523446',
		oauth_secret: '412d55077e55d72525f45ff904474a3a',
		source: '1680523446',
		public_timeline: '/statuses/public_timeline',
		friends_timeline: '/statuses/friends_timeline',
		user_timeline: '/statuses/user_timeline',
		mentions: '/statuses/mentions',
		comments_timeline: '/statuses/comments_timeline',
		comments_by_me: '/statuses/comments_by_me',
		comments_to_me: '/statuses/comments_to_me',
		comments: '/statuses/comments',
		counts: '/statuses/counts',
		repost_timeline: '/statuses/repost_timeline',
		repost_by_me: '/statuses/repost_by_me',
		unread: '/statuses/unread',
		reset_count: '/statuses/reset_count'
	},
	public_timeline: function(data, callback) {
		if (!callback) return false;
		var params = {
			url: this.config.public_timeline,
			type: 'GET',
			data: data
		};
		this.request(params, callback);
	},
	apply_auth: function(url, args, user) {
        user.authType = user.authType || 'Basic_Auth';
        if (user.authType == 'Basic_Auth') {
            args.headers['Authorization'] = makeBasicAuthHeader(user.username, user.password);
        }
	},
	request: function(params, callback) {
		var args = {
			type: 'GET',
			data: {},
			headers: {},
			data: {}
		};
		$.extend(args, params);
		var url = this.config.api_host + args.url + this.config.format;
		
		if (!args.url) {
            alert('url不能为空');
            return false;
        }
        
        var user = args.user || args.data.user || localStorage.CURRENT_USER_KEY;
        if (!user) {
            alert('用户不存在');
            return false;
        }
		
		args.data.source = args.data.source || this.config.source;
		if (!args.source_required && args.data.source) {
			delete args.source_required;
			delete args.data.source_required;
		}
		
		this.apply_auth(url, args, user);
		$.ajax({
			url: url,
			type: args.type,
			data: $.param(args.data),
			dataType: 'text',
			context: this,
            beforeSend: function(xhr) {
                for (var k in args.headers) {
                    xhr.setRequestHeader(k, args.headers[k]);
                }
            },
			success: function(data) {
				alert(data);
			},
			error: function() {
				alert(0);
			}
		});
	}
};

var Api_Fanfou = $.extend({}, Api_Sinat, {
	config: $.extend({}, Api_Sinat.config, {
		api_host: 'http://api2.fanfou.com',
		source: 'im24Beta'
	})
});

var tapis = {
	'sinat': Api_Sinat,
	'fanfou': Api_Fanfou
};

var tapi = {
	dispatch: function(data) {
		var apiType = (data.user ? data.user.site : data.site) || 'sinat';
		return tapis[apiType];
	},
	public_timeline: function(data, callback) {
		this.dispatch(data).public_timeline(data, callback);
	},
	friends_timeline: function(data, callback) {
		this.dispatch(data).friends_timeline(data, callback);
	}
};
