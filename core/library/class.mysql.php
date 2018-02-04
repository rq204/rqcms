<?php
class DB_MySQL  {

	var $querycount = 0;
    var $mysqli = null;
	//错误描述
	function geterrdesc() {
		return $this->mysqli->error;
	}

	//错误编号
	function geterrno() {
		return $this->mysqli->errno;
	}

	//插入生成的自增id
	function insert_id() {
		 return mysqli_insert_id($this->mysqli);
	}

	//连接数据库
	function connect($servername, $dbusername, $dbpassword, $dbname) {
		$this->mysqli = new mysqli($servername, $dbusername, $dbpassword, $dbname); 
		 if ($this->mysqli->connect_error) {
            $this->halt($this->mysqli->connect_error);
        }
		$this->mysqli->set_charset("utf8");
	}

	//从结果集中取得一行作为数字数组或关联数组
	function fetch_array($query, $result_type = MYSQLI_ASSOC) {
		return mysqli_fetch_array($query, $result_type);
	}

	//查询并得到一个结果集
	function query($sql) {
		$query = $this->mysqli->query($sql);
        if (!$query) {
            $this->halt("查询失败:\n$sql");
        }
		$this->querycount++;
		return $query;
	}

	//从结果集中取得一行，并作为枚举数组返回
	function fetch_row($query) {
		$arr = mysqli_fetch_row($query);
		return $arr;
	}

	//使用sql查询获取第一行
	function fetch_first($sql) {
		$result = $this->query($sql);
		$record = $this->fetch_array($result);
		return $record;
	}

	//回结果集中行的数目
	function num_rows($query) {
		return $this->mysqli->num_rows;
	}
	
	//释放查询资源
	function free_result($query) {
		return $query->close();
	}

	//mysql版本号
	function version() {
		return $this->mysqli->server_info;
	}

	//关闭连接
	function close() {
		return mysqli_close($this->mysqli);
	}

	//出错显示
	function halt($msg, $sql=''){
		global $username,$timestamp,$onlineip;

		if ($sql) {
			@$fp = fopen(RQ_DATA.'/dberror.php', 'a');
			@fwrite($fp, "$username\t$timestamp\t$onlineip\t".basename($_SERVER["SCRIPT_FILENAME"])."\t".htmlspecialchars($this->geterrdesc())."\t".str_replace(array("\r", "\n", "\t"), array(' ', ' ', ' '), trim(htmlspecialchars(str_replace("\t",'',$sql))))."\n");
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
		if($sql) $message .= "<b>Mysql error sql</b>: ".htmlspecialchars($sql)."\n<br />";
		$message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
		$message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br />";

		$message .= "</body>\n</html>";
		echo $message;
		exit;
	}

}
?>