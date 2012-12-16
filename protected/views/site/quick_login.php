<div id="quick-login">
    <span href="#" data-dismiss="modal" class="site-bg quick-login-close"></span>
    <div class="column-login">
        <?php echo CHtml::form(aurl('site/quicklogin'), 'post', array('id'=>'form-quick-login'));?>
            <h1>欢迎回来</h1>
            <label>用户名或邮箱</label>
            <?php echo CHtml::activeTextField($form, 'username', array('class'=>'input-quick'));?>
            <label>密码：(忘记密码)</label>
            <?php echo CHtml::activePasswordField($form, 'password', array('class'=>'input-quick'));?>
            <label class="checkbox">
                <?php echo CHtml::activeCheckBox($form, 'rememberMe', array('class'=>'checkbox'));?>记住我
            </label>
            <input type="submit" class="btn btn-primary" data-loading-text="登录中..." data-error-text="登录错误" id="btn-quick-login" value="登录" />
        </form>
    </div>
    <div class="column-signup">
        <div class="signup-tip">
            <h1>创建账号</h1>
            <p>请珍惜自己的账号，一旦作恶，账号将被永久删除。</p>
            <p>使用<?php echo app()->name;?> <a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone</a>或<a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank">Android</a> 应用可以快速注册，尽享移动欢乐！</p>
            <a href="<?php echo CDBase::singupUrl();?>" target="_blank" class="btn btn-danger">注册</a>
        </div>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
$(function(){
	$('#btn-quick-login').button();
	$('#form-quick-login').on('submit', function(event){
	    var btn = $('#btn-quick-login');
		btn.button('loading');
		var url = $(this).attr('action');
		var data = $(this).serialize();
		Waduanzi.quickLogin(url, data, function(result){
			console.log(result);
			if (result.errno == 0) {
				wdz_logined = 1;
				$('#user-mini-nav').html(result.html);
				$('#quick-login-modal').modal('hide');
			}
			else {
				setTimeout(function(){btn.button('reset');}, 500);
			}
		}, function(jqXHR, textStatus, errorThrown){
			btn.button('error');
			setTimeout(function(){btn.button('reset');}, 2000);
		});
		event.preventDefault();
		return false;
	});
});
</script>