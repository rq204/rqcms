<?php
doAction('js_before_view');//输出js前的处理
exit('开发中');
//生一个日历

//建立日志时间写入数组
	$query = $DB->query("SELECT date FROM ".DB_PREFIX."blog WHERE hide='n' and type='blog'");
	while ($date = $DB->fetch_array($query)){
		$logdate[] = gmdate("Ymd", $date['date']);
	}
	
		if (isset($_GET['record'])){
		$n_year = substr(intval($_GET['record']),0,4);
		$n_year2 = substr(intval($_GET['record']),0,4);
		$n_month = substr(intval($_GET['record']),4,2);
		$year_month = substr(intval($_GET['record']),0,6);
	}
	
	//获取某个栏目多少数据
	
	
	//获取最新最热的文章