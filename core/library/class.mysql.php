<?php
class DB_MySQL  {

	var $querycount = 0;

	function geterrdesc() {
		return mysql_error();
	}

	function geterrno() {
		return intval(mysql_errno());
	}

	function insert_id() {
		$id = mysql_insert_id();
		return $id;
	}

	function connect($servername, $dbusername, $dbpassword, $dbname, $usepconnect=0) {
		if($usepconnect) {
			if(!@mysql_pconnect($servername, $dbusername, $dbpassword)) {
				$this->halt('数据库链接失败');
			}
		} else {
			if(!@mysql_connect($servername, $dbusername, $dbpassword)) {
				$this->halt('数据库链接失败');
			}
		}

		if($this->version() > '4.1') {
			$charset=$dbcharset='utf8';
			if(!$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8'))) {
				$dbcharset = str_replace('-', '', $charset);
			}
			if($dbcharset) {
				//mysql_query("SET NAMES '$dbcharset'");
				mysql_query("SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary;");
			}
		}

		if($this->version() > '5.0.1') {
			mysql_query("SET sql_mode=''");
		}
		if($dbname) {
			$this->select_db($dbname);
		}
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function query($sql, $type = '') {
		//echo "<div style=\"text-align: left;\">".htmlspecialchars($sql)."</div>";
		/*
		遇到问题时用这个来检查SQL执行语句
		$fp = fopen('sqlquerylog.txt', 'a');
		flock($fp, 2);
		fwrite($fp, $sql."\n");
		fclose($fp);
		*/
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql)) && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querycount++;
		return $query;
	}
	
	function unbuffered_query($sql) {
		$query = $this->query($sql, 'UNBUFFERED');
		return $query;
	}

	function select_db($dbname) {
		return mysql_select_db($dbname);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_first($sql) {
		$result = $this->query($sql);
		$record = $this->fetch_array($result);
		return $record;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}
	
	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}
	
	function free_result($query) {
		$query = mysql_free_result($query);
		return $query;
	}

	function version() {
		return mysql_get_server_info();
	}

	function close() {
		return mysql_close();
	}

	function halt($msg, $sql=''){
		global $username,$timestamp,$onlineip;

		if ($sql) {
			@$fp = fopen(RQ_DATA.'/logs/dberror.php', 'a');
			@fwrite($fp, "$username\t$timestamp\t$onlineip\t".basename(RQ_FILE)."\t".htmlspecialchars($this->geterrdesc())."\t".str_replace(array("\r", "\n", "\t"), array(' ', ' ', ' '), trim(htmlspecialchars(str_replace("\t",'',$sql))))."\n");
			@fclose($fp);
		}

		$message = "<html>\n<head>\n";
		$message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
		$message .= "<style type=\"text/css\">\n";
		$message .=  "body,p,pre {\n";
		$message .=  "font:12px Verdana;\n";
		$message .=  "}\n";
		$message .=  "</style>\n";
		$message .= "</head>\n";
		$message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";

		$message .= "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
		$message .= "<b>Mysql error description</b>: ".htmlspecialchars($this->geterrdesc())."\n<br />";
		$message .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br />";
		$message .= "<b>Mysql error sql</b>: ".htmlspecialchars($sql)."\n<br />";
		$message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
		$message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br />";

		$message .= "</body>\n</html>";
		echo $message;
		exit;
	}
	
	function getMysqlVersion()
	{
		return mysql_get_server_info();
	}
}
?>