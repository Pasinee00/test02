<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>��§ҹ����觫��� / �� claim</title>

<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><b>��§ҹ����觫��� / �� claim</b></td>
  </tr>
  <tr>
    <td width="91%">����ѷ��µ�ҹ������ ����˹�����µ�� �ӡѴ</td>
    <td width="9%" align="right"><b>Page :</b> <?php print($page);?>/<?php print($totalPage);?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;<?php print($title);?></td>
  </tr>
  <tr>
    <td height="4" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="4%" align="center" class="tlb_bg"><b>�ӴѺ</b></td>
        <td width="7%" align="center" class="tlb_bg"><b>Req No.</b></td>
        <td width="15%" class="tlb_bg"><b>Ἱ�</b></td>
        <td width="14%" class="tlb_bg"><b>�Ң�</b></td>
        <td width="24%" class="tlb_bg"><b>��¡�÷����觫���</b></td>
        <td width="20%" class="tlb_bg"><b>����ѷ������</b></td>
        <td width="8%" align="center" class="tlb_bg"><b>�ѹ�����</b></td>
        <td width="8%" align="center" class="tlbr_bg"><b>�ѹ����Ѻ</b></td>
      </tr>     
      <tr>
        <td align="center" class="lb">&nbsp;<?php print($i);?></td>
        <td align="center" class="lb">&nbsp;<?php print($row['FReqNo']);?></td>
        <td class="lb">&nbsp;<?php print($row['sec_nameThai']);?></td>
        <td class="lb">&nbsp;<?php print($row['brn_name']);?></td>
        <td class="lb">&nbsp;<?php print("1. ".$row['claims'][0]['FItems']);?></td>
        <td class="lb">&nbsp;<?php print($utilMD->convertDate2Thai($row['claims'][0]['FDateRequest'],"dd-sm"));?></td>
        <td align="center" class="lb">&nbsp;<?php print($utilMD->convertDate2Thai($row['claims'][0]['FSendDate'],"dd-sm"));?></td>
        <td align="center" class="lrb">&nbsp;<?php print($utilMD->convertDate2Thai($row['claims'][0]['FReciveDate'],"dd-sm"));?></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>