<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
// 获得文件扩展名

function getextension($filename) {
	$pathinfo = pathinfo($filename);
	return $pathinfo['extension'];
}


// 删除附件
function removeattachment($query) {
	global $DB;
	$delatt=array();
	while ($att = $DB->fetch_array($query)) {
		$delatt[]=$att['filepath'];
	}

	foreach($delatt as $delfile)
	{
		$dfile=RQ_DATA.'/files/'.$delfile;
		if(file_exists($dfile)){
			@chmod ($dfile, 0777);
			@unlink($dfile);
		}
	}
}
?>