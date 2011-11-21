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

app.stores.Posts = new Ext.data.Store({
	model: 'app.models.Post',
	sorters: [{
		property: 'id',
		direction: 'asc'
	}],
	pageSize: 5,
	autoLoad: true,
    proxy: {
        type: 'ajax',
        url: '/test.php',
        reader: {
            type: 'json'
        }
    }
});

app.stores.Posts.on('beforeload', function(store, operation){
	Ext.getBody().mask();
});

app.stores.Posts.on('load', function(store, records, successful){
    Ext.getBody().unmask();
});