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
				text: '24小时', pressed: true
				}, {
					text: '7天',
                    handler: function() {
                        app.stores.Posts.loadPage(3);
                    }
				}, {
					text: '1个月',
					handler: function() {
						app.stores.Posts.loadPage(2);
		      		}
			}]
        }]
	}],
	layout: 'fit',
	items: [{
		xtype: 'list',
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
		}
	}],
	initComponent: function(){
		app.views.HottestList.superclass.initComponent.apply(this, arguments);
	}
});

Ext.reg('hottestlist', app.views.HottestList);
