<?php
/*
Plugin Name: 简繁体转换
Plugin URI: https://oogami.name/project/wpcc/
Description: 为每个页面增加简繁体中文的链接，默认是ch-hans。原作者oogami
Version: 1.1.16
Author: rq204
Author URI: https://rqcms.com/
Thanks URL: https://oogami.name/project/wpcc/
*/

require(RQ_DATA . '/plugins/chinese-conversion/ZhConversion.php');

$wpcc_langs = array(
	'zh-hans' => array('zhconversion_hans', 'zh2Hans', '简体中文'),
	'zh-hant' => array('zhconversion_hant', 'zh2Hant', '繁體中文'),
	'zh-cn' => array('zhconversion_cn', 'zh2CN', '大陆简体'),
	'zh-hk' => array('zhconversion_hk', 'zh2HK', '港澳繁體'),
	'zh-sg' => array('zhconversion_sg', 'zh2SG', '马新简体'),
	'zh-tw' => array('zhconversion_tw', 'zh2TW', '台灣正體'),
	'zh-mo' => array('zhconversion_hk', 'zh2MO', '澳門繁體'),
	'zh-my' => array('zhconversion_sg', 'zh2MY', '马来西亚简体'),
	//'zh' => array('zhconversion_zh', 'zh2ZH', '中文'),
);

addAction('before_output','chinese_conversion_before_output');
addAction('init','chinese_init');
addAction('before_router','chinese_before_router');

function chinese_init()
{
	global $wpcc_langs,$wpcc_variant,$setting;
	$request_url=trim($_SERVER['REQUEST_URI'],'/');
	$request_arr=explode('/',$request_url);
	if(isset($wpcc_langs[$request_arr[0]]))
	{
		$wpcc_variant=$request_arr[0];
		unset($request_arr[0]);
		$_SERVER['REQUEST_URI']=implode('/',$request_arr);
	}
}

$wpcc_variant='zh-hans';

function chinese_before_router()
{
	global $request_arr,$wpcc_langs,$headArr,$views;
	if($views=='404') return;
	$pureurl=implode('/',$request_arr);
	$temp='<link rel="alternate" href="/zh-sg/'.$pureurl.'" hreflang="zh-SG" />';
	foreach($wpcc_langs as $key=>$lan)
	{
		if($key=='zh-hans') continue;
		$to=explode('-',$key)[1];
		$link=str_replace('sg',$to,$temp);
		$link=str_replace('SG',strtoupper($to),$link);
		$headArr[]=$link;
	}
	$headArr[]='<link rel="alternate" href="/'.$pureurl.'" hreflang="x-default" />';
}

function chinese_conversion_before_output()
{
	global $output,$fix_filemap,$wpcc_langs,$request_arr,$wpcc_variant,$setting,$category;
	if( !empty($wpcc_variant) && isset($wpcc_langs[$wpcc_variant]))
	{
		$output= zhconversion($output, $wpcc_variant);
		if($wpcc_variant!='zh-hans')
		{
			foreach($fix_filemap as $filemap)
			{
				if($filemap=='admin') continue; 
				$output=str_replace("\"/{$setting['option'][$filemap]}","\"/{$wpcc_variant}/{$setting['option'][$filemap]}",$output);
				$output=str_replace("'/{$setting['option'][$filemap]}","'/{$wpcc_variant}/{$setting['option'][$filemap]}",$output);
			}
			foreach($category as $cate)
			{
				$output=str_replace("\"/{$cate['url']}","\"/{$wpcc_variant}/{$cate['url']}",$output);
				$output=str_replace("'/{$cate['url']}","'/{$wpcc_variant}/{$cate['url']}",$output);
			}
		}

		//html
		$output=str_replace('<html>',"<html lang={$wpcc_variant}>",$output);
		$output=str_replace('<html amp lang="zh">',"<html amp lang=\"{$wpcc_variant}\">",$output);
	}
}

function zhconversion($str, $variant) {
	global $wpcc_langs;
	return $wpcc_langs[$variant][0]($str);
}

function zhconversion_hant($str) {
	global $zh2Hant;
	return strtr($str, $zh2Hant );
}

function zhconversion_hans($str) {
	global $zh2Hans;
	return strtr($str, $zh2Hans);
}

function zhconversion_cn($str) {
	global $zh2Hans, $zh2CN;
	return strtr(strtr($str, $zh2CN), $zh2Hans);
}

function zhconversion_tw($str) {
	global $zh2Hant, $zh2TW;
	return strtr(strtr($str, $zh2TW), $zh2Hant);
}

function zhconversion_sg($str) {
	global $zh2Hans, $zh2SG;
	return strtr(strtr($str, $zh2SG), $zh2Hans);
}

function zhconversion_hk($str) {
	global $zh2Hant, $zh2HK;
	return strtr(strtr($str, $zh2HK), $zh2Hant);
}

function zhconversion_zh($str) {
	return $str;
}