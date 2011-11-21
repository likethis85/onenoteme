app.views.viewport = Ext.extend(Ext.TabPanel, {
    tabBar: {
        dock: 'bottom',
        layout: {
            pack: 'center'
        }
    },
    fullscreen: true,
    ui: 'light',
    cardSwitchAnimation: {
        type: 'slide',
        cover: true
    },
    defaults: {
        scroll: 'vertical'
    },
	items: [{
        xtype: 'latestlist',
        title: '最新',
        iconCls: 'time',
        cls: 'card1',
        badgeText: '4',
		id: 'tab1'
    }, {
		xtype: 'hottestlist',
        title: '最热',
        iconCls: 'favorites',
        cls: 'card2'
    }, {
		xtype: 'categorylist',
        title: '分类',
        iconCls: 'team',
        cls: 'card3'
    }, {
        title: '关于',
        id: 'tab3',
        html: '<h1>Downloads Card</h1>',
        cls: 'card4',
        iconCls: 'info'
    }],
	initComponent: function(){
		app.views.viewport.superclass.initComponent.apply(this, arguments);
	}
});
