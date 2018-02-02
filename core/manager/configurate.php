<?php
$settingmenu = array(
	'basic' => '基本设置',
	'display' => '显示设置',
	'search' => '搜索设置',
	'filemap'=>'文件映射'
);
$type=isset($_GET['type'])?$_GET['type']:(isset($_POST['type'])?$_POST['type']:'');
if(RQ_POST&&isset($_POST['action'])&&$_POST['action'] == 'updatesetting')
{
	$sql="replace into {$dbprefix}option (`name`,`value`) values ";
	foreach($_POST['option'] AS $key => $val)
	{
		$sql.="('$key','$val'),";
	}
	$sql=substr($sql,0,strlen($sql)-1);
	$DB->query($sql);
	setting_recache();
	redirect('更新系统配置成功', $admin_url.'?file=configurate&type='.$type);
}

?>