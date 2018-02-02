<?php
// 检查链接URL是否符合逻辑
function checkurl($url,$allownull=1) 
{
	if($url) 
	{
		if (!preg_match("#^(http|news|https|ftp|ed2k|rtsp|mms)://#", $url)) 
		{
			$result .= '网站URL错误.<br />';
			return $result;
		}
		$key = array("\\",' ',"'",'"','*',',','<','>',"\r","\t","\n",'(',')','+',';');
		foreach($key as $value)
		{
			if (strpos($url,$value) !== false){ 
				$result .= '网站URL错误.<br />';
				return $result;
			}
		}
	} else {
		if (!$allownull) {
			$result .= '网站URL不允许为空.<br />';
			return $result;
		}
	}
}

//转换字符
function char_cv($string) {
	$string = htmlspecialchars(addslashes($string));
	return $string;
}


// 分页函数

function multi($num, $perpage, $curpage, $mpurl) {
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if($num > $perpage) {
		$page = 10;
		$offset = 5;
		$pages = @ceil($num / $perpage);
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $page - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $curpage - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1">第一页</a> ' : '').($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'">上一页</a> ' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? $i.' ' : '<a href="'.$mpurl.'page='.$i.'">['.$i.']</a> ';
		}
		$multipage .= ($curpage < $pages ? '<a href="'.$mpurl.'page='.($curpage + 1).'">下一页</a>' : '').($to < $pages ? ' <a href="'.$mpurl.'page='.$pages.'">最后一页</a>' : '');
		$multipage = $multipage ? '页: '.$multipage : '';
	}
	return $multipage;
}

//目录的实际大小
function dirsize($dir) { 
	$dh = @opendir($dir);
	$size = 0;
	while($file = @readdir($dh)) {
		if ($file != '.' && $file != '..') {
			$path = $dir.'/'.$file;
			if (@is_dir($path)) {
				$size += dirsize($path);
			} else {
				$size += @filesize($path);
			}
		}
	}
	@closedir($dh);
	return $size;
}

//目录个数
function dircount($dir) { 
	$dh = @opendir($dir);
	$count = 0;
	while($file = @readdir($dh)) {
		if ($file != '.' && $file != '..') {
			$path = $dir.'/'.$file;
			if (@is_dir($path)) {
				$count++;
			}
		}
	}
	@closedir($dh);
	return $count;
}


function sizecount($filesize) {

	if($filesize >= 1073741824) {

		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';

	} elseif($filesize >= 1048576) {

		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';

	} elseif($filesize >= 1024) {

		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';

	} else {

		$filesize = $filesize . ' Byte';

	}
	return $filesize;
}