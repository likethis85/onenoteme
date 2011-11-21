Ext.regApplication({
	name: 'app',
	fullscreen:true,
	launch: function() {
		this.launched = true;
		this.mainLaunch();
		console.log('launch');
	},
	mainLaunch: function(){
		if (!device || !this.launched) {return;}
		
		this.views.viewport = new this.views.viewport();
		Ext.dispatch({
			controller: app.controllers.HomeController,
			action: 'index',
			animation: 'slide'
		});
		console.log('mainLaunch');
	}
});

