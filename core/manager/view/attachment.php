<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td rowspan="3" valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">附件查看</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=list&view=image">图片附件</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=list&view=file">文件附件</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=list&view=hot">热门附件</a></div>
		  <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=list&view=big">最大附件</a></div>
        </div>
      </div>
	  <div class="tableborder">
        <div class="tableheader">附件管理</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=repair">附件修复</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=attachment&action=clear">附件清理</a></div>
        </div>
      </div>
	  </td>
      <td rowspan="3" valign="top" style="width:20px;"></td>
      <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if($action == 'list'){
if (!$aid) {print <<<EOT
  <tr class="tdbheader">
	<td colspan="2">附件概要信息</td>
  </tr>
  <tr class="tablecell">
	<td>附件数量:</td>
	<td>$stats[count] 个</td>
  </tr>
  <tr class="tablecell">
	<td>数据库记录全部附件大小:</td>
	<td>$stats[sum]</td>
  </tr>
  <tr class="tablecell">
	<td>当前附件存放路径:</td>
	<td>$a_dir$warning</td>
  </tr>
  <tr class="tablecell">
	<td>附件目录内子目录数量:</td>
	<td>$dircount 个</td>
  </tr>      
EOT;
} else {print <<<EOT
  <form action="admin.php?file=attachment" method="post" enctype="multipart/form-data">
  <input type="hidden" name="action" value="addattachtoarticle" />
  <input type="hidden" name="aid" value="$aid" />
  <tr class="tdbheader">
	<td colspan="2">上传新附件到该文章</td>
  </tr>
  <tr class="tablecell">
    <td colspan="2">图片超过2M缩略图和水印均不生效.如果上传大于2M的图片请自行处理.</td>
  </tr>
  <tbody id="attachbody"><tr class="tablecell"><td>附件:</td><td><input class="formfield" type="file" name="attach[0]"><input class="formfield" type="file" name="attach[1]"><input class="formfield" type="file" name="attach[2]"><input class="formfield" type="file" name="attach[3]"></td></tr></tbody>
  <tr class="tablecell">
    <td colspan="2" align="center"><input type="submit" class="formbutton" value="上传" /></td>
  </tr>
  </form>
EOT;
}
print <<<EOT
  <tr>
    <td class="tablebottom" colspan="2"></td>
  </tr>
  </table></td>
    </tr>
    <tr>
      <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <form action="admin.php?file=attachment"  method="post">
          <input type="hidden" name="action" value="delattachments" />
          <input type="hidden" name="aid" value="$aid" />
          <tr class="tdbheader">
            <td nowrap>附件名</td>
            <td nowrap>附件信息</td>
            <td nowrap>所在目录</td>
            <td nowrap>上传时间</td>
            <td nowrap>下载次数</td>
            <td nowrap>文章</td>
            <td width="2%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)" /></td>
          </tr>       
EOT;
foreach($attachdb as $key => $attach){
$atturl=mkUrl('attachment.php',$attach['aid']);
$arturl='admin.php?file=article&action=mod&aid='.$attach['articleid'];
print <<<EOT
          <tr class="tablecell">
            <td><a href="{$atturl}" target="_blank" title="$attach[filepath]">$attach[filename]</a></td>
            <td>大小:$attach[filesize]<br />
              类型:$attach[filetype]</td>
            <td>$attach[subdir]</td>
            <td nowrap>$attach[dateline]</td>
            <td>$attach[downloads]</td>
			<td nowrap><a title="$attach[article]" href="{$arturl}" target="_blank">查看</a></td>
            <td nowrap><input type="checkbox" name="attachment[]" value="$attach[aid]" /></td>
          </tr>
EOT;
}print <<<EOT
          <tr class="tablecell">
            <td colspan="8" nowrap><div class="records">记录:$total</div>
                  <div class="multipage">$multipage</div></td>
          </tr>
          <tr class="tablecell">
            <td colspan="8" align="center"><input type="submit" class="formbutton" value="删除所选附件" /></td>
          </tr>
        </form>
EOT;
} elseif ($action == 'repair') {print <<<EOT
        <form action="admin.php?file=attachment" method="post">
          <input type="hidden" name="action" value="dorepair" />
          <tr class="tdbheader">
            <td>附件修复</td>
          </tr>
          <tr>
            <td class="alertbox">
			<p>本功能清除数据库那存在附件记录而没有附件文件的冗余数据，文章中的附件记录也将同时更新。</p>
            <p>如果附件较多，过程会比较久，请耐心等候。</p>
            <p>建议定期执行。</p>
            <p><input type="submit" value="确认" class="formbutton"></p>
			</td>
          </tr>
        </form> 
EOT;
} elseif ($action == 'clear') {print <<<EOT

        <form action="admin.php?file=attachment" method="post">
          <input type="hidden" name="action" value="doclear" />
          <tr class="tdbheader">
            <td>附件清理</td>
          </tr>
          <tr>
            <td class="alertbox">
			<p>本功能删除数据库中没有记录而实际存在的附件，可有效清理冗余附件。</p>
            <p>循环处理数量: <input class="formfield" type="text" name="percount" value="500" size="5"></p>
            <p><input type="submit" value="确认" class="formbutton"></p>
			</td>
          </tr>
        </form>  
EOT;
}print <<<EOT
        <tr>
          <td class="tablebottom" colspan="8"></td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
EOT;
?>