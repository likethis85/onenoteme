$(function(){
	Api_Waduanzi.windowInit();
    
    $('.on-toolbar').on('click', '#newcategory', function(event){Api_Waduanzi.newCategory();});
    $('.on-toolbar').on('click', '#newtopic', function(event){Api_Waduanzi.newTopic();});
    $('.on-toolbar').on('click', '#newpost', function(event){Api_Waduanzi.newPost();});
	
    $('.on-toolbar').on('click', '#logout', function(event){Api_Waduanzi.logout();});
    $('.on-toolbar').on('click', '#login', function(event){Api_Waduanzi.login();});
    $('.on-toolbar').on('click', '#signup', function(event){Api_Waduanzi.signup();});
    

    
});
