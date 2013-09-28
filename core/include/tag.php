<?php
// 检查提交Tag是否符合逻辑
function checktag($tag) {
	$tag = str_replace('，', ',', $tag);
	if (strrpos($tag, ',')) {
		$result .= '关键字中不能含有“,”或“，”字符<br />';
		return $result;
	}
	if(strlen($tag) > 15) {
		$result .= '关键字不能超过15个字符<br />';
		return $result;
	}
}

//更改tag
function modtag($olditem,$newitem)
{
	global $hostid,$DB;
	$aids=gettagids($olditem);
	if($aids)
	{
		$query=$DB->query('Select tag,aid from '.DB_PREFIX."article where aid in ($aids) and `hostid`='$hostid'");
		while($result=$DB->fetch_array($query))
		{
			$tagstr=$result['tag'];
			$aid=$result['aid'];
			if(strpos($tagstr,$olditem)!==false)
			{
				$newtagstr=$newitem;
				$oldtagarr=explode(',',$tagstr);
				foreach($oldtagarr as $oldtag) 
				{
					if($oldtag!=$olditem) $newtagstr.=','.$oldtag;
				}
				$DB->query('update '.DB_PREFIX."article set `tag`='$newtagstr' where `aid`='$aid'");
			}
		}
	}
	$DB->query("update ".DB_PREFIX."tag set tag='$newitem' where tag='$olditem' and hostid='$hostid'");
}

//删除Tag
function removetag($tagname)
{
	global $hostid,$DB;
	$aids=gettagids($tagname);
	if($aids)
	{
		$query=$DB->query('Select tag,aid from '.DB_PREFIX."article where aid in ($aids) and `hostid`='$hostid'");
		while($result=$DB->fetch_array($query))
		{
			$tagstr=$result['tag'];
			$aid=$result['aid'];	

			$newtagstr='';
			$oldtagarr=explode(',',$tagstr);
			foreach($oldtagarr as $oldtag) 
			{
				if($oldtag!=$tagname) $newtagstr.=','.$oldtag;
			}
			if($newtagstr) 
			{
				$newtagstr=substr($newtagstr,1);
			}
			$DB->query('update '.DB_PREFIX."article set `tag`='$newtagstr' where `aid`='$aid'");
		}
	}
	$DB->query("Delete from ".DB_PREFIX."tag where tag='$tagname' and hostid='$hostid'");
}
	
//得到ids
function gettagids($tagname)
{
	global $hostid,$DB;
	$tagsql="SELECT articleid FROM ".DB_PREFIX."tag WHERE tag='$tagname' and hostid='$hostid'";
	$tagquery=$DB->query($tagsql);
	$aidarr=array();
	while($taginfo=$DB->fetch_array($tagquery))
	{
		$aidarr[]=$taginfo['articleid'];
	}
	$aids='';
	if(!empty($aidarr))
	{
		$aids=implode(',',$aidarr);
	}
	return  $aids;
}