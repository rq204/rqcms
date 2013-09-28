<?php
// 后台管理记录

function getlog() {

	global $timestamp, $onlineip, $sax_user;

	if ($_POST['action']) {

		$action = $_POST['action'];
		$script = str_replace('job=', '', $_SERVER['QUERY_STRING']);
		writelog(SABLOG_ROOT.'cache/log/adminlog.php', "<?PHP exit('Access Denied'); ?>\t$timestamp\t".htmlspecialchars($sax_user)."\t$onlineip\t".htmlspecialchars(trim($action))."\t".htmlspecialchars(trim($script))."\n");
	}

}

// 写日至
function writelog($filename,$filedata)
 {
	@$fp=fopen($filename, 'a');
	@flock($fp, 2);
	@fwrite($fp, $filedata);
	@fclose($fp);
	@chmod($filename, 0777);
}