<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$imgurl=mkUrl('captcha.php','');
?>

<h2 class="title">注册</h2>

<?php
if ($options['closereg']) {?>

<p>对不起, 目前系统禁止新用户注册. 请返回...</p>

<?php
} else {?>

<p>注册后, 您的用户名得到保护, 别人无法使用您的名字发表任何评论, <br />
  如果填写了备选信息, 系统在您发表评论时, 自动填写.</p>
<form action="<?php echo $profile_url;?>" method="post" onsubmit="return checkloginform();">
  <input type="hidden" name="action" value="register" />
  <input type="hidden" name="formhash" value="<?php echo $formhash;?>" />
  <div class="formbox">
  <p>
    <label for="username">用户名(*):<br />
    <input name="username" id="username" size="54" maxlength="20" tabindex="1" value="" class="formfield" />
    </label>
  </p>
  <p>
    <label for="password">密码(*):<br />
    <input name="password" id="password" type="password" size="54" maxlength="20" tabindex="2" value="" class="formfield" />
    </label>
  </p>
  <p>
    <label for="confirmpassword">确认密码(*):<br />
    <input name="confirmpassword" id="confirmpassword" type="password" size="54" maxlength="20" tabindex="3" value="" class="formfield" />
    </label>
  </p>
  <p>
    <label for="url">网址或电子邮件:<br />
    <input name="url" id="url" type="text" size="54" maxlength="100" tabindex="4" value="" class="formfield" />
    </label>
  </p>
  
<?php
if ($options['seccode_enable']) {?>

  <p>
    <label for="clientcode">验证码(*):<br />
    <input name="clientcode" id="clientcode" value="" tabindex="5" class="formfield" size="6" maxlength="6" />
    <img id="seccode" class="codeimg" src="<?php echo $imgurl;?>" alt="单击图片换张图片" border="0" onclick="this.src='<?php echo $imgurl;?>?' + Math.random()" /></label>(请将后三位数字倒序输入)
  </p>
  
<?php
}?>

  <p>
    <label for="submit">
    <button name="submit" id="submit" type="submit" class="formbutton">确定</button>
    </label>
  </p>
  </div>
</form>

<?php
}
?>