app.views.CategoryList = Ext.extend(Ext.Panel, {
	dockedItems: [{
		xtype: 'toolbar',
		ui: 'light',
        layout: {
            pack: 'center'
        },
		items: [{
            xtype: 'segmentedbutton',
            items: [
                {text: '太搞了', pressed: true},
                {text: '太冷了'},
                {text: '太经典了'}
            ],
            handler: function(){
                Ext.dispatch({
                    controller: app.controllers.HomeController,
                    action: 'login',
                    username: 'Chen Dong'
                });
            }
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
		app.views.CategoryList.superclass.initComponent.apply(this, arguments);
	}
});

Ext.reg('categorylist', app.views.CategoryList);
