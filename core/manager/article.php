<?php
if(empty($action)) $action='list';

if(RQ_POST)
{	
	$article=isset($_POST['article'])?$_POST['article']:array();
	$content=isset($_POST['content'])?$_POST['content']:array();
	
	if(in_array($action,array('add','mod')))
	{
		$article['title'] = trim($article['title']);
		$article['cateid'] = intval($article['cateid']);
		$tags        = getTagArr(trim($article['tag']));
		$article['tag']=$tags?implode(',',$tags):'';
		$article['username']=$username;
	}

	switch($action)
	{
		case 'add':
			if(empty($article['title'])) redirect('标题不得为空',$admin_url.'?file=article&action=add');
			if(empty($content['content'])) redirect('内容不得为空',$admin_url.'?file=article&action=add');

			// 插入数据部分
			$addsql="INSERT INTO {$dbprefix}article set ".getJoinSql($article);
			$DB->query($addsql);
			$articleid = $DB->insert_id();

			$content['articleid']=$articleid;

			$DB->query("Insert into {$dbprefix}content set ".getJoinSql($content));

			//添加tags
			modtag('',$article['tag'],$articleid);
			redirect('添加文章成功', $admin_url.'?file=article&action=add');
			break;
		case 'mod'://修改文章
			$aid=intval($_POST['aid']);
			$old=$DB->fetch_first("Select * from {$dbprefix}article where aid=$aid");
			if(!$old) redirect('不存在的记录',$admin_url.'?file=article&action=list');
			if(empty($article['title'])) redirect('标题不得为空',$admin_url.'?file=article&action=mod&aid='.$aid);
			if(empty($content['content'])) redirect('内容不得为空',$admin_url.'?file=article&action=mod&aid='.$aid);
			$oldtag=$old['tag'];
	
			// 插入数据部分
			$DB->query("Update {$dbprefix}article set ".getJoinSql($article)." where aid=$aid");
			$DB->query("Update {$dbprefix}content set ".getJoinSql($content)." where `articleid`='$aid'");
			//添加tags
			modtag($oldtag,$article['tag'],$aid);
			redirect('修改文章成功', $admin_url.'?file=article&action=list');
		break;
		case 'domore':
			if(isset($_POST['aids'])&&is_array($_POST['aids']))
			{
				$aids=implode_ids($_POST['aids']);
				$aquery=$DB->query("Select aid from {$dbprefix}article where aid in ($aids)");
				$aidarr=array();
				while($ainfo=$DB->fetch_array($aquery))
				{
					$aidarr[]=$ainfo['aid'];
				}
				$aids=implode_ids($aidarr);
				if(in_array($do,array('delete','move')))
				{
					$query=$DB->query("Select * from {$dbprefix}article where aid in ($aids)");
					while($article=$DB->fetch_array($query))
					{
						$articledb[]=$article;
					}
				}
				else
				{
					switch($do)
					{
						case 'dodelete':
							$articlequery=$DB->query("select aid,tag from {$dbprefix}article where aid in ($aids) ");
							while($article=$DB->fetch_array($articlequery))
							{
								modtag($article['tag'],'',$article['aid']);
							}
							
							$DB->query("delete from {$dbprefix}article where aid in ($aids) ");
							$DB->query("delete from {$dbprefix}content where articleid in ($aids)");
							
							redirect('您选择的文章已成功删除', $admin_url.'?file=article&action=list');
							break;
						case 'domove':
							$cid=intval($_POST['cid']);
							$cateinfo=$DB->fetch_first("select * from {$dbprefix}category where cid='$cid' ");
							if($cateinfo)
							{
								$DB->query("update {$dbprefix}article set cateid='$cid' where aid in ($aids) ");
								redirect('您选择的文章成功移动', $admin_url.'?file=article&action=list&cid='.$cid.($view?'&view='.$view:''));
							}
							else redirect('您选择的栏目不存在', $admin_url.'?file=article&action=list');
						break;
					}
				}
			}
			else redirect('请选择要操作的文章', $admin_url.'?file=article&action=list');
			break;
		case 'list':
			if ($do == 'search') 
			{
				$searchsql="Select a.*,c.cid,c.name as cname from {$dbprefix}article a,{$dbprefix}category c where c.cid=a.cateid";
				$keywords = !empty($_POST['keywords'])?trim($_POST['keywords']):'';
				if ($keywords) 
				{
					$keywords = str_replace("_","\_",$keywords);
					$keywords = str_replace("%","\%",$keywords);
					if(preg_match("(AND|\+|&|\s)", $keywords) && !preg_match("(OR|\|)", $keywords)) {
						$andor = ' AND ';
						$sqltxtsrch = '1';
						$keywords = preg_replace("/( AND |&| )/is", "|", $keywords);
					} else {
						$andor = ' OR ';
						$sqltxtsrch = '0';
						$keywords = preg_replace("/( OR |\|)/is", "|", $keywords);
					}
					$keywords = str_replace('*', '%', addcslashes($keywords, '%_'));
					foreach(explode("|", $keywords) AS $text) {
						$text = trim($text);
						if($text) {
							$sqltxtsrch .= $andor;
							$sqltxtsrch .= "(a.title LIKE '%".$text."%')";
							doAction('admin_article_search_changesql');
						}
					}
					$searchsql .= " AND ($sqltxtsrch)";
				}
				if(!empty($_POST['cateid']))
				{
					$searchsql .= " AND a.cateid='".intval($_POST['cateid'])."'";
				}
				$searchsql .= !empty($_POST['startdate']) ? " AND dateline < '".$_POST['startdate']."'" : '';
				$searchsql .= !empty($_POST['enddate'] )? " AND dateline > '".$_POST['enddate']."'" : '';
				$squery=$DB->query($searchsql);
				$multipage='';
				$articledb = array();
				while ($article = $DB->fetch_array($squery)) {
					$articledb[] = $article;
				}
				$total=count($articledb);
			}
			else redirect('请指定搜索条件', $admin_url.'?file=article&action=list');
			break;
		default:
		redirect('未定义操作', $admin_url.'?file=article&action=list');
	}
}
else
{
	if($action=='add'||$action=='mod')
	{
		$aid=isset($_GET['aid'])?intval($_GET['aid']):0;
		//类别
		if(!$aid)
		{
			$stick_check='';
			$visible_check='checked';
			$article['title']=$article['content']=$article['tag']=$article['excerpt']='';
			$tdtitle='添加内容';
		}
		else
		{
			$article=$DB->fetch_first("Select a.*,c.* from {$dbprefix}article a left join {$dbprefix}content c on a.aid=c.articleid where a.aid=$aid");
			$tdtitle='编辑内容';
		}

	}
	else if($action=='list')
	{
		$searchsql='';
		$addquery='';
		$pagelink='';
		$view=isset($_GET['view'])?$_GET['view']:'';
		$tag=isset($_GET['tag'])?$_GET['tag']:'';
		$cid=isset($_GET['cid'])?intval($_GET['cid']):'';
		if ($cid) {
			$addquery = " AND a.cateid='$cid'";
			$pagelink = '&cid='.$cid;
		} 
		else $addquery = "";	

		if($page) {
		$start_limit = ($page - 1) * 30;
		} else {
			$start_limit = 0;
			$page = 1;
		}
		$articledb = array();
		if(empty($tag))
		{
			$rs = $DB->fetch_first("SELECT count(*) AS articles FROM {$dbprefix}article a WHERE 1 $searchsql $addquery");
			$total = $rs['articles'];
			$multipage = multi($total, 30, $page, $admin_url.'?file=article&action=list'.$pagelink);
			$query = $DB->query("SELECT a.*,c.name as cname FROM {$dbprefix}article a 
			LEFT JOIN {$dbprefix}category c ON c.cid=a.cateid
			WHERE 1 $searchsql $addquery ORDER BY a.aid DESC LIMIT $start_limit, 30");
		}
		else
		{
			$item = addslashes($tag);
			$tagaids=gettagids($item);
			
			if($tagaids)
			{
				$aidsarr=explode(',',$tagaids);
				arsort($aidsarr,1);
				$all_tag_total=count($aidsarr);
				$pagenum=@ceil($all_tag_total/30);
				if($page>$pagenum) $page=$pagenum;
				$start = ($page - 1) * 30;
				$selectnum=30;
				if($selectnum+$start>$all_tag_total) $selectnum=$all_tag_total-$start;
				$listaids=array_slice($aidsarr,$start,$selectnum);
				$aidstr=implode_ids($listaids);

				$query=$DB->query("Select a.*,c.name as cname from {$dbprefix}article a LEFT JOIN {$dbprefix}category c ON c.cid=a.cateid where a.aid in ($aidstr) order by a.aid desc");
				$tagarr=explode(',',$tagaids);
				$total=count($tagarr);
			}
			else
			{
				redirect('标签不存在', $admin_url.'?file=article&action=list');
			}
			
			$pagelink = '&tag='.urlencode($item);
			$multipage = multi($total, 30, $page, $admin_url.'?file=article&action=list'.$pagelink);
			$subnav = 'Tags:'.$item;
		}
		while ($article = $DB->fetch_array($query)) {
				$articledb[] = $article;
		}
		unset($article);
		$DB->free_result($query);
	}
}

//得到tag的数组
function getTagArr($tag)
{
	if(!$tag) return false;
	$tag  = str_replace('，', ',', $tag);
	$tag  = str_replace(',,', ',', $tag);	
	if (substr($tag, -1) == ',')
	{
		$tag = substr($tag, 0, strlen($tag)-1);
	}
	if(!$tag) return false;
	$tagarr= explode(',',$tag);
	$tagarr= array_unique($tagarr);
	return $tagarr;
}

// 检查提交内容是否符合逻辑
function checkcontent($content) {
	if(!$content || strlen($content) < 4) {
		$result .= '内容不能为空并且不能少于4个字符<br />';
		return $result;
	}
}

// 检查标题是否符合逻辑

function checktitle($title) {

	if(!$title || strlen($title) > 120) {

		$result = '标题不能为空并且不能超过120个字符<br />';
		return $result;

	}
}

function cleartag($tagname)
{
	$tagname=trim($tagname);
	$trim=array('/','*','$','-',';','#','"');
	foreach($trim as $tr) $tagname=str_replace($tr,'',$tagname);
	if(preg_match('/[a-zA-Z ]*/ui', $tagname))
	{
		$tagname=ucwords(strtolower($tagname));
	}
	return $tagname;
}
