<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
if(!$action) $action = 'mysqlinfo';
$backupdir = RQ_DATA.'/backup';

$tables = array(
	DB_PREFIX.'article',
	DB_PREFIX.'attachment',
	DB_PREFIX.'category',
	DB_PREFIX.'comment',
	DB_PREFIX.'content',
	DB_PREFIX.'link',
	DB_PREFIX.'filemap',
	DB_PREFIX.'host',
	DB_PREFIX.'plugin',
	DB_PREFIX.'tag',
	DB_PREFIX.'user',
	DB_PREFIX.'var',
	DB_PREFIX.'log',
	DB_PREFIX.'redirect'
);

//下载备份文件
if ($action == 'downsql') {
	$sqlfile=$_GET['sqlfile'];
	$sqlpath=$backupdir.'/'.$sqlfile;
	if(is_file($sqlpath))
	{
		ob_end_clean();
		header('Content-Type: text/x-sql');
		header('Expires: '. gmdate('D, d M Y H:i:s', $timestamp) . ' GMT');
		header('Content-Disposition: attachment; filename='.$sqlfile);
		if (preg_match("/MSIE ([0-9].[0-9]{1,2})/", $_SERVER['HTTP_USER_AGENT'])){
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Pragma: no-cache');
			header('Last-Modified: '. gmdate('D, d M Y H:i:s', $timestamp) . ' GMT');
		}
		readfile($sqlpath);
		exit;
	}
	else
	{
		redirect('备份文件'.$sqlfile.'没有找到', 'admin.php?file=database&action=filelist');
	}
}

if($action == 'checkresume') $sqlfile=$_GET['sqlfile'];

// 恢复数据库文件
if ($action == 'resume') {
	$sqlfile = $_POST['sqlfile'];
	$file = $backupdir.'/'.$sqlfile;
	$path_parts = pathinfo($file);
	if (strtolower($path_parts['extension']) != 'sql') {
		redirect('只能恢复SQL文件!','admin.php?file=database&action=filelist');
	}
	checkSqlFileInfo($file);
	bakindata($file);
	//更新缓存
	redirect('数据恢复成功','admin.php?file=database&action=filelist');
}

/**
 * 检查备份文件头信息
 * 
 * @param file $sqlfile
 */
function checkSqlFileInfo($sqlfile) {
	$fp = @fopen($sqlfile, 'r');
	if ($fp){
		$dumpinfo = array();
		$line = 0;
		while (!feof($fp)){
			$dumpinfo[] = fgets($fp, 4096);
			$line++;
			if ($line == 3) break;
		}
		fclose($fp);
		if (!empty($dumpinfo)){
			if (preg_match('/#version:rqcms '. RQ_VERSION .'/', $dumpinfo[0]) === 0) {
				redirect('导入失败！该备份文件只能导入到' . RQ_VERSION . '版本的RQCMS站点!');
			}
			if (preg_match('/#tableprefix:'. DB_PREFIX .'/', $dumpinfo[2]) === 0) {
				redirect('导入失败！备份文件中的数据库前缀与当前系统数据库前缀不匹配' . $dumpinfo[2]);
			}
		} else {
			redirect('导入失败！该备份文件不是 RQCMS 的备份文件!');
		}
	} else {
		redirect('导入失败！读取文件失败');
	}
}


// 备份操作
if ($action == 'dobackup') {
	$sqlfilename = RQ_DATA.'/backup/'.$_POST['filename'].'.sql';
	$bakplace=$_POST['bakplace'];
	$sqldump = '';
	
	foreach($tables as $table)
	{
		$sqldump .= dataBak($table);
	}

	if(trim($sqldump)){
	$dumpfile = '#version:rqcms '. RQ_VERSION . "\n";
	$dumpfile .= '#date:' . gmdate('Y-m-d H:i',$timestamp+$host['server_timezone']*3600) . "\n";
	$dumpfile .= '#tableprefix:' . DB_PREFIX . "\n\n";
	$dumpfile .= $sqldump;
	$dumpfile .= "\n#the end of backup";
	if($bakplace == 'local'){
		ob_end_clean();
		header('Content-Type: text/x-sql');
		header('Expires: '. gmdate('D, d M Y H:i:s', $timestamp) . ' GMT');
		header('Content-Disposition: attachment; filename=RQCMS_'. gmdate('Ymd', $timestamp+$host['server_timezone']*3600).'.sql');
		if (preg_match("/MSIE ([0-9].[0-9]{1,2})/", $_SERVER['HTTP_USER_AGENT'])){
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Pragma: no-cache');
			header('Last-Modified: '. gmdate('D, d M Y H:i:s', $timestamp) . ' GMT');
		}
		echo $dumpfile;
		exit;
	} else {
		@$fp = fopen($sqlfilename, 'w+');
		if ($fp)
		{
			@flock($fp, 3);
			if(@!fwrite($fp, $dumpfile))
			{
				@fclose($fp);
				redirect('备份失败。备份目录('.RQ_DATA.'/backup)不可写');
			}else{
				redirect("数据库备份成功",'admin.php?file=database&action=filelist');
			}
		}else{
			redirect('创建备份文件失败。备份目录('.RQ_DATA.'/backup)不可写');
		}
	}
	}else{
		redirect('数据表没有任何内容','admin.php?file=database&action=filelist');
	}
}// 备份操作结束


//批量删除备份文件
if($action == 'deldbfile') {
	!isset($_POST['sqlfiles'])&&redirect('未选择任何文件','admin.php?file=database&action=filelist');
	$sqlfiles=$_POST['sqlfiles'];
	$selected = count($sqlfiles);
	$succ = $fail = 0;
    foreach ($sqlfiles AS $filen=>$value) {
		$file=$backupdir.'/'.$filen;
		if (file_exists($file)) {
			@chmod($file, 0777);
			if (@unlink($file)) {
				$succ++;
			} else {
				$fail++;
			}
		} else {
			redirect($filen.' 文件已不存在', 'admin.php?file=database&action=filelist');
		}
    }
    redirect('删除数据文件操作完毕,删除'.$selected.'个,成功'.$succ.'个,失败'.$fail.'个.', 'admin.php?file=database&action=filelist',5);
}

// 数据库维护操作
if($action == 'dotools') {
	$doname = array(
		'check' => '检查',
		'repair' => '修复',
		'analyze' => '分析',
		'optimize' => '优化'
	);
	$dodb = $tabledb = array();
	foreach ($do AS $value) {
		$dodb[] = array('do'=>$value,'name'=>$doname[$value]);
		foreach ($tables AS $table) {
			if ($DB->query($value.' TABLE '.$table)) {
				$result = '<span class="yes">成功</span>';
			} else {
				$result = '<span class="no">失败</span>';
			}
			$tabledb[] = array('do'=>$value,'table'=>$table,'result'=>$result);
		}
	}
	$subnav = '数据库维护';
}// 数据库维护操作结束

if (in_array($action, array('backup', 'tools'))) {
	if ($action == 'backup') {
		$backuppath = date('Y-m-d',$timestamp).'_'.substr(md5($timestamp),0,8);
		$tdtitle = '备份数据库';
		$act = 'dobackup';
	} else {
		$tdtitle = '数据库维护';
		$act = 'dotools';
	}
	$subnav = ''.$tdtitle;
}//backup

// 数据库信息
if ($action == 'mysqlinfo') {
	$mysql_version = mysql_get_server_info();
	$mysql_runtime = '';
	$query = $DB->query("SHOW STATUS");
	while ($r = $DB->fetch_array($query)) {
		if (preg_match("/^uptime/i", $r['Variable_name'])){
			$mysql_runtime = $r['Value'];
		}
	}
	$mysql_runtime = format_timespan($mysql_runtime);

	$query = $DB->query("SHOW TABLE STATUS");
	$RQCMS_table_num = $RQCMS_table_rows = $RQCMS_data_size = $RQCMS_index_size = $RQCMS_free_size = 0;
	$other_table_num = $other_table_rows = $other_data_size = $other_index_size = $other_free_size = 0;
	$RQCMS_table = $other_table = array();
	while($table = $DB->fetch_array($query)) {
		if(in_array($table['Name'],$tables)) {
			$RQCMS_data_size = $RQCMS_data_size + $table['Data_length'];
			$RQCMS_index_size = $RQCMS_index_size + $table['Index_length'];
			$RQCMS_table_rows = $RQCMS_table_rows + $table['Rows'];
			$RQCMS_free_size = $RQCMS_free_size + $table['Data_free'];
			$table['Data_length'] = sizecount($table['Data_length']);
			$table['Index_length'] = sizecount($table['Index_length']);
			$table['Data_free'] = sizecount($table['Data_free']);
			$RQCMS_table_num++;
			$RQCMS_table[] = $table;
		} else {
			$other_data_size = $other_data_size + $table['Data_length'];
			$other_index_size = $other_index_size + $table['Index_length'];
			$other_table_rows = $other_table_rows + $table['Rows'];
			$other_free_size = $other_free_size + $table['Data_free'];
			$table['Data_length'] = sizecount($table['Data_length']);
			$table['Index_length'] = sizecount($table['Index_length']);
			$table['Data_free'] = sizecount($table['Data_free']);
			$other_table_num++;
			$other_table[] = $table;
		}
	}
	$RQCMS_data_size = sizecount($RQCMS_data_size);
	$RQCMS_index_size = sizecount($RQCMS_index_size);
	$RQCMS_free_size = sizecount($RQCMS_free_size);
	$other_data_size = sizecount($other_data_size);
	$other_index_size = sizecount($other_index_size);
	$other_free_size = sizecount($other_free_size);
	unset($table);
	$subnav = '数据库信息';
}

// 管理数据文件
if ($action == 'filelist') {
	$file_i = 0;
	if(is_dir($backupdir)) {
		$dirs = dir($backupdir);
		$dbfiles = array();
		$today = date('Y-m-d',$timestamp);
		while ($file = $dirs->read()) {
		    if($file=='.' || $file=='..'|| $file=='.svn') continue;
			$filepath = $backupdir.'/'.$file;
			$pathinfo = pathinfo($filepath);
			if(is_file($filepath) && $pathinfo['extension'] == 'sql') {
				$moday = @date('Y-m-d',@filemtime($filepath));
				$mtime = @date('Y-m-d H:i',@filemtime($filepath));
				$dbfile = array(
					'filesize' => sizecount(filesize($filepath)),
					'mtime' => ($moday == $today) ? '<font color="#FF0000">'.$mtime.'</font>' : $mtime,
					'filepath' => urlencode($file),
					'bktime'=> @date('Y-m-d H:i',@filectime($filepath)),
					'filename' => htmlspecialchars($file),
				);
				$file_i++;
				$dbfiles[] = $dbfile;
			}
		}
		unset($dbfile);
		$dirs->close();
		$noexists = 0;
	} else {
		$noexists = 1;
	}
	$subnav = '数据文件管理';
} // end filelist



/**
 * 备份数据库结构和所有数据
 *
 * @param string $table 数据库表名
 * @return string
 */
function dataBak($table){
	global $DB;
	$sql = "DROP TABLE IF EXISTS $table;\n";
	$createtable = $DB->query("SHOW CREATE TABLE $table");
	$create = $DB->fetch_row($createtable);
	$sql .= $create[1].";\n\n";

	$rows = $DB->query("SELECT * FROM $table");
	$numfields = $DB->num_fields($rows);
	$numrows = $DB->num_rows($rows);
	while ($row = $DB->fetch_row($rows)){
		$comma = "";
		$sql .= "INSERT INTO $table VALUES(";
		for ($i = 0; $i < $numfields; $i++){
			$sql .= $comma."'".mysql_escape_string($row[$i])."'";
			$comma = ",";
		}
		$sql .= ");\n";
	}
	$sql .= "\n";
	return $sql;
}

// 转换时间单位:秒 to XXX
function format_timespan($seconds = '') {
	if ($seconds == '') $seconds = 1;
	$str = '';
	$years = floor($seconds / 31536000);
	if ($years > 0) {
		$str .= $years.' 年, ';
	}
	$seconds -= $years * 31536000;
	$months = floor($seconds / 2628000);
	if ($years > 0 || $months > 0) {
		if ($months > 0) {
			$str .= $months.' 月, ';
		}
		$seconds -= $months * 2628000;
	}
	$weeks = floor($seconds / 604800);
	if ($years > 0 || $months > 0 || $weeks > 0) {
		if ($weeks > 0)	{
			$str .= $weeks.' 周, ';
		}
		$seconds -= $weeks * 604800;
	}
	$days = floor($seconds / 86400);
	if ($months > 0 || $weeks > 0 || $days > 0) {
		if ($days > 0) {
			$str .= $days.' 天, ';
		}
		$seconds -= $days * 86400;
	}
	$hours = floor($seconds / 3600);
	if ($days > 0 || $hours > 0) {
		if ($hours > 0) {
			$str .= $hours.' 小时, ';
		}
		$seconds -= $hours * 3600;
	}
	$minutes = floor($seconds / 60);
	if ($days > 0 || $hours > 0 || $minutes > 0) {
		if ($minutes > 0) {
			$str .= $minutes.' 分钟, ';
		}
		$seconds -= $minutes * 60;
	}
	if ($str == '') {
		$str .= $seconds.' 秒, ';
	}
	$str = substr(trim($str), 0, -1);
	return $str;
}

/**
 * 执行备份文件的SQL语句
 *
 * @param string $filename
 */
function bakindata($filename) {
	global $DB;
	$setchar = $DB->getMysqlVersion() > '4.1' ? "ALTER DATABASE `" . DB_DATABASE . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;" : '';
	$sql = file($filename);
	if(isset($sql[0]) && !empty($sql[0]) && checkBOM($sql[0])) {
	    $sql[0] = substr($sql[0], 3);
	}
	array_unshift($sql,$setchar);
	$query = '';
	foreach($sql as $value){
		$value = trim($value);
		if(!$value || $value[0]=='#'){
			continue;
		}
		if(preg_match("/\;$/i", $value)){
			$query .= $value;
			if(preg_match("/^CREATE/i", $query)){
				$query = preg_replace("/\DEFAULT CHARSET=([a-z0-9]+)/is",'',$query);
			}
			$DB->query($query);
			$query = '';
		} else{
			$query .= $value;
		}
	}
}

/**
 * 检查文件是否包含BOM(byte-order mark)
 */
function checkBOM($contents) {
    $charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);
    if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
        return true;
    } else {
        return false;
    }
}