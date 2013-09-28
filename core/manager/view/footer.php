<?php
if(!defined('RQ_ROOT')) exit('Access Denied');
print <<<EOT
<div class="copyright">
  <table border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td width="120" align="center"></td>
      <td align="left">Powered by <strong><a href="http://www.rqcms.com" target="_blank">RQCMS {$constant['RQ_VERSION']}</a> Build {$constant['RQ_RELEASE']}</strong> &copy; 2010-2012</td> 
      <td width="120" align="center"></td>
    </tr>
  </table>
</div>
</body>
</html>
EOT;
?>