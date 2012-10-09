$(function(){
	Api_Waduanzi.windowInit();
    
    $('.on-toolbar').delegate('#newcategory', 'click', function(event){Api_Waduanzi.newCategory();});
    $('.on-toolbar').delegate('#newtopic', 'click', function(event){Api_Waduanzi.newTopic();});
    $('.on-toolbar').delegate('#newpost', 'click', function(event){Api_Waduanzi.newPost();});
	
    $('.on-toolbar').delegate('#logout', 'click', function(event){Api_Waduanzi.logout();});
    $('.on-toolbar').delegate('#login', 'click', function(event){Api_Waduanzi.login();});
    $('.on-toolbar').delegate('#signup', 'click', function(event){Api_Waduanzi.signup();});
    

    
});
