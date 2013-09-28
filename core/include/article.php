<?php
//这里对POST的数据进行处理,成功返回fasle
function getDateLine()
{
	global $_POST;
	$newyear     = intval($_POST['newyear']);
	$newmonth    = intval($_POST['newmonth']);
	$newday      = intval($_POST['newday']);
	$newhour     = intval($_POST['newhour']);
	$newmin      = intval($_POST['newmin']);
	$newsec      = intval($_POST['newsec']);
	return mktime($newhour,$newmin,$newsec,$newmonth,$newday,$newyear);
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
	return explode(',',$tag);
}

//清空cookie里保存的数据
function clearCookie()
{
	setcookie('cid','');
	setcookie('title','');
	setcookie('excerpt','');
	//setcookie('content','');
	setcookie('keywords','');
}

// 暂时把提交的数据保存到COOKIE	
function saveCookie()
{
	global $cid,$title,$excerpt,$content,$keywords,$timestamp;
	$cookietime=$timestamp+2592000;
	setcookie('cid',$cid,$cookietime);
	setcookie('title',$title,$cookietime);
	setcookie('excerpt',$excerpt,$cookietime);
	//setcookie('content',$content,$cookietime);//dreamhost会出错，不知道为什么,12.7.14
	setcookie('keywords',$keywords,$cookietime);
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

function getAttach()
{
	global $_POST,$_FILES,$host,$cid;
	$attachments=array();//name, tmp_name,size,type,error
	if(isset($_FILES['attach']) && is_array($_FILES['attach'])) {
		foreach($_FILES['attach'] as $key => $varArr) {
			foreach($varArr as $id => $val) {
				$attachments[$id][$key] = $val;
			}
		}
	}

	foreach($attachments as $id=>$val)
	{
		if(empty($val['name'])) unset($attachments[$id]);
		else $attachments[$id]['localid']=$id;
	}
		
	foreach($attachments as $id=>$val)
	{
		if(isset($_POST['score'])&&is_array($_POST['score'])) $attachments[$id]=$val;
	}
	
	foreach($attachments as $id=>$val)
	{	
		$attach_dir='';
		switch($host['attach_save_dir'])
		{
			case 0: $attach_dir = ''; break; //全部放一起
			case 1: $attach_dir = 'cate_'.$cid; break; //按分类放
			case 2: $attach_dir = 'date_'.date('Ym'); break; //按月放
			case 3: $attach_dir = 'ext_'.$ext; break; //按文件类型
		}
		//创建目录
		$savedir=RQ_DATA.'/files/'.$attach_dir.'/';
		if(!is_dir($savedir)) 
		{
			mkdir($savedir, 0777);
			@chmod($savedir, 0777);
		}

		$ext=strtolower(trim(pathinfo($val['name'], PATHINFO_EXTENSION)));
		$fnamehash = md5(uniqid(microtime()));
		$filepath = $attach_dir.'/'.$fnamehash.'.'.$ext.'.file';
		$attachment=RQ_DATA.'/files/'.$filepath;
		if(!move_uploaded_file($val['tmp_name'],$attachment))
		{
			redirect('上传附件发生意外错误!');
		}
		$attachments[$id]['filepath']=$filepath;
		$attachments[$id]['filename']=$attachments[$id]['name'];
		$attachments[$id]['filetype']=$attachments[$id]['type'];
		unset($attachments[$id]['type']);
		unset($attachments[$id]['name']);
		unset($attachments[$id]['error']);
		unset($attachments[$id]['tmp_name']);

		$tmp_filesize = @filesize($attachment);
		if ($tmp_filesize != $attachments[$id]['size']) {
			@unlink($attachment);
			redirect('文件大小错误上传附件发生意外错误!文件大小错误');
		}
		$attachments[$id]['filesize']=$attachments[$id]['size'];
		unset($attachments[$id]['size']);
		
		// 判断是否为图片格式
		$isimage = '0';
		if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png'))) 
		{
			if ($imginfo=@getimagesize($attachment)) {
				if (!$imginfo[2] || !$imginfo['bits']) {
					@unlink($attachment);
					redirect('上传的文件不是一个有效的GIF或者JPG文件!');
				} else {
					$isimage = '1';
				}
			}
			$attachments[$id]['isimage']=$isimage;

			//水印
			$watermark_size = explode('x', strtolower($host['watermark_size']));				
			if($isimage && $host['watermark'] && count($watermark_size)==2&&$imginfo[0] > $watermark_size[0]*2 && $imginfo[1] > $watermark_size[1]*2 && $tmp_filesize < 2048000) 
			{
				$waterfile=RQ_DATA.'/watermark/'.$host['host'].'.png';
				if(file_exists($waterfile)) coreaddwatermark($attachment,$waterfile,$host['watermark_pos'],$host['watermark_trans']);
			}
		}
		if(!isset($attachments[$id]['isimage'])) $attachments[$id]['isimage']='0';
	}
	return $attachments;
}
