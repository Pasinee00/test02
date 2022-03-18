<?php
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
include '../../../general_sys/modules/suplier_model.php';

	$filename = "excel_report".".xls";	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel"); 
	
$utilMD = new Model_Utilities();
$supMD = new Model_Suplier();
$_status = array("new"=>"�͡�����","waiting"=>"�͡��͹��ѵ�","inprogress"=>"���ѧ�ӡ�����","finished"=>"�ӡ�����������������","cancel"=>"�١¡��ԡ","noapprove"=>"���͹��ѵ�");
$FSectionID = $_REQUEST['sec_id'];
$FBranchID = $_REQUEST['brn_id'];
$FRepair_comp_id = $_REQUEST['FRepair_comp_id'];
$SRequestDate = $_REQUEST['SRequestDate'];
$ERequestDate = $_REQUEST['ERequestDate'];
$SDueDate = $_REQUEST['SDueDate'];
$EDueDate = $_REQUEST['EDueDate'];
$SFinishDate = $_REQUEST['SFinishDate'];
$EFinishDate = $_REQUEST['EFinishDate'];
$FStatus = $_REQUEST['FStatus'];
$Support = $_REQUEST['Support'];
$sect_data = $utilMD->getSectById($FSectionID);
$brn_data = $utilMD->get_BranchById($FBranchID);
$sup_data = $supMD->get_data($FComClaimID);
function DateThaishow($_date){
			$ex = split("-",$_date);
			if($_date!=''){
				 return $ex[2]."/".($ex[1])."/".($ex[0]+543);
			}
}
function DateDiffshow($begin,$end){
		$strSQL = "SELECT DATEDIFF('$end','$begin') AS diff_date";
		$rst = mysql_query($strSQL);
		if($row=mysql_fetch_array($rst))return $row['diff_date'];
		else return 0;
}
$title = "";
if($FSectionID)$title = "Ἱ� : ".$sect_data['sec_nameThai'];
if($FBranchID)$title .=(empty($title))?"�Ң� : ".$brn_data['brn_name']:"&nbsp;&nbsp;�Ң� : ".$brn_data['brn_name'];
if($SRequestDate) $title .=(empty($title))?"�ѹ����駵�����ѹ��� : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm"):"&nbsp;&nbsp;�ѹ����駵�����ѹ��� : ".$utilMD->convertDate2Thai($SRequestDate,"dd-sm");
if($ERequestDate){
	 if(empty($SRequestDate))$title .=(empty($title))?"�ѹ����駶֧�ѹ��� : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm"):"&nbsp;&nbsp;�ѹ����駶֧�ѹ��� : ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($ERequestDate,"dd-sm");
}

if($SDueDate) $title .=(empty($title))?"�ѹ����˹����稵�����ѹ��� : ".$utilMD->convertDate2Thai($SDueDate,"dd-sm"):"&nbsp;&nbsp;�ѹ����˹����稵�����ѹ��� : ".$utilMD->convertDate2Thai($SDueDate,"dd-sm");
if($EDueDate){
	 if(empty($SDueDate))$title .=(empty($title))?"�ѹ����˹����稶֧�ѹ��� : ".$utilMD->convertDate2Thai($EDueDate,"dd-sm"):"&nbsp;&nbsp;�ѹ����˹����稶֧�ѹ��� : ".$utilMD->convertDate2Thai($EDueDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($EDueDate,"dd-sm");
}

if($SFinishDate) $title .=(empty($title))?"�ѹ������稵�����ѹ��� : ".$utilMD->convertDate2Thai($SFinishDate,"dd-sm"):"&nbsp;&nbsp;�ѹ������稵�����ѹ��� : ".$utilMD->convertDate2Thai($SFinishDate,"dd-sm");
if($EFinishDate){
	 if(empty($SFinishDate))$title .=(empty($title))?"�ѹ������稶֧�ѹ��� : ".$utilMD->convertDate2Thai($EFinishDate,"dd-sm"):"&nbsp;&nbsp;�ѹ������稶֧�ѹ��� : ".$utilMD->convertDate2Thai($EFinishDate,"dd-sm");
	 else  $title .=" - ".$utilMD->convertDate2Thai($EFinishDate,"dd-sm");
}

$query ="SELECT t0.FStatus,t0.FSupportID, t1.FJobLevel,t1.FJobresult,t1.FDetail,t1.FRequestID,t1.FReqNo,t1.FReqDate,t1.FFinishDate,t1.status_closejob,t1.approve_date,t1.closejob_date,t1.closejob_date2,t1.closejob_emp_date "
				.",t2.first_name,t2.last_name "
				.",t3.brn_code "
				.",t4.sec_nameThai "
				."FROM mtrequest_db.tbl_requestowner t0 "
				."LEFT JOIN mtrequest_db.tbl_request t1 ON(t1.FRequestID = t0.FRequestID) "
				."LEFT JOIN pis_db.tbl_user t2 ON(t2.user_id = t0.FSupportID) "
				."LEFT JOIN pis_db.tbl_branch t3 ON(t3.brn_id = t1.FBranchID) "
				."LEFT JOIN pis_db.tbl_section t4 ON(t4.sec_id = t1.FSectionID) "
				."WHERE 1 ";
if($FSectionID)$query .=" AND t1.FSectionID='{$FSectionID}'";
if($FBranchID)$query .=" AND t1.FBranchID='{$FBranchID}'";
//if($FRepair_comp_id)$query .=" AND t1.FRepair_comp_id='{$FRepair_comp_id}'";
if($SRequestDate)$query .=" AND t1.FReqDate>='{$SRequestDate}'";
if($ERequestDate)$query .=" AND t1.FReqDate<='{$ERequestDate}'";
if($SDueDate)$query .=" AND t1.FDueDate>='{$SDueDate}'";
if($EDueDate)$query .=" AND t1.FDueDate<='{$EDueDate}'";
if($SFinishDate)$query .=" AND t1.FFinishDate>='{$SFinishDate}'";
if($EFinishDate)$query .=" AND t1.FFinishDate<='{$EFinishDate}'";
if($Support)$query .=" AND t0.FSupportID IN ({$Support})";
$query .=" GROUP BY t0.FRequestID,t0.FSupportID,t0.FStartDate,t0.FFinishDate";
$query .=" ORDER BY t0.FSupportID,t1.FReqNo";
$results = mysql_query($query); 
$rowPerPage = 20;
$numRows = mysql_num_rows($results);
$totalPage = ceil($numRows/$rowPerPage);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>��§ҹ��ػʶҹЧҹ�駫����¡������˹�ҷ��</title>
<?php /* <link href="../../../css/stylesheet_report.css" rel="stylesheet" type="text/css"> */?>
</head>
<style>
.fontWingdings{
	font-family:"Wingdings 2";
}
</style>
<body><table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="12%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">���˹�ҷ�����Ѻ�Դ�ͺ</td>
    <td width="5%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">Req No.</td>
    <td width="5%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�ѹ�����</td>
    <td width="4%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">Ἱ�</td>
    <td width="3%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�Ң�</td>
    <td width="3%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�������ҹ</td>
    <td width="3%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�����ͧ</td>
    <td width="3%" rowspan="2" align="center" bgcolor="#FFFFE1" style="font-weight: bold">���.���Թ���</td>
    <td colspan="12" align="center" bgcolor="#FFFFE1" style="font-weight: bold">ʶҹ�</td>
  </tr>
  <tr>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�駫���</td>
    <td width="8%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">����ͧ͹��ѵ� ��� �Ң�</td>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">���͹��ѵ�</td>
    <td width="8%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�ʹ��Թ���</td>
    <td width="5%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">Apporve</td>
    <td width="9%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">���ѧ���Թ���</td>
    <td width="6%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">��������´ / �ѭ��</td>
    <td width="6%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�Դ�ҹ 1</td>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">��Ǩ�Ѻ�ҹ 1</td>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">�Դ�ҹ 2</td>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">��Ǩ�Ѻ�ҹ 2</td>
    <td width="7%" align="center" bgcolor="#FFFFE1" style="font-weight: bold">��������</td>
  </tr>
  <?	while($row=mysql_fetch_object($results)){ 
  				$name[$row->first_name."&nbsp;&nbsp;".$row->last_name] +=+1;
				/* if($name[$row->first_name."&nbsp;&nbsp;".$row->last_name]=='1'){
					$name_emp = $row->first_name."&nbsp;&nbsp;".$row->last_name;
				} */
  ?>
  <tr>
    <td><? if($name[$row->first_name."&nbsp;&nbsp;".$row->last_name]=='1'){ echo $row->first_name."&nbsp;&nbsp;".$row->last_name;}?></td>
    <td><?=$row->FReqNo?></td>
    <td align="center"><?=DateThaishow($row->FReqDate)?></td>
    <td align="center"><?=$row->sec_nameThai?></td>
    <td align="center"><?=$row->brn_code?></td>
    <td align="center"><?=$row->FJobLevel?></td>
    <td align="center" style="font-family:'Wingdings 2'"><? if($row->FJobresult=='1'){echo "P";}?></td>
    <td align="center" style="font-family:'Wingdings 2'"><? if($row->FJobresult=='2'){echo "P";}?></td>
    <td align="center"><?=DateThaishow($row->FReqDate)?></td>
    <td align="center"><?=DateThaishow($row->approve_date)?></td>
    <td align="center" style="font-family:'Wingdings 2'"><? if($row->FStatus=="noapprove"){echo "P";}?></td>
    <td align="center" style="font-family:'Wingdings 2'"><? if($row->FStatus=="new"){echo "P";} ?></td>
    <td align="center" style="font-family:'Wingdings 2'"><? if($row->FStatus=="waiting"){echo "P";} ?></td>
    <td align="center" style="font-family:'Wingdings 2'"><? if($row->FStatus=="inprogress"){echo "P";} ?></td>
    <td align="center"><?=$row->FDetail?></td>
    <td align="center"><?=DateThaishow($row->FFinishDate)?></td>
    <td align="center"><?=DateThaishow($row->closejob_date)?></td>
    <td align="center"><?=DateThaishow($row->closejob_emp_date)?></td>
    <td align="center"><?=DateThaishow($row->closejob_date2)?></td>
    <td align="center"><?=DateDiffshow($row->approve_date,$row->FFinishDate)?></td>
  </tr>
  <? } ?>
</table>

</body>
</html>