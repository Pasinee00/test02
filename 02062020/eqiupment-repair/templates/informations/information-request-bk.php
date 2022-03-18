<!DOCTYPE HTML">
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
$attachs = $reqMD->list_attach($_id);
$states = $reqMD->get_request_state($_id);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<link href="../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<title>Insert title here</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
</head>
<body>
   <div class="dialog-panel" style="height:100%;">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">��������駫��� / ���ا�ѡ��</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center" style="height:100px;">
   				<div style="width:80%;height:100%;overflow:auto;float:left;">
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
								     if(!empty($costData['L'])){
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
									 }else{
					            ?>
                                		<tr>
                                              <td align="center">&nbsp;</td>
                                              <td colspan="2"><b>����ç</b></td>
                                              <td align="center">�Ҥ�</td>
                                              <td align="right" class="underlinedott">&nbsp;</td>
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
					  </table>
   				</div>
   				<div style="width:20%;float:right;overflow:auto;">
   					<ul class="attachment-list">
   					<?php if(!empty($attachs)){
   							foreach($attachs as $key=>$val){
   					?>
   								<li>
   									<a href="javascript:void(0);" onclick="javascript:downloadFile('<?php print $_id?>','<?php print $utilMD->convert2Thai($val['FAttachName']);?>','<?php print $val['FAttachLink']?>')">
   									<?php print $utilMD->convert2Thai($val['FAttachName']);?>
   									</a>
   								</li>
   					<?php 
   							}
						 }
					?>
   					</ul>
   				</div>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   			    <ul class="request-state">
   			    	<?php if(!empty($states)){
   							foreach($states as $key=>$val){
   					?>
   								<?php if($val['numDay']>0){?>
   									<li class="arrow-state<?php print $val['type'];?>"><?php print $val['numDay'];?> day</li>
   								<?php }?>
			   			    	<li>
			   			    		<span><?php print $utilMD->convert2Thai($val['label']);?></span>
			   			    		<span><?php print $val['date'];?></span>
			   			    	</li>
   					<?php 
   							}
						 }
					?>
   			    </ul>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script>
function downloadFile(id,filename,url){
	var width = screen.width-10;
	var height = screen.height-60;
	newwindow=window.open('../../../download.php?name='+filename+'&reqId='+id+'&filename='+url,
								  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}
</script>
</html>