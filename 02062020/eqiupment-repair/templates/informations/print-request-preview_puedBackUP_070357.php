<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../modules/request_model.php';

$utilMD = new Model_Utilities();
$reqMD = new Model_Request();
$_id = $_REQUEST['id'];
$reqData = $reqMD->get_data($_id);
$costData = $reqMD->load_cost($_id);
$estimateData = $reqMD->load_estimate($_id);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Print Preview</title>
<link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-size: 20pt;
	font-weight: bold;
}
.style3 {
	font-size: 18pt;
	font-weight: bold;
}
-->
</style>
</head>
<body scroll="yes"> 
<form name="form1" method="post" action="">&nbsp;
  <table width="98%" height="98%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="20" valign="top" class="TBRL"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="8%" align="center"><img src="../../../images/logoDoc.gif" width="80" height="51"></td>
          <td width="72%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><span class="style1">��駫��� / ���ا�ѡ��</span></td>
              </tr>
              <tr>
                <td align="center"><span class="style1">˹��§ҹ�������ا Ἱ���á�� ��ǹ�����çҹ��ҧ</span></td>
              </tr>
          </table></td>
          <td width="20%" align="center"><span class="style3">�Ţ���</span>&nbsp;&nbsp;
              <?=$reqData['FReqNo']?>
            &nbsp;&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="100%" valign="top" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <? list($y,$m,$d) = split("-",$reqData['FReqDate']);?>
          <td width="75%" height="20" bgcolor="#CCCCCC" class="B"><b class="style3">1. �����š���駫���</b></td>
          <td width="25%" align="right" bgcolor="#CCCCCC" class="B"><b>�ѹ���������ͧ</b>  <?=$d?>  <?=$utilMD->getNameMonthFull($m)?>  <?=($y+543)?>          </td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="13%"><b>���ͼ����</b></td>
                      <td width="26%"><?=$utilMD->convert2Thai($reqData['emp_name'])?></td>
                      <td width="7%"><b>�Ң�</b></td>
                      <td width="20%"><?=$utilMD->convert2Thai($reqData['brn_name'])?></td>
                      <td width="8%"><b>������</b></td>
                      <td width="26%"><?=$utilMD->convert2Thai($reqData['FTel'])?></td>
                    </tr>
                    <tr>
                      <td><b>˹��§ҹ</b></td>
                      <td><?=$utilMD->convert2Thai($reqData['FFnc'])?></td>
                      <td><b>Ἱ�</b></td>
                      <td><?=$utilMD->convert2Thai($reqData['sec_nameThai'])?></td>
                      <td colspan="2" align="center">..............................................................................</td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td colspan="2" align="center"><b>���͹��ѵ�</b></td>
                      </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="2"><table width="146" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="146" class="B">�դ������ʧ����駫���</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td width="15%" valign="top"><b>�駫����ҹ�к�</b></td>
                <td width="85%"><?=$utilMD->convert2Thai($reqData['FRepairGroupItemName'])?></td>
              </tr>
              
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="10%"><b>ʶҹ���Դ���</b></td>
                    <td width="5%"><b>�Ң�</b></td>
                    <td width="13%" valign="bottom"><?=$utilMD->convert2Thai($reqData['brn_name'])?></td>
                    <td width="11%"><b>�Ҥ�� / ʶҹ���</b></td>
                    <td width="20%">&nbsp;<?=$utilMD->convert2Thai($reqData['FLocation'])?></td>
                    <td width="3%"><b>���</b></td>
                    <td width="6%">&nbsp;<?=$utilMD->convert2Thai($reqData['FFloor'])?></td>
                    <td width="4%"><b>��ͧ</b></td>
                    <td width="28%">&nbsp;<?=$utilMD->convert2Thai($reqData['FRoom'])?></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top"><b>��������´ / �ѭ��</b>&nbsp;&nbsp;&nbsp;&nbsp;<?$utilMD->convert2Thai($reqData['FDetail'])?></td>
                    </tr>
                </table></td>
                </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" class="LBR_BG"><span class="style3">2. �š�ô��Թ�ҹ�ͧ����Ѻ�Դ�ͺ (˹��§ҹ�������ا)</span></td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="8%" align="center">&nbsp;</td>
              <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="6" align="center"><b>�š�з�</b></td>
                </tr>
                <tr>
                  <td>[<? if($reqData['FLevel']=="1"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td>�Ѻ�١����µç</td>
                  <td>[<? if($reqData['FLevel']=="2"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td width="25%">�ѺἹ���ҧ �</td>
                  <td width="6%">[<? if($reqData['FLevel']=="3"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td width="23%">����Ἱ�</td>
                </tr>
                <tr>
                  <td width="8%">[<? if($reqData['FJobresult']=="S"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td width="31%">�����ͧ </td>
                  <td width="7%">[<? if($reqData['FJobresult']=="O"){print "<img src=\"../../../images/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;";}?>]</td>
                  <td colspan="3">������Ѻ���Ҵ��Թ���</td>
                </tr>
              </table></td>
              </tr>
            <?php 
            	foreach($costData['L'] as $key=>$val){
				    $FLap_amt = $val['FReqCost'];
            ?>
		            <tr>
		              <td align="center">&nbsp;</td>
		              <td colspan="2"><b>����ç</b></td>
		              <td align="center">�Ҥ�</td>
		              <td align="right" class="underlinedott"><? if($FLap_amt>0)print number_format($FLap_amt,2,".",",");?>&nbsp;</td>
		              <td align="center">�ҷ</td>
		            </tr>
            <?php 
            	}
            ?>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><b>���������</b></td>
              <td align="center">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td align="center">&nbsp;</td>
            </tr>
            <?php 
            	$FPart_amt_total = 0;
            	$index = 0;
            	foreach($costData['P'] as $key=>$val){
				    $FPart_amt = $val['FReqCost'];
				    $FPart_amt_total += $FPart_amt;
				    $index++;
            ?>
            <tr>
              <td>&nbsp;</td>
              <td width="3%" align="center"><?=$index?>.</td>
              <td width="56%" class="underlinedott">&nbsp;<?=$utilMD->convert2Thai($val['FReqCostDetail'])?></td>
              <td width="8%" align="center">�Ҥ�</td>
              <td width="16%" class="underlinedott" align="right"><? if($FPart_amt>0)print number_format($FPart_amt,2,".",",");?>&nbsp;</td>
              <td width="9%" align="center">�ҷ</td>
            </tr>
            <?php 
            	}
            ?>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="right">����繨ӹǹ�Թ</td>
              <td>&nbsp;</td>
              <td class="underlinedott" align="right"><? if($FPart_amt_total>0)print number_format($FPart_amt_total,2,".",",");?>&nbsp;</td>
              <td align="center">�ҷ</td>
            </tr>
            
            <tr>
              <td align="center">[<? if($FReam=="Y"){print "<img src=\"image/OK.gif\" width=\"12\" height=\"12\">";}else{print "&nbsp;&nbsp;&nbsp;&nbsp;";}?>]</td>
              <td>&nbsp;</td>
              <td colspan="4">�ԡ�Թʴ���ͨѴ������ʴ��Ҵ��Թ��ë������</td>
              </tr>
            <tr>
              <td height="15" colspan="6"></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="2" align="center">ŧ����..............................................................<br>��ҧ෤�Ԥ�������ا</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
          <td width="50%" class="L"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
              <td colspan="5" align="center"><b>�����Դ���</b></td>
            </tr>
            <?php 
            	$index = 0;
            	foreach($estimateData as $key=>$val){
					$index++;
            ?>
		            <tr>
		              <td width="9%" align="center"><?=$index?></td>
		              <td colspan="3" class="underlinedott">&nbsp;<?=$utilMD->convert2Thai($val['FReqEstimate'])?></td>
		              <td width="3%">&nbsp;</td>
		            </tr>
            <?php 
            	}
            ?>
            <tr>
              <td>&nbsp;</td>
              <td width="40%">&nbsp;</td>
              <td width="17%">&nbsp;</td>
              <td width="31%">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="15" colspan="5"></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3" align="center">ŧ����.....................................................................</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3" align="center">���˹��˹��§ҹ</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3" align="center">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3" align="center">ŧ����.....................................................................</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3" align="center">���˹�ҧҹ������</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" class="LBR_BG"><span class="style3">3. ��õ�Ǩ�ͺ�š�ô��Թ�ҹ</span></td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="98%" align="right"> ��¡�â�ҧ�����Ѻ��ë������繷�����º�������� �������ö��ҹ��������������ѹ���..............��͹...........�.�...........</td>
          <td width="2%" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right">ŧ����..................................................................................����駫���</td>
          <td align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" class="LBR"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2">4. �óշ���������ö������������駢�͹��ѵԷӡ����觫�����������¡�úѧ�Ѻ�ѭ�Ҩҡ���ѧ�Ѵ</td>
        </tr>
        <tr>
          <td width="98%" align="right">ŧ����..................................................................................���͹��ѵ�</td>
          <td width="2%" align="right">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
            <tr>
          <td colspan="2" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="75%"><font size="5">�͡��é�Ѻ��� / �ѹ����͡�͡��� / �ѹ����ռźѧ�Ѻ�� : 6 / 24/06/2553 / 01/07/2553</font></td>
              <td width="25%" align="right">EF-OFF-63</td>
            </tr>
            <tr>
              <td colspan="2"><font size="5"><b>�����˵� : </b>�ó��駫�����ҹ�ҧ�к�����礷�͹ԡ�������ö��ҹ������駫�����ҹ�ҧ�к��͡��õ��Ẻ������Ţ��� EF-OFF-63</font></td>
              </tr>
          </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form> 
</body>
<script language="javascript">
		function onSave(){
				if(window.document.all.FReqName.value == ""){
						alert('��س��кؼ����');
						window.document.all.FReqName.focus();
				}else if(window.document.all.FPosition.value == ""){
						alert('��س��кص��˹�');
						window.document.all.FPosition.focus();
				}else if(window.document.all.FSectionID.value == "0"){
						alert('��س��к�Ἱ�');
						window.document.all.FSectionID.focus();
				}else if(window.document.all.FBranchID.value == "0"){
						alert('��س��к��Ң�');
						window.document.all.FBranchID.focus();
				}else if(window.document.all.FReqDate.value == ""){
						alert('��س��к��ѹ���');
				}else if(window.document.all.FDetail.value == ""){
						alert('��س��к���������´ / �ѭ��');
						window.document.all.FDetail.focus();
				}else if(window.document.all.FCondition.value == ""){
						alert('��س��к����͹� / �����ŷ���ͧ���');
						window.document.all.FCondition.focus();
				}else{
						window.document.all.cmd.value = "save";
						window.document.form1.submit();
				}
		}
		
		function onCancel(){
				window.location.href = 'selectPage.php';
		}
		
		function openDialog(url,value){
					var Arg="dialogWidth:"+550+"px;dialogHeight:"+350+"px;center:yes;status:no;help:no";
					var win = window.showModalDialog('popup/selectDialog.php?url='+url+'&value='+value+'&ref=' + Math.round(Math.random()*1000000000), self, Arg);		
		}
		
</script>
</html>
