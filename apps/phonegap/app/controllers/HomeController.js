app.controllers.HomeController = new Ext.Controller({
	
	index: function(options){
		app.views.viewport.setActiveItem(0, options.animation);
		console.log(Ext.getCmp('tab1').tab.setBadge('5'));
		console.log('home/index');
		
	},
	
	login: function(options){
		app.views.viewport.setActiveItem(app.views.HomeLogin, 'slide');
		//Ext.Msg.alert('home/login', 'username: ' + options.username);
		console.log('home/login');
	}
});
