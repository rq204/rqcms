<?php
$is404=false;
if(!$arg1) $is404=true;

if(!$is404)
{
    $argArr=explode('.',$arg1);
    if(count($argArr)!=2) $is404=true;
    else
    {
        if($argArr[1]!='html') $is404=true;
    }
}

if(!$is404)
{
    $articleid=intval($argArr[0]);
    if($articleid==0) $is404=true;
}

if(!$is404)
{
    $article=$DB->fetch_first("select a.*,c.content from {$dbprefix}article a left join {$dbprefix}content c on c.articleid={$articleid} where a.aid={$articleid}");
    if(empty($article)) doAction('article_not_find');//插件可以处理找不到文章的结果
    if(empty($article)) $is404=true;
    else
    {
        $article=fillArticle($article);
    }
}

if($is404)
{
    $views='404';
    $tempView=RQ_DATA.'/themes/'.$theme.'/'.$views.'.php';//风格模板文件
    header('HTTP/1.1 404 Not Found');
}
else
{
    //缓存，先判断是否超时的
    cacheControl($article['modified']);

    //分类信息
    $cateArr=$category[$article['cateid']];

    doAction('article_before_view');
}