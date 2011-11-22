app.models.Post = Ext.regModel('app.models.Post', {
	idProperty: 'id',
	fields: [
	   {name: 'id', type: 'int'},
	   {name: 'content', type: 'string'},
	   {name: 'create_time', type: 'string'},
	   {name: 'up_score', type: 'int'},
	   {name: 'down_score', type: 'int'}
	]
});

app.stores.LatestPosts = new Ext.data.Store({
	model: 'app.models.Post',
//	sorters: [{
//		property: 'id',
//		direction: 'desc'
//	}],
	pageSize: 5,
	autoLoad: true,
    proxy: {
        type: 'scripttag',
        url: 'http://waduanzi.cn/phone/latest',
        reader: {
            type: 'json'
        },
		noCache: true
    }
});

app.stores.HottestPosts = new Ext.data.Store({
	model: 'app.models.Post',
//	sorters: [{
//		property: 'up_score',
//		direction: 'desc'
//	}, {
//		property: 'id',
//		direction: 'desc'
//	}],
	pageSize: 5,
	autoLoad: true,
    proxy: {
        type: 'scripttag',
        url: 'http://waduanzi.cn/phone/hottest',
        reader: {
            type: 'json'
        },
		noCache: true,
		extraParams: {
			interval: 'P1D'
		}
    }
});

app.stores.CategoryPosts = new Ext.data.Store({
	model: 'app.models.Post',
//	sorters: [{
//		property: 'id',
//		direction: 'desc'
//	}],
	pageSize: 5,
	autoLoad: true,
    proxy: {
        type: 'scripttag',
        url: 'http://waduanzi.cn/phone/category',
        reader: {
            type: 'json'
        },
		noCache: true,
		extraParams: {
			cid: 1
		}
    }
});

app.stores.LatestPosts.on('beforeload', function(store, operation){
	Ext.getBody().mask();
});

app.stores.LatestPosts.on('load', function(store, records, successful){
    Ext.getBody().unmask();
});

app.stores.HottestPosts.on('beforeload', function(store, operation){
	Ext.getBody().mask();
});

app.stores.HottestPosts.on('load', function(store, records, successful){
    Ext.getBody().unmask();
});

app.stores.CategoryPosts.on('beforeload', function(store, operation){
	Ext.getBody().mask();
});

app.stores.CategoryPosts.on('load', function(store, records, successful){
    Ext.getBody().unmask();
});




