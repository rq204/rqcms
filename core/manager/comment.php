<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
if(empty($action)) $action='cmlist';
$cid = empty($_GET['cid']) ? '':intval($_GET['cid']);
if(!$cid) $cid = empty($_POST['cid']) ? '':intval($_POST['cid']);
$articleid = intval(isset($_GET['articleid']) ? $_GET['articleid'] : (isset($_POST['articleid'])?$_POST['articleid']:0));
$do = in_array($do,array('hidden','display','del')) ? $do : '';
if(RQ_POST)
{
	if($action == 'domodcm') { 	// 修改评论
		if(!$cid) redirect('评论id不正确');
		$commarr=$DB->fetch_first("select * from ".DB_PREFIX."comment where cid=$cid and hostid=$hostid limit 1");
		if(!$commarr) redirect('不存在的评论');
		$aid=$commarr['articleid'];
		$username = trim($_POST['username']);
		$url = trim($_POST['url']);
		$email = trim($_POST['email']);
		if(!$username || strlen($username) > 30) {
			redirect('用户名为空或用户名太长');
		}
		$name_key = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#','$','(',')','%','@','+','?',';','^');
		foreach($name_key as $value){
			if (strpos($username,$value) !== false){ 
				redirect('此用户名包含不可接受字符或被管理员屏蔽,请选择其它用户名');
			}
		}
		$username = char_cv($username);
		$DB->unbuffered_query("UPDATE ".DB_PREFIX."comment SET username='$username', url='$url',`email`='$email',`content`='".$_POST['content']."' WHERE cid='$cid'");
		redirect('修改评论成功', 'admin.php?file=comment&action=cmlist&aid='.$aid);
	}	
	else if($action == 'domorecmlist') { //批量处理评论状态
	if ($do == 'display') {
		$visible = '1';
		$msg     = '所选评论已显示';
		$del     = false;

	} elseif ($do == 'hidden') {
		$visible = '0';
		$msg     = '所选评论已隐藏';
		$del     = false;
	} elseif ($do == 'del') {
		$msg     = '所选评论已删除';
		$del     = true;
	} else {
		redirect('未选择任何操作');
	}

	if ($cids = implode_ids($_POST['comment'])) {
		if ($del) {
			$DB->unbuffered_query("DELETE FROM ".DB_PREFIX."comment WHERE cid IN ($cids)");
		} else {
			$DB->unbuffered_query("UPDATE ".DB_PREFIX."comment SET visible='$visible' WHERE cid IN ($cids)");
		}
		$query = $DB->query("SELECT articleid FROM ".DB_PREFIX."comment where cid IN ($cids)");
		while ($article = $DB->fetch_array($query)) {
			// 更新所有文章的评论数
			$total = $DB->num_rows($DB->query("SELECT cid FROM ".DB_PREFIX."comment WHERE articleid='".$article['articleid']."' AND visible='1'"));
			$DB->unbuffered_query("UPDATE ".DB_PREFIX."article SET comments='$total' WHERE aid='".$article['articleid']."'");
		}
		redirect($msg, 'admin.php?file=comment&action=cmlist&articleid='.$articleid);
	} else {		
		redirect('未选择任何评论');
	}
	}
}
else
{	
	$pagelink='';
	//设置状态
	if($action == 'cmvisible') {
		if ($cid) {
			$comment = $DB->fetch_first("SELECT visible,articleid FROM ".DB_PREFIX."comment WHERE cid='$cid' and hostid=$hostid");
			if ($comment['visible']) {
				$visible = '0';
				$query = '-';
				$state = '隐藏';
			} else {
				$visible = '1';
				$query = '+';
				$state = '显示';
			}
			$DB->unbuffered_query("UPDATE ".DB_PREFIX."article SET comments=comments".$query."1 WHERE aid='".$comment['articleid']."' and hostid=$hostid");
			$DB->unbuffered_query("UPDATE ".DB_PREFIX."comment SET visible='$visible' WHERE cid='$cid' and hostid=$hostid");
			redirect('已经成功把该评论设置为 '.$state.' 状态', 'admin.php?file=comment&action=cmlist&aid='.$articleid);
		} else {
			redirect('缺少评论id参数', 'admin.php?file=comment&action=cmlist&articleid='.$articleid);
		}
	}
	else if ($action == 'cmlist') {
		$sql_query = " WHERE hostid=$hostid ";
		$subnav = '全部评论';
		$kind =isset($_GET['kind']) &&in_array($_GET['kind'],array('display','hidden')) ? $_GET['kind'] : '';
		if ($kind == 'display') {

			$sql_query .= " AND visible='1'";
			$pagelink   = '&kind=display';
			$subnav     = '全部显示的评论';
		}
		if ($kind == 'hidden') {

			$sql_query .= " AND visible='0'";
			$pagelink   = '&kind=hidden';
			$subnav     = '全部隐藏的评论';

		}
		if ($articleid) {
			$article = $DB->fetch_first("SELECT title FROM ".DB_PREFIX."article WHERE aid='$articleid'");
			$sql_query .= " AND articleid='$articleid'";
			$pagelink   = '&articleid='.$articleid;
			$subnav     = '文章:'.$article['title'];
		}
		$ip =isset($_GET['ip'])? char_cv($_GET['ip']):'';
		if ($ip) {
			$frontlen = strrpos($ip, '.');
			$ipc = substr($ip, 0, $frontlen);
			$sql_query .= " AND (ipaddress LIKE '%".$ipc."%')";
			$pagelink   = '&ip='.$ip;
			$subnav     = '与 '.$ip.' 同一C段提交的评论';
		}
		if($page) {
			$start_limit = ($page - 1) * 30;
		} else {
			$start_limit = 0;
			$page = 1;
		}
		$total     = $DB->num_rows($DB->query("SELECT cid FROM ".DB_PREFIX."comment $sql_query"));
		$multipage = multi($total, 30, $page, 'admin.php?file=comment&action=cmlist'.$pagelink);

		$query  = $DB->query("SELECT * FROM ".DB_PREFIX."comment $sql_query ORDER BY cid DESC LIMIT $start_limit, 30");
		$commentdb = array();

		while ($comment = $DB->fetch_array($query)) {
			$comment['visible'] = $comment['visible'] ? '<span class="yes">显示</span>' : '<span class="no">隐藏</span>';
			$comment['url'] = $comment['url'] ? ('<a href="'.$comment['url'].'" target="_blank">'.$comment['url'].'</a>') : '<font color="#FF0000">Null</font>';
			$comment['email'] = $comment['email'] ? ( '<a href="mailto:'.$comment['email'].'" target="_blank">'.$comment['email'].'</a>') : '<font color="#FF0000">Null</font>';
			$comment['dateline'] = date('Y-m-d H:i',$comment['dateline']);
			$comment['author']=$comment['userid']?'<a href="admin.php?file=user&action=mod&userid='.$comment['userid'].'" target="_blank">'.$comment['username'].'</a>':$comment['username'];
			$comment['content'] = htmlspecialchars($comment['content']);
			$commentdb[] = $comment;
		}
		unset($comment);
		$DB->free_result($query);
	}//end list
	
		
	if ($action == 'modcm') {
		$comment = $DB->fetch_first("SELECT c.*, a.title FROM ".DB_PREFIX."comment c LEFT JOIN ".DB_PREFIX."article a ON (a.aid=c.articleid) WHERE c.cid='$cid' and c.hostid=$hostid");
		$comment['content'] = htmlspecialchars($comment['content']);
		$subnav = '修改评论';
	}//end mod
}



