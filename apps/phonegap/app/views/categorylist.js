app.views.CategoryList = Ext.extend(Ext.Panel, {
	dockedItems: [{
		xtype: 'toolbar',
		ui: 'light',
        layout: {
            pack: 'center'
        },
		items: [{
            xtype: 'segmentedbutton',
            items: [{
					text: '太糗了',
					pressed: true,
					handler: function() {
						Ext.apply(app.stores.CategoryPosts.getProxy().extraParams, {
							cid: 1
						});
	                    app.stores.CategoryPosts.load();
	                }
				}, {
					text: '太搞了',
					handler: function() {
						Ext.apply(app.stores.CategoryPosts.getProxy().extraParams, {
							cid: 2
						});
	                    app.stores.CategoryPosts.load();
	                }
				}, {
					text: '太冷了',
					handler: function() {
						Ext.apply(app.stores.CategoryPosts.getProxy().extraParams, {
							cid: 3
						});
	                    app.stores.CategoryPosts.load();
	                }
				}, {
					text: '太经典了',
					handler: function() {
						Ext.apply(app.stores.CategoryPosts.getProxy().extraParams, {
							cid: 4
						});
	                    app.stores.CategoryPosts.load();
	                }
				}
            ]
        }]
	}],
	layout: 'fit',
	items: [{
		xtype: 'list',
		store: app.stores.CategoryPosts,
        itemTpl: '{id} {content} {create_time}',
		listeners: {
			'itemtap': function(dataView, index, item, e){
				console.log('itemtap, item index: ' + index);
				console.log(item);
                var row = app.stores.LatestPosts.getById(index);
                console.log(row.data);
				console.log(dataView.getRecord(item));
			}
		}
	}],
	initComponent: function(){
		app.views.CategoryList.superclass.initComponent.apply(this, arguments);
	}
});

Ext.reg('categorylist', app.views.CategoryList);
