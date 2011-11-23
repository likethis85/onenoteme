app.views.LatestList = Ext.extend(Ext.Panel, {
	dockedItems: [{
		xtype: 'toolbar',
		ui: 'light',
		title: '最新段子'
	}],
	layout: 'fit',
	items: [{
		xtype: 'list',
		id: 'latest',
		store: app.stores.LatestPosts,
		itemTpl: '{id} {content} {create_time}',
		listeners: {
			'itemtap': function(dataView, index, item, e){
				console.log('itemtap, item index: ' + index);
				console.log(item);
                var row = app.stores.LatestPosts.getById(index);
                console.log(row.data);
				console.log(dataView.getRecord(item));
			}
		},
		plugins: [ new Ext.plugins.ListPagingPlugin({
	        autoPaging: false,
	        loadMoreText: "载入更多..."
	    })]
	}],
	initComponent: function(){
		app.views.LatestList.superclass.initComponent.apply(this, arguments);
	}
});

Ext.reg('latestlist', app.views.LatestList);
