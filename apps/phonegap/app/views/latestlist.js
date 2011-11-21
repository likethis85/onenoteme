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
		store: app.stores.Posts,
		itemTpl: '{id} {content} {create_time}',
		listeners: {
			'added': function(component, container, pos){
				console.log('added ' + pos);
			},
			'itemtap': function(dataView, index, item, e){
				console.log('itemtap, item index: ' + index);
				console.log(item);
                var row = app.stores.Posts.getById(index);
                console.log(row.data);
				console.log(dataView.getRecord(item));
			}
		},
		plugins: [ new Ext.plugins.ListPagingPlugin({
	        autoPaging: false,
	        loadMoreText: "Load More"
	    })]
	}],
	initComponent: function(){
		app.views.LatestList.superclass.initComponent.apply(this, arguments);
	}
});

Ext.reg('latestlist', app.views.LatestList);
