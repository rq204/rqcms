<?php

//更新选项，插件，链接
function setting_recache()
{
	global $DB,$dbprefix;
	$setting=array();
	
	$options = $DB->query("SELECT * FROM `{$dbprefix}option`");
	while ($option = $DB->fetch_array($options))
	{
		$setting['option'][$option['name']] = $option['value'];
	}
	
	$query= $DB->query("SELECT * FROM `{$dbprefix}plugin` where active=1");
	while ($ps = $DB->fetch_array($query)) 
	{
		$setting['plugin'][$ps['file']]=$ps['config'];
	}
	
	$links = $DB->query("SELECT * FROM `{$dbprefix}link` WHERE visible = 1 ORDER BY displayorder ASC");
	while ($link = $DB->fetch_array($links))
	{
		$setting['link'][] = $link;
	}
	
	writeCache('setting',$setting);
}

//分类及系统设置参数
function category_recache()
{
	global $DB,$dbprefix;
	$cquery= $DB->query("SELECT * FROM `{$dbprefix}category` where visible=1 order by displayorder asc");
	$arrcates=array();
	while($cate=$DB->fetch_array($cquery))
	{
		$cate['count']=0;
		$arrcates[$cate['cid']]=$cate;
	}
	
	$countquery=$DB->query("SELECT count(aid) as ct,cateid FROM `{$dbprefix}article` group by cateid");
	while($catecount=$DB->fetch_array($countquery))
	{
		if(isset($arrcates[$catecount['cateid']]))
		{
			$arrcates[$catecount['cateid']]['count']=$catecount['ct'];
		}
	}
	writeCache('category',$arrcates);
}