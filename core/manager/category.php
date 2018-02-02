<?php
if(RQ_POST)
{
	switch($action)
	{
		case 'doadd':
			//添加分类
			$name   = trim($_POST['name']);
			$displayorder = intval($_POST['displayorder']);
			$url=trim($_POST['url']);
			$description=trim($_POST['description']);
			$result = checkname($name);
			if($result)
			{
				redirect($result);
			}
			$name = char_cv($name);
			$rs = $DB->fetch_first("SELECT count(*) AS categories FROM {$dbprefix}category WHERE name='$name' ");
			if($rs['categories']) 
			{
				redirect('该分类名在数据库中已存在');
			}
			if(!$url) redirect('友好网址不得为空');
			$us = $DB->fetch_first("SELECT count(*) AS url FROM {$dbprefix}category WHERE url='$url' ");
			if($us['url']) 
			{
				redirect('该友好网址在数据库中已存在');
			}
			$DB->query("INSERT INTO {$dbprefix}category (name,displayorder,url,description) VALUES ('$name','$displayorder','$url','$description')");
			category_recache();
			redirect('添加新分类成功', $admin_url.'?file=category');
			break;
		case 'domod':
			//修改分类
			$name   = trim($_POST['name']);
			$url   = trim($_POST['url']);
			$cid    = intval($_POST['cid']);
			$displayorder=intval($_POST['displayorder']);
			$description=trim($_POST['description']);
			$result = checkname($name);
			if($result) redirect($result);
			$name = char_cv($name);
			$rs = $DB->fetch_first("SELECT count(*) AS categories FROM {$dbprefix}category WHERE cid!='$cid' AND name='$name'");
			if($rs['categories']) {
				redirect('已经有其他分类使用【'.$name.'】这个名称');
			}
			if(!$url) redirect('友好网址不得为空');
			$us = $DB->fetch_first("SELECT count(*) AS url FROM {$dbprefix}category WHERE cid!='$cid' AND url='$url' ");
			if($us['url']) {
				redirect('已经有其他友好网址使用【'.$url.'】这个名称');
			}
			// 更新分类
			$DB->query("UPDATE {$dbprefix}category SET name='$name',displayorder='$displayorder',url='$url',description='$description' WHERE cid='$cid'");
			category_recache();
			redirect('修改分类成功', $admin_url.'?file=category');
			break;
		case 'dodel':
			//删除分类
			$cid = intval($_POST['cid']);
			$aids = $a_tatol = 0;
			// 删除分类
			$DB->query("DELETE FROM {$dbprefix}category WHERE cid='$cid'");

			$query = $DB->query("SELECT aid,tag FROM {$dbprefix}article WHERE cateid='$cid'  ORDER BY aid");
			while ($article = $DB->fetch_array($query)) {
				$aids .= ','.$article['aid'];
				if ($article['keywords']) {
					$tagarr=explode(',');
					foreach($tagarr as $tg)
					{
						$DB->query("update tag set aids=replace(`aids`,',{$article['aid']},','') where tag='{$tg}'");
					}
				}
			}//end while

			// 删除评论
			$DB->query("DELETE FROM {$dbprefix}comment WHERE articleid IN ($aids)");

			// 删除分类下的文章
			$DB->query("DELETE FROM {$dbprefix}article WHERE cateid='$cid'");
			category_recache();
			redirect('成功删除分类和该分类下所有文章以及相关评论', $admin_url.'?file=category');
			break;
		case 'updatedisplayorder':
			// 更新分类排序
			if (!$_POST['displayorder'] || !is_array($_POST['displayorder'])) 
			{
				redirect('未选择任何分类');
			}
			$displayorder=$_POST['displayorder'];
			foreach($displayorder as $cid => $order) 
			{
				$DB->query("UPDATE {$dbprefix}category SET displayorder='".intval($order)."' WHERE cid='$cid' ");
			}
			category_recache();
			redirect('所有分类的排序已更新', $admin_url.'?file=category');
			break;
		default:
			redirect('未定义操作', $admin_url.'?file=category');
	}
}
else
{
	if(empty($action)) $action='list';
	$catenav = '分类管理';
	
	$category=array();
	$catequery=$DB->query("Select * from {$dbprefix}category order by displayorder desc");
	while($cateinfo=$DB->fetch_array($catequery))
	{
		$cid=$cateinfo['cid'];
		$category[$cid]=$cateinfo;
		$category[$cid]['count']=0;
	}

	//分类操作
	if (in_array($action, array('add', 'mod', 'del'))) {
	 //先得到所有
		if ($action == 'add') {
			$subnav = '添加分类';
			$cate['cid']=$cate['name']=$cate['url']=$cate['keywords']=$cate['description']='';
			$cate['displayorder']=0;
		} else {
			$cate = $DB->fetch_first("SELECT * FROM {$dbprefix}category WHERE cid='".intval($_GET['cid'])."' ");
			if($action == 'mod') {
				$subnav = '修改分类';
			} else {
				$subnav = '删除分类';
			}
		}
	}
	if($action=='list')
	{
		$countquery=$DB->query("SELECT count(aid) as ct,cateid FROM `{$dbprefix}article` group by cateid");
		while($catecount=$DB->fetch_array($countquery))
		{
			if(isset($category[$catecount['cateid']]))
			{
				$category[$catecount['cateid']]['count']=$catecount['ct'];
			}
		}
	}
}

// 检查分类名是否符合逻辑
function checkname($name) {
	if(!$name || strlen($name) > 30) {
		$result = '分类名不能为空并且不能超过30个字符<br />';
		return $result;
	}
}