<div id="quick-login">
    <span href="javascript:void(0);" class="site-bg quick-login-close"></span>
    <div class="column-login">
        <form action="#" method="post">
            <h1>欢迎回来</h1>
            <label>用户名或邮箱</label>
            <input type="text" class="input-quick" />
            <label>密码：(忘记密码)</label>
            <input type="password" class="input-quick" />
            <label class="checkbox">
                <input type="checkbox" />记住我
            </label>
            <input type="button" class="btn" id="btn-quick-login" value="登录" />
        </form>
    </div>
    <div class="column-signup">
        <div class="signup-tip">
            <h1>创建账号</h1>
            <p>请珍惜自己的账号，一旦作恶，账号将被永久删除。</p>
            <p>使用<?php echo app()->name;?> <a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone</a>或<a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank">Android</a> 应用可以快速注册，尽享移动欢乐！</p>
            <a href="<?php echo CDBase::singupUrl();?>" target="_blank" class="btn btn-primary">注册</a>
        </div>
    </div>
    <div class="clear"></div>
</div>