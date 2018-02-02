<?php
if (count($articledb)>0) {?>
        <ul id=list>
<?php
foreach($articledb as $key => $article){
?>
          <li><span class=postdate><?php echo $article['dateline'];?></span> <a href="<?php echo $article['url'];;?>" title="<?php echo $article['excerpt'];?>"><?php echo $article['title'];?></a> </li>
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