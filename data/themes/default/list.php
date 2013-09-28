<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
if ($total) {?>
        <ul id=list>
<?php
foreach($articledb as $key => $article){
?>
          <li><span class=postdate><?php echo $article['dateline'];?></span> <a href="<?php echo $article['aurl'];;?>" title="<?php echo $article['excerpt'];?>"><?php echo $article['title'];?></a> </li>
<?php
}?>
        </ul>
<?php
if($multipage){
echo $multipage;;
}
} else {?>
<p><strong>没有任何文章</strong></p>
<?php
}
?>