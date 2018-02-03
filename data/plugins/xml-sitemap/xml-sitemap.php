<?php
/*
Plugin Name: xml sitemap
Version: 3.0.1801
Description: 该插件提供google-sitemap。
Author: RQ204
Author URL: http://www.rqcms.com
*/

addAction('change_views','xml_change_views');
function xml_change_views()
{
    global $views,$request_arr,$category,$DB,$dbprefix,$setting;
    if($views=='404'&&strlen($request_arr[0])>7&&substr($request_arr[0],0,7)=='sitemap'&&substr($request_arr[0],-4)=='.xml')
    {
       //1.首页,sitemap.xml 包含整站sitemap-misc.xml，分类sitemap-tax-category.xml和最所有月份的最后更新时间,不显示Change frequency
       //2.整站页sitemap-misc.xml，权重100%，Daily更新  
       //3.分类sitemap-tax-category.xml,Weekly更新，权重30
       //4.月份类的sitemap-2018-02.xml，Monthly更新，60权重
       header('Content-Type: text/xml; charset=utf-8');
       include(dirname(__file__).'/WebSitemapGenerator.php');
       $xml=new WebSitemapGenerator(RQ_HTTP.RQ_HOST);
       if($request_arr[0]=='sitemap.xml')
       {
           $item=new WebSitemapItem('/sitemap-misc.xml');
           $xml->addItem($item);
           $item=new WebSitemapItem('/sitemap-tax-category.xml');
           $xml->addItem($item);
           //	<lastmod>2017-12-31T23:59:44+00:00</lastmod>
            //为了效率，忽略修改时间
            $query=$DB->query("SELECT DISTINCT YEAR(dateline) AS `year`, MONTH(dateline) AS `month`, MAX(dateline) AS last_mod, count(aid) AS posts FROM {$dbprefix}article GROUP BY YEAR(dateline), MONTH(dateline) ORDER BY dateline DESC");
            while($data=$DB->fetch_array($query))
            {
                $item=new WebSitemapItem("/sitemap-{$data['year']}-{$data['month']}.xml");
                $item->setLastModified($data['last_mod']);
                $xml->addItem($item);
            }
       }
       else if($request_arr[0]=='sitemap-misc.xml')
       {
           //  100%	Daily	2018-02-03 08:24
           $item=new WebSitemapItem('/');
           $timearr=$DB->fetch_first("select `modified` from {$dbprefix}article order by aid desc limit 1");
           if($timearr)
           {
                $item->setLastModified($timearr['modified']);
           }
           $item->setChangeFrequency('daily');
           $item->setPriority(1);
           $xml->addItem($item);
       }
       else if($request_arr[0]=='sitemap-tax-category.xml')
       {
            $query=$DB->query("select dateline,cateid from {$dbprefix}article a where aid in ( select aid from (SELECT MAX(aid) AS aid FROM {$dbprefix}article c GROUP BY cateid) b)");
            $lastArr=array();
            while($re=$DB->fetch_array($query)) $lastArr[$re['cateid']]=$re['dateline'];
           foreach($category as $cate)
           {
               $item=new WebSitemapItem('/'.$cate['url']);
               if(isset($lastArr[$cate['cid']]))
               {
                    $item->setLastModified($lastArr[$cate['cid']]);
               }
               $item->setPriority(0.3);
               $item->setChangeFrequency('weekly');
               $xml->addItem($item);
           }
       }
       else
       {
           //322017.html	60%	Monthly	2018-02-03 08:58
           $xmarr=explode('-',substr($request_arr[0],0,-4));
           if(count($xmarr)==3)
           {
               $month=$xmarr[2];
               $year=$xmarr[1];
               $dt=new DateTime($year.'-'.$month);
               $start= $dt->format("y-m-d");
               $dt->add(new DateInterval("P1M"));
               $end= $dt->format("y-m-d");
               $query=$DB->query("select aid,modified from {$dbprefix}article a where aid in ( select cid from (SELECT aid AS cid FROM {$dbprefix}article c where dateline>='{$start}' and dateline<'{$end}') b) limit 50000");
               while($re=$DB->fetch_array($query))
               {
                    $item=new WebSitemapItem('/'.$setting['option']['article'].'/'.$re['aid'].'.html');
                    $item->setLastModified($re['modified']);
                    $item->setChangeFrequency('monthly');
                    $item->setPriority(0.6);
                    $xml->addItem($item);
               }
           }
       }

       $xml->closeXml();
       $xml->outxml();
       exit;
    }
}