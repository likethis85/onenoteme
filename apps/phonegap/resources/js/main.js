var Api_Waduanzi = {
	config: {
		apiHost: 'http://waduanzi.cn/api',
		apiKey: '123',
		apiSecret: '123',
		apiFormat: 'json',
		apiGetCategories: 'category.getlist',
        apiCreatePost: 'post.create',
		apiUserLogin: 'user.login',
		apiUserLogout: 'user.logout'
	},
	debug: 1,
	getRequestUrl: function(method, params) {
		var accessor = {consumerSecret: Api_Waduanzi.config.apiSecret, tokenSecret: '', accessorSecret: ''};
		if (Api_Waduanzi.debug)
			params.push(['debug', 1]);
        params.push(['oauth_consumer_key', Api_Waduanzi.config.apiKey]);
        params.push(['format', Api_Waduanzi.config.apiFormat]);
        var message = {method: method, action: Api_Waduanzi.config.apiHost, parameters: params};
		
        OAuth.completeRequest(message, accessor);
		//var bs = OAuth.SignatureMethod.getBaseString(message);
		//console.log(bs);
		var parameters = OAuth.SignatureMethod.normalizeParameters(message.parameters);
		var url = OAuth.addToURL(Api_Waduanzi.config.apiHost, parameters) + '&oauth_signature=' + OAuth.getParameter(message.parameters, "oauth_signature");
		return url;
    }
};
