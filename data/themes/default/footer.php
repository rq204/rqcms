<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
$clearurl=mkUrl('profile.php','clearcookies');
?>
<div class=foot>
    Copyright © 2010-2012 <a href="http://<?php echo $constant['RQ_HOST'];?>"><?php echo $host['name'];?></a> All Rights Reserved. Powered by <a href="<?php echo RQ_WEBSITE;?>" 
target=_blank><B><?php echo $constant['RQ_AppName'];?>&nbsp;<?php echo $constant['RQ_VERSION'];?></B> </a><br />
    <a href="http://validator.w3.org/check?uri=referer" target="_blank">XHTML 1.0</a>. <a href="<?php echo $clearurl;?>">清除Cookies</a> 
<?php
if($host['icp']){ 
?>
<a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo $host['icp'];?></a>
<?php
}
?>
  </div>
</div>
</body>
</html>