<?php
function pagination($pnums,$page,$url){
	$re = '';
	for ($i = $page-5;$i <= $page+5 && $i <= $pnums; $i++){
		if ($i > 0){
			if ($i == $page){
				$re .= " <span>$i</span> ";
			} else {
				$re .= " <a href=\"/$url/$i\">$i</a> ";
			}
		}
	}

	if ($page > 6) $re = "<a href=\"/{$url}\" title=\"首页\">&laquo;</a><em>...</em>$re";
	if ($page + 5 < $pnums) $re .= "<em>...</em> <a href=\"/$url/$pnums\" title=\"尾页\">&raquo;</a>";
	if ($pnums <= 1) $re = '';
	return $re;
}


$homeurl='/';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<title><?php echo $title; ?></title>
<meta content="text/html; charset=utf-8" http-equiv=Content-Type>
<?php viewhead(); ?>
</head>
<body>
