app.views.HottestList = Ext.extend(Ext.Panel, {
	dockedItems: [{
		xtype: 'toolbar',
		ui: 'light',
		layout: {
			pack: 'center'
		},
		items: [{
            xtype: 'segmentedbutton',
			centered: true,
            items: [{
					text: '24小时',
					pressed: true,
	                handler: function() {
						Ext.apply(app.stores.HottestPosts.getProxy().extraParams, {
							interval: 'P1D'
						});
	                    app.stores.HottestPosts.load();
	                }
				}, {
					text: '7天',
                    handler: function() {
						Ext.apply(app.stores.HottestPosts.getProxy().extraParams, {
							interval: 'P1W'
						});
                        app.stores.HottestPosts.load();
                    }
				}, {
					text: '1个月',
					handler: function() {
						Ext.apply(app.stores.HottestPosts.getProxy().extraParams, {
							interval: 'P1M'
						});
                        app.stores.HottestPosts.load();
		      		}
			}]
        }]
	}],
	layout: 'fit',
	items: [{
		xtype: 'list',
		store: app.stores.HottestPosts,
        itemTpl: '{id} {content} {create_time}',
		listeners: {
			'added': function(component, container, pos){
				console.log('added ' + pos);
			},
			'itemtap': function(dataView, index, item, e){
				console.log('itemtap, item index: ' + index);
				console.log(item);
                var row = app.stores.HottestPosts.getById(index);
                console.log(row.data);
				console.log(dataView.getRecord(item));
			}
		}
	}],
	initComponent: function(){
		app.views.HottestList.superclass.initComponent.apply(this, arguments);
	}
});

Ext.reg('hottestlist', app.views.HottestList);
