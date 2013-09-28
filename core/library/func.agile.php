<?php
/**
 * Seo相关的，如Url处理，缓存等
 */

/**
 * 将数组写入缓存文件
 *
 * @param string $cacheFile 要保存的文件路径
 * @param array $cacheArray 需要保存的数组
 * @return false or strlen
 */
function writeCache($cacheFile,$cacheArray)
{
	if(!is_array($cacheArray)) return false;
	$array = "<?php\nreturn ".var_export($cacheArray, true).";\n?>";
	$wirteFile = RQ_DATA.'/cache/'.$cacheFile.'.php';
	$strlen = file_put_contents($wirteFile, $array);
	@chmod($wirteFile, 0777);
	return $strlen;
}


//网址重写,重写的只是文件名,只能是相对地址,以"后就是地址,如<a href="admin.php改成 <a href="master.php
function adminRewrite($buffer)
{
	global $mapArr,$host;
	if(is_array($mapArr)&&$mapArr['file'][RQ_FILE]=='admin.php')
	{
		$left=array('action="','href="','url=');
		$add='';
		if($host['url_ext']&&$host['url_html']) $add='.'.$host['url_ext'];

		foreach($left as $lf)
		{
			$buffer=str_replace("{$lf}admin.php",$lf.RQ_FILE.$add,$buffer);
		}
	}
	return $buffer;
}

//参数重写，将浏览器传过来的参数写在程序可以识别的参数,除后台的不写外
function argRewrite()
{
	global $mapArr;
	if(is_array($mapArr)&&isset($mapArr['file'][RQ_FILE])&&is_array($mapArr['arg'][RQ_FILE]))//$mapArr['file'][RQ_FILE]!='admin.php'&&
	{
		foreach($mapArr['arg'][RQ_FILE] as $new=>$old)
		{
			if(isset($_GET[$old])) unset($_GET[$old]);
			if(isset($_GET[$new]))
			{
				$_GET[$old]=$_GET[$new];
				unset($_GET[$new]);
			}
		}
	}
}

//生成新的网址
function mkUrl($file,$url,$page=0)
{
	global $mapArr,$host;
	$url=urlencode($url);
	if(is_array($mapArr)&&!empty($mapArr))
	{	
		foreach($mapArr['file'] as $nfile=>$ofile)
		{
			if($ofile==$file)
			{
				$aurl='url';
				$purl='page';
			    if(!empty($mapArr['arg'][$nfile]))
				{
					$fs= array_flip($mapArr['arg'][$nfile]);
					if(isset($fs['url'])) $aurl=$fs['url'];
					if(isset($fs['page'])) $purl=$fs['page'];
				}
				if(!$host['url_html'])
				{
					switch($file)
					{
						case 'category.php':
						case 'article.php':
							if($page<2) 
								return $nfile.'?'.$aurl.'='.$url;
							else return $nfile.'?'.$aurl.'='.$url.'&'.$purl.'='.$page;
						break;
						case 'search.php':
							if($page<2)
							{
								if(!$url) return $nfile;
								else return $nfile.'?'.$aurl.'='.$url;
							}							
							else return $nfile.'?'.$aurl.'='.$url.'&'.$purl.'='.$page;
						case 'profile.php':
						case 'tag.php':
						case 'comment.php':
						case 'admin.php':
						case 'rss.php':
							if($url=='') return $nfile;
							else return $nfile.'?'.$aurl.'='.$url;
						default:
							return $nfile.'?'.$aurl.'='.$url;
					}
				}
				else//纯静态的，默认三个参数url,page,more
				{
					$add=!$host['url_ext']?'':'.'.$host['url_ext'];
					$fs= array_flip($mapArr['arg'][$nfile]);
					switch($file)
					{
						case 'category.php':
						case 'article.php':
							if($page<2) 
									return $nfile.'/'.$url.$add;
								else return $nfile.'/'.$url.'/'.$page.$add;
						break;
						case 'search.php':
							if($url)
							{
								if($page<2) 
										return $nfile.'/'.$url.$add;
									else return $nfile.'/'.$url.'/'.$page.$add;
							}
							else return $nfile.$add;
						case 'profile.php':
						case 'tag.php':
						case 'comment.php':
						case 'admin.php':
						case 'rss.php':
							if($url=='') return $nfile.$add;
							else return $nfile.'/'.$url.$add;
						default:
							return $nfile.'/'.$url.$add;
					}
				}
			}
		
		}
	}
	return '';
}

function message($msg,$returnurl='')
{
	global $theme,$host;
	if(!$returnurl) $returnurl='http://'.$host['host'];
	include RQ_DATA."/themes/$theme/message.php";
	exit();
}

function showArticle($article)
{
	global $host,$cateArr;
	$article['month'] = date('M', $article['dateline']);
	$article['day'] = date('d', $article['dateline']);
	$article['dateline']=date($host['time_article_format'], $article['dateline']);
	$article['lastmodified']=$article['modified']+(isset($article['comment'])?$article['comment']:0);
	$article['modified']=date($host['time_article_format'], $article['modified']);
	$article['aurl'] = mkUrl('article.php',$article['url'],0);
	$article['curl'] = mkUrl('category.php',$cateArr[$article['cateid']]['url'],0);
	$article['attachments']=$article['attachments'];
	return $article;
}

function cacheControl($lastmodified)
{
	$lastmodified=gmdate('D, d M Y H:i:s',$lastmodified).' GMT';
	if(array_key_exists('HTTP_IF_MODIFIED_SINCE',$_SERVER))
	{
		if($_SERVER['HTTP_IF_MODIFIED_SINCE']==$lastmodified)
		{
			header('HTTP/1.0 304 Not Modified');
			exit;
		}
	}
	else
	{
		header("Cache-Control: max-age=259200");
		header("Last-Modified: ".$lastmodified); //Fri, 31 Oct 2008 02:14:04 GMT
	}
}