<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="mainbody">
  <table border="0"  cellspacing="0" cellpadding="0" style="width:100%;">
    <tr>
      <td valign="top" style="width:150px;"><div class="tableborder">
        <div class="tableheader">数据管理</div>
        <div class="leftmenubody">
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=database&action=backup">备份数据库</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=database&action=tools">数据库维护</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=database&action=filelist">数据库恢复</a></div>
          <div class="leftmenuitem">&#8226; <a href="admin.php?file=database&action=mysqlinfo">数据库信息</a></div>
        </div>
      </div></td>
      <td valign="top" style="width:20px;"></td>
      <td valign="top">
EOT;
if ($action == 'filelist') {print <<<EOT
<div class="box">
<div class="alert">关于导入数据说明</div>
<div class="alertmsg">
1. 导入的数据必须是用RQCMS备份的文件.<br />
2. 导入的数据文件表前缀和当前需一致<br />
</div>
EOT;
}print <<<EOT
	  <form action="admin.php?file=database" enctype="multipart/form-data" method="POST" name="form"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	  <tr><td class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
EOT;
if (in_array($action, array('backup', 'tools'))) {print <<<EOT
    <tr class="tdbheader">
      <td colspan="2">$tdtitle</td>
    </tr>
EOT;
if($action == 'backup'){print <<<EOT
    <tr class="tablecell">
      <td>备份文件名:</td>
      <td><input class="formfield" type="text" name="filename" size="40" maxlength="40" value="$backuppath">.sql</td>
    </tr>
	 <tr class="tablecell">
      <td>备份方式:</td>
      <td><input type="radio" checked="checked" value="local" name="bakplace"/>本地<input type="radio" value="server" name="bakplace"/>服务器&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.phome.net/product/Ebak.html" target='_blank'>如果数据较多可以使用帝国备份王</a></td>
    </tr>
EOT;
} else {print <<<EOT
  <tr class="tablecell">
    <td width="40" align="right" nowrap><input type="checkbox" name="do[]" value="check" checked /></td>
    <td width="100%">检查表</td>
  </tr>
  <tr class="tablecell">
    <td width="40" align="right" nowrap><input type="checkbox" name="do[]" value="repair" checked /></td>
    <td width="100%">修复表</td>
  </tr>
  <tr class="tablecell">
    <td width="40" align="right" nowrap><input type="checkbox" name="do[]" value="analyze" checked /></td>
    <td width="100%">分析表</td>
  </tr>
  <tr class="tablecell">
    <td width="40" align="right" nowrap><input type="checkbox" name="do[]" value="optimize" checked /></td>
    <td width="100%">优化表</td>
  </tr>
EOT;
}print <<<EOT
    <input type="hidden" name="action" value="$act">
    <tr class="tablecell">
      <td colspan="2" align="center"><input type="submit" value="提交" class="formbutton">
        <input type="reset" name="" value="重置" class="formbutton">
      </td>
    </tr>
EOT;
} elseif($action == 'filelist'){print <<<EOT
    <input type="hidden" name="action" value="deldbfile">
    <tr class="tdbheader">
      <td width="34%" nowrap>文件名</td>
      <td width="22%" nowrap>备份时间</td>
      <td width="22%" nowrap>修改时间</td>
      <td width="11%" nowrap>文件大小</td>
      <td width="9%" nowrap>操作</td>
      <td width="2%" nowrap><input name="chkall" value="on" type="checkbox" onclick="checkall(this.form)"></td>
    </tr>
EOT;
if ($noexists) {print <<<EOT
    <tr class="tablecell">
      <td colspan="8">目录不存在或无法访问, 请检查 $backupdir 目录.</td>
    </tr>
EOT;
} else {
foreach($dbfiles as $key => $dbfile){print <<<EOT
    <tr class="tablecell">
      <td><a href="admin.php?file=database&action=downsql&sqlfile=$dbfile[filename]" title="右键另存为保存该文件">$dbfile[filename]</a></td>
      <td nowrap>$dbfile[bktime]</td>
      <td nowrap>$dbfile[mtime]</td>
      <td nowrap>$dbfile[filesize]</td>
      <td nowrap><a href="admin.php?file=database&action=checkresume&sqlfile=$dbfile[filepath]">导入</a></td>
      <td nowrap><input type="checkbox" name="sqlfiles[$dbfile[filename]]" value="1"></td>
    </tr>
EOT;
}}print <<<EOT
    <tr class="tablecell">
      <td colspan="8"><b>共有{$file_i}个备份文件</b></td>
    </tr>
    <tr class="tablecell">
      <td colspan="8" align="center">
        <input type="submit" value="删除所选文件" class="formbutton">
      </td>
    </tr>
EOT;
} elseif($action == 'mysqlinfo'){print <<<EOT
  <tr class="tdbheader">
	<td colspan="3">MYSQL数据库信息</td>
  </tr>
  <tr class="tablecell">
	<td width="50%">数据库版本:</td>
	<td width="50%">$mysql_version</td>
  </tr>
  <tr class="tablecell">
	<td width="50%">数据库运行时间:</td>
	<td width="50%">$mysql_runtime</td>
  </tr>
  <tr>
    <td class="tablebottom" colspan="8"></td>
  </tr>
  </table></td>
    </tr>
    <tr>
      <td height="20"></td>
    </tr>
    <tr>
      <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr class="tdbheader">
	<td width="20%">RQCMS数据表名称</td>
	<td width="20%">创建时间</td>
	<td width="20%">最后更新时间</td>
	<td width="10%">记录数</td>
	<td width="10%">数据</td>
	<td width="10%">索引</td>
	<td width="10%">碎片</td>
  </tr>
EOT;
foreach($RQCMS_table as $sablog){print <<<EOT
  <tr class="tablecell">
	<td>$sablog[Name]</td>
	<td nowrap>$sablog[Create_time]</td>
	<td nowrap>$sablog[Update_time]</td>
	<td nowrap>$sablog[Rows]</td>
	<td nowrap>$sablog[Data_length]</td>
	<td nowrap>$sablog[Index_length]</td>
	<td nowrap>$sablog[Data_free]</td>
  </tr>
EOT;
}print <<<EOT
  <tr class="tablecell">
	<td colspan="3"><b>共计:{$RQCMS_table_num}个数据表</b></td>
	<td><b>$RQCMS_table_rows</b></td>
	<td><b>$RQCMS_data_size</b></td>
	<td><b>$RQCMS_index_size</b></td>
	<td><b>$RQCMS_free_size</b></td>
  </tr>
  <tr>
    <td class="tablebottom" colspan="8"></td>
  </tr>
  </table></td>
    </tr>
    <tr>
      <td height="20"></td>
    </tr>
    <tr>
      <td valign="top" class="rightmainbody"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr class="tdbheader">
	<td width="20%">其他数据表名称</td>
	<td width="20%">创建时间</td>
	<td width="20%">最后更新时间</td>
	<td width="10%">记录数</td>
	<td width="10%">数据</td>
	<td width="10%">索引</td>
	<td width="10%">碎片</td>
  </tr>
EOT;
foreach($other_table as $other){print <<<EOT
  <tr class="tablecell">
	<td>$other[Name]</td>
	<td nowrap>$other[Create_time]</td>
	<td nowrap>$other[Update_time]</td>
	<td nowrap>$other[Rows]</td>
	<td nowrap>$other[Data_length]</td>
	<td nowrap>$other[Index_length]</td>
	<td nowrap>$other[Data_free]</td>
  </tr>
EOT;
}print <<<EOT
  <tr class="tablecell">
	<td colspan="3"><b>共计:{$other_table_num}个数据表</b></td>
	<td><b>$other_table_rows</b></td>
	<td><b>$other_data_size</b></td>
	<td><b>$other_index_size</b></td>
	<td><b>$other_free_size</b></td>
  </tr>
EOT;
} elseif($action == 'dotools') {
foreach ($dodb AS $do) {print <<<EOT
  <tr class="tdbheader">
	<td colspan="2">$do[name]表</td>
  </tr>
EOT;
foreach($tabledb as $table){
if ($table['do'] == $do['do']) {print <<<EOT
  <tr class="tablecell">
	<td>$table[table]</td>
	<td>$table[result]</td>
  </tr>
EOT;
}}}} elseif ($action == 'checkresume') {print <<<EOT
  <input type="hidden" name="action" value="resume">
  <input type="hidden" name="sqlfile" value="$sqlfile">
    <tr class="alertheader">
      <td>导入备份数据</td>
    </tr>
    <tr>
      <td class="alertbox">
	  <p>导入文件:$sqlfile</p>
	  <p><b>恢复功能将覆盖原来的数据,您确认要导入备份数据?</b></p>
	  <p><input type="submit" value="确认" class="formbutton"></p>
	  </td>
    </tr>
EOT;
} elseif ($action == 'rssimport') {print <<<EOT
    <tr class="tdbheader">
      <td colspan="2">导入RSS数据</td>
    </tr>
    <tr class="tablecell">
      <td valign="top">选择目标分类:</td>
      <td><select name="cid" id="cid">
          <option value="" selected>== 选择分类 ==</option>
EOT;
$i=0;
foreach($catedb as $key => $cate){
print <<<EOT

          <option value="$cate[cid]">$cate[name]</option>
EOT;
}print <<<EOT
        </select></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">选择文章作者:</td>
      <td><select name="uid" id="uid">
          <option value="" selected>== 选择作者 ==</option>
EOT;
$i=0;
foreach($userdb as $key => $user){
print <<<EOT
          <option value="$user[userid]">$user[username]</option>

EOT;
}print <<<EOT
        </select></td>
    </tr>
    <tr class="tablecell">
      <td>选择XML文件</td>
      <td><input class="formfield" type="file" name="xmlfile"> 允许文件类型:xml</td>
    </tr>
    <input type="hidden" name="action" value="importrss">
    <tr class="tablecell">
      <td colspan="2" align="center">
        <input type="submit" value="确定" class="formbutton">
      </td>
    </tr>
EOT;
} print <<<EOT
    <tr>
      <td class="tablebottom" colspan="8"></td>
    </tr>
      </table></td>
    </tr>
  </table>
</form></td>
    </tr>
  </table>
</div>

EOT;

