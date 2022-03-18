<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../../pis_sys/models/user_model.php';
include '../../../modules/request_model.php';

$utilMD = new Model_Utilities();
$userMD = new Model_User();
$reqMD = new Model_Request();
$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$back = $_REQUEST['back'];
$close = $_REQUEST['close'];
$compId = (!empty($_REQUEST['compId']))?$_REQUEST['compId'] : "7";
$repairGroups = $utilMD->get_RepairGroupList();
$machineType = $utilMD->get_MachineTypeList();
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList('');
$empInfo= json_decode($utilMD->get_EmpById($empId));
$userInfo = $userMD->get_data($userId);
$FRequestID = $_REQUEST['FRequestID'];
$costData = $reqMD->load_cost($FRequestID);
$estimateData = $reqMD->load_estimate($FRequestID);
$managerOption = $utilMD->get_ManagerList();

if($JobLevel_Data=='ok'){
		$sql_check="SELECT
								general_db.tbl_machinetype.FMachineTypeID,
								general_db.tbl_machinetype.FMachineTypeName,
								general_db.tbl_machinetype.FJobLevel
								FROM
								general_db.tbl_machinetype
								WHERE
								general_db.tbl_machinetype.FMachineTypeID='".$FMachineTypeID_JobLevel."' ";		
		$query_check=mysql_query($sql_check);
		$row_check=mysql_fetch_assoc($query_check);
		echo $row_check[FJobLevel];
		exit();
}
if($back=="assign"){
	$backUrl = '../../administrator/request-assign-owner.tpl.php';	
}else if($close=="holder"){
	$backUrl = '../../supports/request-holder.tpl.php';
}else{
	if($userInfo['user_type']=="E")
		$backUrl ='../general-index.php';
	else if($userInfo['user_type']=="AM")
		$backUrl ='../../administrator/administrator-index.php';
	else if($userInfo['user_type']=="M" OR $userInfo['user_type']=="GM")
		$backUrl ='../../supports/support-index.php';
}

$date = date('Y-m-d');
$time = (date('H')).":".date('i');
$_isClose = "N";
if($userInfo['user_type']=="AM"){
	if($reqMD->check_owner($FRequestID,$userId)==1)$_isClose = "Y";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<!-- Select2 -->
<script src="../../../../css/select2/dist/js/select2.full.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>

<script  type="text/javascript" src="../../../../jsLib/jquery-chosen/chosen.jquery.js"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/js/script.js"></script>
<script type="text/javascript" src="../../../../jsLib/datepicker/zebra_datepicker.js"></script>

 
 <!-- Select2 -->
<link rel="stylesheet" href="../../../../css/select2/dist/css/select2.min.css">
<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../../css/display.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-chosen/chosen.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<script>
$(function(){
    $("input, textarea").uniform();
    $(".uniform-select").uniform();

  });
</script>
<style>

 .FontPued {
           font-size: 12px;
 		  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
       } 
</style>
</head>

<body scrolling="no">
  <div class="content-top">
  	<div class="_content-title">���ҧ㺤���ͧ�駫���</div>
  	<div class="search-action">
  		<span class="label">Request No : </span>
  		<span class="request_no" style="color:#000066;font-weight:bold"></span>
  	</div>
  </div>
  
  <div class="list-body" style="box-shadow: 0 0 5px #26384A;overflow:auto">
    <form name="form1" method="post" enctype="multipart/form-data" action="../../../controllers/request_controller.php?function=upload" target="upload_target">
      <table width="98%" align="center" border="0">
        <?php if($userInfo['user_type']=="AM" && empty($FRequestID)){?>
        <tr>
           <td width="12%"><b>���ʾ�ѡ�ҹ</b> <font color="#FF0000">*</font> :</td>
           <td width="22%">
             <input type="text" name="emp_code" id="emp_code" onkeyup="javascript:if(event.keyCode=='13')getEmpInfo();">
             <input type="hidden" name="fields[FReqID]" id="FReqID" value="">
           	 <input type="hidden" name="FRequestID" id="FRequestID" value="">
           	 <input type="hidden" name="fields[FReqNo]" id="FReqNo">
           	 <input type="hidden" name="user_type" id="user_type" value="<?php print($userInfo['user_type'])?>">
           	 <input type="hidden" name="user_type" id="user_id" value="<?php print($userInfo['user_id'])?>">
           	 <input type="hidden" name="fields[FStatus]" id="FStatus" value="new">
           </td>
           <td width="4%">&nbsp;</td>
           <td width="15%"><b>���� - ʡ��</b> :</td>
           <td width="23%">
           	  <input type="text" name="emp_name" id="emp_name" style="width:80%;" disabled value="">
           </td>
           <td width="24%"></td>
         </tr>
         <tr>
           <td><b>���˹�</b> :</td>
           <td>
           	  <input type="text" name="fields[FPosition]" id="FPosition" style="width:80%" disabled value="">
           </td>
           <td>&nbsp;</td>
           <td><b>Ἱ�</b> :</td>
           <td>
           	   <input type="text" name="sec_nameThai" id="sec_nameThai" style="width:80%" disabled value="">
           	   <input type="hidden" name="fields[FSectionID]" id="FSectionID" value="">
           </td>
           <td></td>
         </tr>
         <tr>
           <td><b>�Ң�</b> :</td>
           <td>
           	   <input type="text" name="brn_name" id="brn_name" style="width:80%" disabled value="">
           </td>
           <td>&nbsp;</td>
           <td><b>���Ѵ��� /���˹�ҧҹ</b> <font color="#FF0000">*</font> :</td>
           <td>
           	  <select name="fields[FManagerID]" id="FManagerID" style="width:350px;" class="chzn-select" onchange="javascript:getTextSelectOpt('FManagerID','FManagerName');">
   					<option value="">---���˹�ҧҹ / ���Ѵ���---</option>
   					<?php if(!empty($managerOption)){ foreach ($managerOption as $key=>$val){?>
   						<option value="<?php echo $val['FManagerID'];?>"><?php echo $val['FName'];?></option>
   					<?php }}?>
   					
		     </select>
		     <input type="hidden" name="fields[FManagerName]"  id="FManagerName" />
           </td>
           <td></td>
         </tr>
          <tr>
           <td></td>
           <td></td>
           <td>&nbsp;</td>
           <td><b>����ӹ�¡��</b> <font color="#FF0000">*</font> :</td>
           <td>
           	  <select name="fields[FSupervisorID]" id="FSupervisorID" style="width:350px;" class="chzn-select" onchange="javascript:getTextSelectOpt('FSupervisorID','FSupervisorName');">
   					<option value="">---����ӹ�¡��---</option>
   					<?php if(!empty($managerOption)){ foreach ($managerOption as $key=>$val){?>
   						<option value="<?php echo $val['FManagerID'];?>"><?php echo $val['FName'];?></option>
   					<?php }}?>
		    </select>
		     <input type="hidden" name="fields[FSupervisorName]"  id="FSupervisorName" />	
           </td>
           <td></td>
         </tr>
        <?php }else{?>
         <tr>
           <td width="12%"><b>���ʾ�ѡ�ҹ</b> :</td>
           <td width="22%">
             <input type="text" name="emp_code" id="emp_code" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->emp_code));?>">
             <input type="hidden" name="fields[FReqID]" id="FReqID" value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->emp_id));?>">
           	 <input type="hidden" name="FRequestID" id="FRequestID" value="<?php print($FRequestID);?>">
           	 <input type="hidden" name="fields[FReqNo]" id="FReqNo">
           	 <input type="hidden" name="user_type" id="user_type" value="<?php print($userInfo['user_type'])?>">
           	 <input type="hidden" name="user_id" id="user_id" value="<?php print($userInfo['user_id']);?>">
           	 <input type="hidden" name="fields[FStatus]" id="FStatus" value="new">
           </td>
           <td width="4%">&nbsp;</td>
           <td width="15%"><b>���� - ʡ��</b> :</td>
           <td width="23%">
           	  <input type="text" name="emp_name" id="emp_name" style="width:80%;" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->emp_name));?>">
           </td>
           <td width="24%"></td>
         </tr>
         <tr>
           <td><b>���˹�</b> :</td>
           <td>
           	  <input type="text" name="fields[FPosition]" id="FPosition" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $empInfo->post_name));?>">
           </td>
           <td>&nbsp;</td>
           <td><b>Ἱ�����觤���ͧ</b> :</td>
           <td>
           	   <input type="text" name="sec_nameThai" id="sec_nameThai" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['sec_nameThai']));?>">
           	   <input type="hidden" name="fields[FSectionID]" id="FSectionID" value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['sec_id']));?>">
                <input type="hidden" name="fields[FBranchID_login]" id="FBranchID_login" value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['brn_id']));?>">
           </td>
           <td></td>
         </tr>
         <tr>
           <td><b>�Ңҷ���觤���ͧ</b> :</td>
           <td>
           	   <input type="text" name="brn_name" id="brn_name" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['brn_name']));?>">
           </td>
           <td>&nbsp;</td>
           <td><b>���Ѵ��� /���˹�ҧҹ</b> :</td>
           <td>
           	  <input type="text" name="fields[FManagerName]" id="FManagerName" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['manager_name']));?>">
           	  <input type="hidden" name="fields[FManagerID]" id="FManagerID" value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['manager_id']));?>">
           </td>
           <td></td>
         </tr>
          <tr>
           <td></td>
           <td></td>
           <td>&nbsp;</td>
           <td><b>����ӹ�¡��</b> :</td>
           <td>
           	  <input type="text" name="fields[FSupervisorName]" id="FSupervisorName" style="width:80%" disabled value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['supervisor_name']));?>">
           	  <input type="hidden" name="fields[FSupervisorID]" id="FSupervisorID" value="<?php print(iconv("UTF-8", "TIS-620", $userInfo['supervisor_id']));?>">
           </td>
           <td></td>
         </tr>
         <?php }?>
         <tr>
           <td><b>�Ţ�����ҧ�ԧ</b>  <font color="#FF0000">*</font> :</td>
           <td>
             <input type="text" name="fields[FInf_no]" id="FInf_no">
           </td>
           <td>&nbsp;</td>
           <td><b>�Ţ����Ѿ���Թ</b> <font color="#FF0000">*</font>:</td>
           <td>
           	<input type="text" name="fields[FAsset_no]" id="FAsset_no">&nbsp;&nbsp;
           	<input type="checkbox" name="no_asset" id="no_asset"> ��辺����
           </td>
           <td></td>
         </tr>
        <tr>
           <td><b>˹��§ҹ</b> <font color="#FF0000">*</font> :</td>
           <td><input type="text" name="fields[FFnc]" id="FFnc"></td>
           <td>&nbsp;</td>
           <td><b>Serial No.(���ʷ�Ѿ���Թ MT)</b> :</td>
           <td>
           	<input type="text" name="fields[FSerial]" id="FSerial">
           </td>
           <td></td>
         </tr>
         <tr>
           <td><b>����ѷ���Դ���</b> <font color="#FF0000">*</font> :</td>
           <td><select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');">
             <option value="">-------����ѷ-------</option>
             <?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
             <option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
             <?php }}?>
           </select></td>
           <td>&nbsp;</td>
           <td><b>������.</b> <font color="#FF0000">*</font> :</td>
           <td><input type="text" name="fields[FTel]" id="FTel" onkeyup="javascript:changNumeric(this);"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>�Ңҷ��Դ���</b> <font color="#FF0000">*</font> :</td>
           <td><select name="fields[FBranchID]" id="FBranchID" class="uniform-select" style="width:">
             <option value="">-------�Ң�-------</option>
             <?php if(!empty($brnList)){ foreach ($brnList as $key=>$val){?>
             <option value="<?php echo $val['brn_id'];?>"  ><?php echo $val['brn_code']." - ".$val['brn_name'];?></option>
             <?php }}?>
           </select>
         </td>
           <td>&nbsp;</td>
           <td><b>�Ҥ�� / ʶҹ��� </b> <font color="#FF0000">*</font> :</td>
           <td><input type="text" name="fields[FLocation]" id="FLocation"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>���</b> <font color="#FF0000">*</font> :</td>
           <td><input type="text" name="fields[FFloor]" id="FFloor"></td>
           <td>&nbsp;</td>
           <td><b>��ͧ</b> <font color="#FF0000">*</font> :</td>
           <td><input type="text" name="fields[FRoom]" id="FRoom"></td>
           <td></td>
         </tr>
         <tr>
           <td><b>�ѹ��� / ����</b> :</td>
           <td><input type="text" name="fields[FReqDate]" id="FReqDate" readonly value="<?php print($date);?>">
-
  <input type="text" name="fields[FReqTime]" id="FReqTime" readonly style="width:50px;" value="<?php print($time);?>"></td>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
           <td></td>
         </tr>
         <tr>
           <td><b>���駫���</b> <font color="#FF0000">*</font> :</td>
           <td><select name="fields[FRepairGroupID]" id="FRepairGroupID" class="uniform-select" onChange="javascript:updateRepairItem('');">
             <option value="">---��س����͡��¡�â��駫���</option>
             <?php if(!empty($repairGroups)){
            			foreach($repairGroups as $key=>$val){
            	?>
             <option value="<?php print($val['FRepairGroupID']);?>"><?php print($val['FRepairGroupName']);?></option>
             <?php }}?>
           </select></td>
           <td>&nbsp;</td>
           <td><b>��¡�ë���</b>  <font color="#FF0000">*</font> :</td>
           <td>
            <select name="fields[FRepairGroupItemID]" id="FRepairGroupItemID" class="uniform-select" style="width:200px;">
            	<option value="">---��س����͡��¡�ë���</option>
            </select>
           </td>
           <td></td>
         </tr>
         <tr>
           <td valign="top"><b>��������´ / �ѭ��</b> <font color="#FF0000">*</font> :</td>
           <td colspan="5"><textarea name="fields[FDetail]" rows="10" style="width: 728px;" id="FDetail"></textarea></td>
         </tr>
         <tbody id="attachment" style="display:none">
         	<tr>
	           <td><b>���Ṻ</b> : &nbsp;</td>
	           <td colspan="5">
	           		<input type="file" name="fileUpload" id="fileUpload">
	           		<a class="button-bule" href="javascript:void(0);" onclick="javascript:uploadData();"> �������  </a>
	           </td>
	         </tr>
	         <tr>
	           <td>&nbsp;</td>
	           <td colspan="5" id="attach-list">
	           		
	           </td>
	         </tr>
         </tbody>
         <?php if($userInfo['user_type']=="AM" OR $userInfo['user_type']=="M" OR $userInfo['user_type']=="GM"){?>
         <tbody>
         	<tr>
	           <td colspan="6" align="center" style="background-color:#999999;"><b>�š�ô��Թ��âͧ����Ѻ�Դ�ͺ</b></td>
	        </tr>
	        <tr>
	           <td><b>����Ѻ����ͧ</b>  :</td>
	           <td><input type="text" name="recive_name" id="recive_name" style="width:80%" disabled value="<?php print($utilMD->convert2Thai($userInfo['first_name']))?>  <?php print($utilMD->convert2Thai($userInfo['last_name']))?>"></td>
	           <td>&nbsp;<input type="hidden" name="fields[FReciverID]" id="FReciverID" value="<?php print($userInfo['user_id'])?>"></td>
	           <td><b>�ѹ����Ѻ����ͧ</b> :&nbsp;</td>
	           <td>
	           	   <input type="text" name="fields[FReciveDate]" id="FReciveDate" readonly value="<?php print($date);?>">
		           - 
		           <input type="text" name="fields[FReciveTime]" id="FReciveTime" readonly style="width:50px;" value="<?php print($time);?>">
	           </td>
	           <td></td>
	         </tr>
	         <tr>
	           <td><b>����ͧ�ѡ�</b>  :  </td>
	           <td>
				<?   $sql= "select FMachineTypeID from mtrequest_db.tbl_request where  FRequestID = '".$FRequestID."'";
                       $query_type = mysql_query($sql);
                       $rs_type= mysql_fetch_assoc($query_type);
                       $name_type = $rs_type[FMachineTypeID];
                       
                    	$sql_name_type = "select  FMachineTypeName from general_db.tbl_machinetype where FMachineTypeID = '".$name_type."'";
						$query_name_type = mysql_query($sql_name_type);
						$rs_name_type = mysql_fetch_assoc($query_name_type);
						$name_type2 =  $rs_name_type[FMachineTypeName];
						
				?>
               		 
                     <select name="fields[FMachineTypeID]" id="FMachineTypeID_JobLevel"   onChange="javascript:updateRepairType('');javascript:showJobLevel();"  class="select2"  >
                     <? if($name_type2 ==""){ ?>
                        <option value="">---��س����͡��¡������ͧ�ѡ�---</option>
								<?php if(!empty($machineType)){
                                       	  	 	foreach($machineType as $key=>$val){
                                ?>	  		<option value="<?php print($val['FMachineTypeID']);?>" ><?php print($val['FMachineTypeName']);?></option>
		            			<?php 		}
										 }
                       }else{ ?>
                  				 	<option  value="<?=$name_type?>" hidden ><? echo $name_type2 ?></option>
											  <?php if(!empty($machineType)){
                                                   			 foreach($machineType as $key=>$val){
                                               ?>
		            				<option value="<?php print($val['FMachineTypeID']);?>" ><?php print($val['FMachineTypeName']);?></option>
		            	<?php 							}
											 			}	
						} ?>
                          </select>
	           </td>
	           <td>&nbsp;</td>
	           <td><b>�駫���</b> :&nbsp;</td>
	           <td>
	           		<select name="fields[FRepairItemID]" id="FRepairItemID" class="uniform-select" >
		            	<option value="">---��س����͡��¡���駫���</option>
	              </select>
	           </td>
	           <td></td>
	         </tr>
	         
	         <tr>
	           <td><b>�дѺ�����Ӥѭ</b>  :</td>
	           <td>
	           		<select name="fields[FLevel]" id="FLevel" class="uniform-select">
	           	  		<option value="">---��س����͡�дѺ�����Ӥѭ</option>
	           	  		<option value="1">�š�з��Ѻ�١����µç</option>
	           	  		<option value="2">�š�з��ѺἹ���ҧ �</option>
	           	  		<option value="3">�š�з�����Ἱ�</option>
	           	  </select>
	           		
	           </td>
	           <td>&nbsp;</td>
	           <td><font color="#FF0000">*</font> <b>�������ҹ</b> :&nbsp;</td>
	           <td><input name="fields[FJobLevel]" id="FJobLevel" type="text" disabled>
	           		<?php /* <select name="fields[FJobLevel]" id="FJobLevel" class="uniform-select" >
		            	<option value="">---��س����͡��¡�û������ҹ</option>
                        <option value="S">�������ҹ S</option>
		            	<option value="L">�������ҹ L</option>
		            	<option value="M">�������ҹ M</option>
		            	<option value="H">�������ҹ H</option>
		            	<option value="P">�������ҹ P (Project)</option>
	              </select> */?>
	           </td>
	           <td></td>
	         </tr>
	         <tr>
	           <td colspan="6">
	           		<table width="100%" border="0" cellspacing="0" cellpadding="0">
                       <tr>
                          <td width="50%" valign="top">
                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                   <td width="8%" height="20" align="center">&nbsp;</td>
                                   <td height="20" colspan="4">
                                   	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                           <td width="7%" align="center">
                                           		<input type="radio" name="fields[FJobresult]" id="FJobresult" class="FJobresult_1" <? if($FJobresult==1){?> checked <? }?> value="1" class="Noboder">
                                           </td>
                                           <td width="19%">�����ͧ </td>
                                           <td width="10%" align="center">
                                           		<input type="radio" name="fields[FJobresult]" id="FJobresult" class="FJobresult_2" <? if($FJobresult==2){?> checked <? }?> value="2" class="Noboder">
                                           	</td>
                                           <td width="64%">������Ѻ���Ҵ��Թ���</td>
                                        </tr>
                                       </table>
                                   </td>
                                 </tr>
                                 <tr>
                                    <td height="20">&nbsp;</td>
                                    <td height="20" width="50%" ><b>����ç</b></td>
                                    <td height="20" width="15%" align="right">�Ҥ�</td>
                                    <td height="20" align="right" class="underlinedott"><input name="fields[FLapAmt]" type="text" id="FLapAmt" onkeyup="javascript:changNumeric(this);" style="width:80px;text-align:right;" value="<? if($FLap_amt>0)print number_format($FLap_amt,2,".",",");?>"></td>
                                    <td height="20" align="center">�ҷ</td>
                                 </tr>

                                 <tr>
                                    <td height="20">&nbsp;</td>
                                    <td height="20"><b>���������</b></td>
                                    <td height="20" align="right">�Ҥ�(���) </td>
                                    <td height="20" align="right" class="underlinedott"><input name="fields[FPartAmt]" type="text" id="FPartAmt" onkeyup="javascript:changNumeric(this);" style="width:80px;text-align:right;" readonly value="<? if($FLap_amt>0)print number_format($FLap_amt,2,".",",");?>"></td>
                                    <td height="20" align="center">�ҷ</td>
                                 </tr>
                                <tr>
                                   <td height="20" align="center">
                                   		<input type="checkbox" name="fields[FReam]" id="FReam" <? if($FReam=="Y"){?> checked <? }?> value="Y" class="FReam">
                                   	</td>
                                   <td height="20" colspan="4">�ԡ�Թʴ���ͨѴ������ʴ��Ҵ��Թ��ë������</td>
                                </tr>
                                 <tr>
                                		<td colspan="5">
                                				<table width="100%" border="0" cellspacing="0" cellpadding="0">
								           			<tr>
								           			   <td width="12%"><b>��˹�����</b> <font color="#FF0000">*</font> :</td>
											           <td width="23%"><input type="text" name="fields[FEstimate]" id="FEstimate" style="width:80px;text-align:center;" onkeyup="javascript:changNumeric(this);" value=""></td>
											           <td width="4%"></td>
											           <td width="15%"><b>�ѹ����Դ Job</b> <?php if($userInfo['user_type']=="M"){?><font color="#FF0000">*</font><?php }?> :</td>
											           <td width="24%"><input type="text" name="fields[FEditDate]" id="FEditDate" style="width:80%;" value=""></td>
								           			</tr>
								           			<tr id="close-job">
											           <td><b>�ѹ����˹����� </b> :</td>
											           <td><input type="text" name="fields[FDueDate]" id="FDueDate" readonly style="width:50%;" value=""></td>
											           <td></td>
											           <td><b>�ѹ���Դ�ҹ</b ><?php if($close =="y"){?><font color="#FF0000">*</font><?php }?>  :</td>
											           <td><input type="text" name="fields[FFinishDate]" id="FFinishDate" style="width:80%;" value=""></td>
											         </tr>
								           			<tr>
											           <td><b>�ѹ�����ҹ��ԧ</b>  :</td>
											           <td><input type="text" name="fields[FUseDate]" id="FUseDate" style="width:80%;" value=""></td>
											           <td></td>
											           <td></td>
											           <td></td>
											         </tr>
											          <tr>
											           <td></td>
											           <td>
											              <?php if($userInfo['user_type']=="AM" || $userInfo['user_type']=="M"){?>
											           		<input type="checkbox" name="fields[FApprove]" id="FApprove" value="Y" class="FApprove"> �͡���繵�͹��ѵ�  
											           	  <?php }else{?>
											           	  	<input type="checkbox" name="FApprove" id="FApprove" disabled value="Y" class="FApprove"> �͡���繵�͹��ѵ� 
											           	  <?php }?>
											           	  <input type="hidden" name="is_sendTo" id="is_sendTo" value="">
											           	  <input type="hidden" name="FReceiveDoc" id="FReceiveDoc" value="">
											           </td>
											           <td></td>
											           <td></td>
											           <td></td>
											         </tr>
								           		</table>
                                		</td>
                                </tr>
                             </table>
                           </td>
                           <td valign="top">
                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                  <td width="10%" height="20" align="center">
                                  	<input type="radio" name="fields[FCondition]" id="FCondition" value="1" class="FCondition_1">
                                  </td>
                                  <td height="20" colspan="4">�ҡ������������Ǩ�ͺ��繤�ô��Թ��õ���ʹ�</td>
                                </tr>
                                <tr>
                                  <td height="20" align="center">
                                  	<input type="radio" name="fields[FCondition]" id="FCondition" <? if($FCondition=="2"){?> checked <? }?> value="2" class="FCondition_2">
                                  </td>
                                  <td height="20" colspan="4">��� �</td>
                                </tr> 
                               <tr>
                                  <td height="20" align="center" valign="top"><b>��ػ�ҹ :</b></td>
                                  <td height="20" colspan="4"><textarea name="fields[FOth_detail]" rows="10" style="width: 80%;" id="FOth_detail"></textarea></td>
                                </tr> 
                              </table>
                            </td>
                         </tr>
                     </table>
	           </td>
	         </tr>
	         <tr>
	           <td colspan="4" valign="top">
	           		
	           </td>
	           <td colspan="3" rowspan="5" valign="top" align="left">
		           		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                   <tr>
		                      <td width="65" valign="top">������  <font color="#FF0000">*</font> :</td>
		                      <td align="center" >
		                      		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		                      				<tr>
		                      						<td class="line-l-dash line-t-dash line-b-dash" width="" align="left">&nbsp;<b>��ҧ������</b></td>
		                      						<td class="line-l-dash line-t-dash line-b-dash" width="15%" align="center"><b>�ѹ����Դ�ҹ</b></td>
		                      						<td class="line-l-dash line-t-dash line-b-dash" width="15%" align="center"><b>�ѹ���Դ�ҹ</b></td>
		                      						<td class="line-l-dash line-t-dash line-r-dash line-b-dash" width="15%" align="center"><b>ʶҹ�</b></td>
		                      						<td width="10%" align="center">
		                      								<?php if($userInfo['user_type']=="AM"){?>
		                      										<span class="add-icon" style="width:24px;" onclick="javascript:openPopup();"></span>
		                      								<?php }?>
		                      						</td>
		                      				</tr>
		                      				<tbody id="support-list">
		                    
		                   					</tbody>
		                      		</table>
		                      </td>
		                   </tr>
		                </table>
	           </td>
	         </tr>
         </tbody>
         <?php }?>
         <tr>
           <td>&nbsp;</td>
           <td></td>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
           <td></td>
           <td></td>
         </tr>
      </table>
    </form>
    <iframe id="upload_target" name="upload_target" src="" style="width:100%;height:400px;border:1px solid #ccc; display:none"></iframe>
  </div>
  <div class="content-top">
    <table width="98%" align="center">
    	<tr>
   			<td height="20" align="center">
   			    <?php if($userInfo['user_type']=="M"){?>
   			           <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="save-btn" href="javascript:void(0);" onclick="javascript:saveData();"> �ѹ�֡  </a>
   			    	   <a class="button-bule" style="display:<?php if(empty($back) and empty($close)){echo "";}else{echo "none;";}?>" id="openJob-btn" href="javascript:void(0);" onclick="javascript:openJob();"> �Դ Job  </a>
   			    	   <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="closeJob-btn" href="javascript:void(0);" onclick="javascript:closeJob();"> �Դ Job  </a>
   			    	   <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="sentOth-btn" href="javascript:void(0);" onclick="javascript:sendToJob(<?php print($FRequestID);?>,'send');"> Assign to other  </a>
   			    	   <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="sendTo-btn" href="javascript:void(0);" onclick="javascript:sendToJob(<?php print($FRequestID);?>,'close');"> Send to close  </a>
   			   <?php }else if($userInfo['user_type']=="AM"){?>
               	       <a class="button-bule" id="save-btn" href="javascript:void(0);" onclick="javascript:saveData();"> �ѹ�֡  </a>
               	       <a class="button-bule" style="display:<?php if(empty($back) and empty($close)){echo "";}else{echo "none;";}?>" id="openJob-btn" href="javascript:void(0);" onclick="javascript:openJob();"> �Դ Job  </a>
               	       <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>"  id="sendTo-btn" href="javascript:closeJob();" onclick="javascript:sendToJob(<?php print($FRequestID);?>);"> �Դ Job  </a>
               	       <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="closeJob-btn" href="javascript:void(0);" onclick="javascript:closeAll();"> �Դ Job ������  </a>
   			    <?php }else if($userInfo['user_type']=="GM"){?>
   			    		<a class="button-bule" id="save-btn" href="javascript:void(0);" onclick="javascript:saveData();"> �ѹ�֡  </a>&nbsp;
   			    	   <a class="button-bule" style="display:<?php if(empty($back) and empty($close)){echo "";}else{echo "none;";}?>" id="openJob-btn" href="javascript:void(0);" onclick="javascript:openJob();"> �Դ Job  </a>
   			    	    <a class="button-bule" style="display:<?php if($close=="y" OR $close=="holder"){echo "";}else{echo "none;";}?>" id="closeJob-btn" href="javascript:void(0);" onclick="javascript:closeJob();"> �Դ Job  </a>
                <?php }else{?>
   					  <a class="button-bule" href="javascript:void(0);" onclick="javascript:saveData();"> �ѹ�֡  </a>
   				<?php }?>
   				<a class="button-bule" href="javascript:void(0);" onclick="javascript:cancel();"> ¡��ԡ  </a>
   			</td>
   		</tr>
    </table>	
  </div>
  <div class="login-overlay" style="display:none">
  		<div class="preloading"></div>
  </div>
  
</body>

<script>

	//$('.select2').select2();
	$('.select2').select2({ containerCssClass: "FontPued" ,dropdownCssClass: "FontPued" });
	var  user_id = '<?=$userId?>';
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-85;
		var preloading = (height-40)/2;
		$(".list-body").height(main_body_height);
		$(".preloading").attr("style","margin-top:"+preloading+"px;");

		$('#FReciveDate').Zebra_DatePicker({
			  direction: true
		});
		$('#FEditDate').Zebra_DatePicker({
			 onSelect : function(){
				 		if($("#FEstimate").val()==""){
								var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FEditDate\').val(\'\');$(\'#FEstimate\').focus();"}]';
								buttons = eval(buttons);
								_confirm("warning","Warning","��س��кبӹǹ�ѹ��˹�����",buttons);
						}else{
								var newDate = 	incr_date($('#FEditDate').val(),(($('#FEstimate').val()*1)-1));
								$('#FDueDate').val(newDate);
						}
				 }
		});
		$('#FFinishDate').Zebra_DatePicker();
		$('#FUseDate').Zebra_DatePicker();
	});

	 $(function(){
        $('.chzn-select').chosen({width: "95%"});
     });

	function openPopup(id,sendTo){
		if(isNaN(id))id='<?=$FRequestID?>';
		if(sendTo=="sendTo")parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/support-information.php?id='+id,boxid:'frameless',width:650,height:350,fixed:false,maskopacity:40,closejs:function(){closeJob()}});
		else if(sendTo=="sendOth")parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/support-information.php?id='+id,boxid:'frameless',width:650,height:350,fixed:false,maskopacity:40,closejs:function(){saveData()}});
		else parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/support-information.php?id='+id,boxid:'frameless',width:650,height:350,fixed:false,maskopacity:40});
	}

	function cancel(){
		window.location.href = '<?php print($backUrl);?>?u_id=<?php print($userId);?>&e_id=<?php print($empId);?>&compId=<?php print($compId);?>'
    }
	function getEmpInfo(){
		$.ajax({ 
			url: "../../../../main/controllers/utilities_controller.php" ,
			type: "POST",
			dataType: 'json',
			data: {"function":"get_EmpInfor",
				   "emp_code":$("#emp_code").val(),
				   "comp_id":<?php print($compId);?>
			}
		})
		.success(function(json) { 
			if(json['result']==1){
				$('#emp_name').val(json['first_name']+" "+json['last_name']);
				$('#FReqID').val(json['emp_id']);
				$('#FPosition').val(json['post_name']);
				$('#sec_nameThai').val(json['sec_nameThai']);
				$('#FSectionID').val(json['sec_id']);
				$('#brn_name').val(json['brn_name']);
				$('#FBranchID').val(json['brn_id']);
				 $(function(){
					  $.uniform.update("#FBranchID");
				  });
			}else{
				var buttons = '[{"title":"OK","class":"blue","action":"$(\'#emp_code\').focus();"}]';
				buttons = eval(buttons);
				_confirm("warning","Warning","��辺�����Ţͧ��ѡ�ҹ���� "+$('#emp_code').val()+" ��سҵ�Ǩ�ͺ�����١��ͧ",buttons);
			}
		});
	}
	function updateRepairItem(selected){
		$.ajax({
				type: "POST",
				url: ("../../../../main/controllers/utilities_controller.php"),
				data: "1&function=get_RepairGroupItemJson&group_id="+$('#FRepairGroupID').val(),
				dataType: 'json',
				success: function(data){
						appendOption(document.getElementById('FRepairGroupItemID'),data);
						$("#FRepairGroupItemID option[value=" +selected+"]").attr("selected","selected") ;
						setSelectValue('FRepairGroupItemID');
						
				}
			});
    }
	function updateRepairType(selected){
		$.ajax({
				type: "POST",
				url: ("../../../../main/controllers/utilities_controller.php"),
				data: "1&function=get_RepairItemJson&mtype_id="+$('#FMachineTypeID_JobLevel').val(),
				dataType: 'json',
				success: function(data){
						appendOption(document.getElementById('FRepairItemID'),data);
						$("#FRepairItemID option[value=" +selected+"]").attr("selected","selected") ;
						setSelectValue('FRepairItemID');
						
				}
			});
    }
	function showJobLevel(){
		//alert($("#FMachineTypeID_JobLevel").val());
		var	param =�'JobLevel_Data=ok';
				param += '&FMachineTypeID_JobLevel='+$('#FMachineTypeID_JobLevel').val();	
				param += '&xid='+Math.random();		
				//alert(param);
				 			getData = $.ajax({
										url:'?',
										data:encodeURI(param),
										async:false,
										success: function(getData){
										//alert(getData);
										$("#FJobLevel").val(getData)
												
										}
							}).responseText;	
	
    }
    function uploadData(){
		document.forms[0].submit();
    }
    function uploadComplete(id,filename,url){
        var li = '<ul id="attach-list-'+id+'" class="attach-list">';
		    li+= '   <li style="width:80%"> <a href="javascript:void(0);" onclick="javascript:downloadFile('+$('#FRequestID').val()+',\''+filename+'\',\''+url+'\')">'+filename+'</a></li>';
		    li+= '   <li style="width:20%"><div class="trash-icon" onclick="javascript:deleteFile('+id+',\''+url+'\');"></div></li>';
		    li+= '</ul>';
		$('#attach-list').append(li);
    }
    function deleteFile(id,url){
    	$.ajax({
			type: "POST",
			url: ("../../../controllers/request_controller.php"),
			data: "1&function=delete_file&rId="+$('#FRequestID').val()+'&id='+id+'&url='+url,
			dataType: 'json',
			success: function(data){
				$('#attach-list-'+id).remove(); 
			}
		});
    }
    function downloadFile(id,filename,url){
    	var width = screen.width-10;
		var height = screen.height-60;
		newwindow=window.open('../../../../download.php?name='+filename+'&reqId='+id+'&filename='+url,
									  'downloadWindow','width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
    }
    function selectSupport(order,id,name,status,start_date,finish_date){
		var html = '<tr id="support-'+id+'-'+order+'">';
            html+= '	  <td class="line-l-dash line-b-dash" align="left">&nbsp;'+name+'</td>';
            html+='	  <td class="line-l-dash line-b-dash" align="center">'+start_date+'</td>';
            html+='	  <td class="line-l-dash line-b-dash" align="center">'+finish_date+'</td>';
            html+='	  <td class="line-l-dash line-r-dash line-b-dash" align="center"><b>'+status+'</b></td>';
            html+='	  <td align="center">';
            if($('#user_type').val()=='AM' || $('#is_sendTo').val()=='Y')html+= '      <span class="remove-icon" style="padding-left:0px;width: 24px;" onClick="javascript:confirmRemove('+id+','+order+');"></span>';
            html+= '	  <input type="hidden" name="fields[supports]['+order+'][id]" id="support_'+order+'" value="'+id+'">';
            html+= '	  <input type="hidden" name="fields[supports]['+order+'][order]" id="order_'+order+'" value="'+order+'">';
			html+= '	  <input type="hidden" name="fields[supports]['+order+'][send_to]" id="send_to_'+order+'" value="'+$('#is_sendTo').val()+'">';
			html+= '	  <input type="hidden" name="fields[supports]['+order+'][status]" id="status_'+order+'" value="'+status+'">';
			html+= '	  <input type="hidden" name="fields[supports]['+order+'][start_date]" id="start_date_'+order+'" value="'+start_date+'">';
			html+= '	  <input type="hidden" name="fields[supports]['+order+'][finish_date]" id="finish_date_'+order+'" value="'+finish_date+'">';
            html+='	  </td>';
            html+= '</tr>';
        $('#support-list').append(html);
	}
	function getSupport(){
		var ids = 0;
		$('#support-list').find('input[id^=order_]').each(function(){
			var order = $(this).val();
			if($('#status_'+order).val()!="finished"){
				ids +=','+$('#support_'+order).val();
			}
		});
		return ids;
	}
	function countSupportAssign(){
		var assignment = 1;
		$('#support-list').find('input[id^=support_]').each(function(){
				assignment++;
		});
		return assignment;
	}
	function confirmRemove(id,order){
		var buttons = '[{"title":"OK","class":"blue","action":"removeSupport('+id+','+order+');"},{"title":"Cancel","class":"blue","action":""}]';
		buttons = eval(buttons);
		_confirm("warning","Warning","Confirm to remove",buttons);
	}
	function removeSupport(id,order){
		$.ajax({
			type: "POST",
			url: ("../../../controllers/request_controller.php"),
			data: "1&function=remove_support&FRequestID="+$('#FRequestID').val()+'&FOrder='+order+'&FSupportID='+id,
			dataType: 'json',
			success: function(data){
				$('#support-'+id+'-'+order).remove(); 
			}
		});
	}
	
	function openJob(){
		var is_process = 1;
		if($('#FEditDate').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":""}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��ѹ����Դ Job",buttons);
		}else{
			$('.login-overlay').show();
			var dueDate = incr_date($('#FEditDate').val(),$('#FEstimate').val());
			$('#FDueDate').val(dueDate);
			$('#FStatus').val("inprogress");
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/request_controller.php"),
				data: "1&function=open_request&"+params,
				dataType: 'json',
				success: function(data){
					$.ajax({
							type: "POST",
							url: ("../../../controllers/request_controller.php"),
							data: "1&function=load_support&FRequestID="+$('#FRequestID').val(),
							dataType: 'json',
							success: function(json){
								$('#support-list').empty();
								for(var i=0;i<json.length;i++){
									var fields = json[i];
									var name = fields['first_name']+'  '+fields['last_name'];
									selectSupport(fields['FKeyInsert'],fields['FSupportID'],name,fields['FStatus'],fields['FStartDate'],fields['FFinishDate']);
								}
								$('.login-overlay').hide(); 
								$('#closeJob-btn').show();
								$('#sendTo-btn').show();
								$('#openJob-btn').hide();
								$('#sentOth-btn').show();
								$('#save-btn').show();
							}
					  });
					
				}
			});
		}
	}

	function closeAll(){
		if($('#FFinishDate').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":""}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��ѹ���Դ Job",buttons);
		}else{
			var supports = 0;
			var params = getRequestBody();
			$('.login-overlay').show();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/request_controller.php"),
				data: "1&function=insert_data&"+params,
				dataType: 'json',
				success: function(data){
					//alert(data);
						$('.login-overlay').show();
						var params = getRequestBody();
						$.ajax({
							type: "POST",
							url: ("../../../controllers/request_controller.php"),
							data: "1&function=close_request_all&"+params,
							dataType: 'json',
							success: function(data){
								var buttons = '[{"title":"OK","class":"blue","action":"$(\'.login-overlay\').hide();cancel();"}]';
								buttons = eval(buttons);
								_confirm("infor","Warning","�ӡ�ûԴ�ҹ���º��������",buttons);
							}
						});
				}
			});
		}
	}
	
	function closeJob(){
		if($('#FFinishDate').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":""}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��ѹ���Դ Job",buttons);
		}else{
			var supports = 0;
			var params = getRequestBody();
			$('.login-overlay').show();
			if($('#is_sendTo').val()=='Y'){
				$('#support-list').find('input[id^=send_to_]').each(function(){
					if($(this).val()=='Y')supports++;
				});
				if(supports==0){
					var buttons = '[{"title":"OK","class":"blue","action":"openPopup('+$('#FRequestID').val()+')"}]';
					buttons = eval(buttons);
					_confirm("warning","Warning","��س��кؼ���Ѻ�ҹ���",buttons);
				}else{
					$('.login-overlay').show();
					var params = getRequestBody();
					$.ajax({
						type: "POST",
						url: ("../../../controllers/request_controller.php"),
						data: "1&function=close_request&"+params,
						dataType: 'json',
						success: function(data){
							$('.login-overlay').hide(); 
							cancel();
						}
					});
				}
			}else{
				$('.login-overlay').show();
				var params = getRequestBody();
				$.ajax({
					type: "POST",
					url: ("../../../controllers/request_controller.php"),
					data: "1&function=close_request&"+params,
					dataType: 'json',
					success: function(data){
						$('.login-overlay').hide(); 
						cancel();
					}
				});
			}
		}
	}
	
	function sendToJob(id,type){
		if(type=="close"){
			if($('#FFinishDate').val()==""){
				var buttons = '[{"title":"OK","class":"blue","action":""}]';
				buttons = eval(buttons);
				_confirm("warning","Warning","��س��к��ѹ���Դ Job",buttons);
			}else{
				$('#is_sendTo').val('Y');
				openPopup(id,'sendTo');
				$('#sendTo-btn').hide();
			}
		}else if(type=="send"){
			$('#is_sendTo').val('Y');
			openPopup(id,'sendOth');
			$('#sendOth-btn').hide();
		}
		
	}

	function checkAsset(){
		$.ajax({
			type: "POST",
			url: ("http://localhost/service.asset/controllers/asset_controller.php"),
			data: "1&assetNo="+$('#FAsset_no').val(),
			dataType: 'json',
			success: function(data){
				return data;
			}
		});
	}
	
    function saveData(){
    	if($('#FReqID').val()==""){
    		var buttons = '[{"title":"OK","class":"blue","action":"$(\'#emp_code\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к����ʾ�ѡ�ҹ����觤���ͧ",buttons);
        }else if($('#FManagerID').val()==""){
        	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FManagerID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��кؼ��Ѵ��� /���˹�ҧҹ",buttons);
        }else if($('#FSupervisorID').val()==""){
        	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FSupervisorID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��кؼ���ӹ�¡��",buttons);
        }else if($('#FInf_no').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FInf_no\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��Ţ�����ҧ�ԧ",buttons);
		}else if($('#FAsset_no').val()=="" && $('#no_asset').is(':checked')==false){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FAsset_no\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��Ţ����Ѿ���Թ",buttons);
	    }else if($('#FAsset_no').val()=="" && $('#FRepairGroupID').val()=="6"){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FAsset_no\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��Ţ����Ѿ���Թ",buttons);
		}else if($('#FFnc').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FFnc\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к�˹��§ҹ",buttons);
		}else if($('#FTel').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FTel\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к�������.",buttons);
		}else if($('#FBranchID').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FBranchID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��Ңҷ��Դ���",buttons);
	    }else if($('#FRepair_comp_id').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FBranchID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��кغ���ѷ���Դ���",buttons);
	    }else if($('#FLocation').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FLocation\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к��Ҥ�� / ʶҹ���",buttons);
		}else if($('#FFloor').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FFloor\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��кت��",buttons);
	    }else if($('#FRoom').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FRoom\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к� ��ͧ",buttons);
		}else if($('#FRepairGroupID').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FRepairGroupID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к� ��¡�â��駫���",buttons);
	    }else if($('#FRepairGroupItemID').val()==""){
	    	var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FRepairGroupItemID\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к���¡�ë���",buttons);
		}else if($('#FDetail').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FDetail\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��к���������´ / �ѭ��",buttons);
	    }else if($('#FJobLevel').val()==""){
			var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FJobLevel\').focus();"}]';
			buttons = eval(buttons);
			_confirm("warning","Warning","��س��кػ������ҹ",buttons);
	    }else{
	    	$.ajax({
				type: "POST",
				url: "http://10.2.1.225/service.asset/controllers/asset_controller.php",
				data: "1&assetNo="+$('#FAsset_no').val(),
				crossDomain: true,
				dataType: 'json',
				success: function(data){
					if($('#FRequestID').val()!="")data = 1;
					if(data==1){
						var is_process = 1;
					    var supports = getSupport();
					    if($('#user_type').val()=="AM"){
							if($('#FEstimate').val()==""){
								is_process = 0;
								var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FEstimate\').focus();"}]';
								buttons = eval(buttons);
								_confirm("warning","Warning","��س��кبӹǹ�ѹ��˹�����",buttons);
							}else if(supports=="0"){
								is_process = 0;
								var buttons = '[{"title":"OK","class":"blue","action":"openPopup();"}]';
								buttons = eval(buttons);
								_confirm("warning","Warning","��س��кؼ���Ѻ�Դ�ͺ",buttons);
							}
							//if($('#FEditDate').val()!=="" && $('#FFinishDate').val()=="")$('#FStatus').val('inprogress');
							//else if($('#FFinishDate').val()!='')$('#FStatus').val('finished');
						}/*End if($('#user_type').val()=="AM")*/
					    if(is_process==1){
							$('.login-overlay').show();
							if(($('#FStatus').val()=="new" || $('#FStatus').val()=="waiting") && $('#user_type').val()!="E"){
								if($('#FStatus').val()=="new")$('#FStatus').val("inprogress");
								if($('#FApprove').is(':checked')==true )$('#FStatus').val("waiting");
								else $('#FStatus').val("inprogress");
							}else{
								if($('#FApprove').is(':checked')==true && $('#FReceiveDoc').val()=='N'){
									$('#FStatus').val("waiting");
								}
							}
							var params = getRequestBody();
							$.ajax({
								type: "POST",
								url: ("../../../controllers/request_controller.php"),
								data: "1&function=insert_data&"+params,
								dataType: 'json',
								success: function(data){
									$('#FRequestID').val(data['req_id']);
									$('#FReqNo').val(data['req_no']);
									$('.request_no').empty().html(data['req_no']);
									var buttons = '[{"title":"OK","class":"blue","action":"$(\'.login-overlay\').hide();"}]';
									buttons = eval(buttons);
									_confirm("infor","Warning","�ӡ�úѹ�֡���������º��������",buttons);
									$('#attachment').show();
									 
									//if($('#FEditDate').val()!="")$('#close-job').show();
								}
							});
					    }
					}else{
						var buttons = '[{"title":"OK","class":"blue","action":"$(\'#FAsset_no\').focus();"}]';
						buttons = eval(buttons);
						_confirm("warning","Warning","��辺�Ţ����Ѿ���Թ����к� ��سҵ�Ǩ�ͺ",buttons);
					}
				}
			});
		}
    }/*End of function checkData()*/
    
    if($('#FRequestID').val()!=""){
			var params = getRequestBody();
			$.ajax({
				type: "POST",
				url: ("../../../controllers/request_controller.php"),
				data: "1&function=get&"+params,
				dataType: 'json',
				success: function(json){
					//console.log(json);
					  assignFields(json);
					  if(json['FAsset_no']==""){
						  $('#no_asset').attr('checked','checked');
						  if(json['FApprove']=="Y") $('#FApprove').attr('checked','checked');
						  if(json['FReam']=="Y")$('#FReam').attr('checked','checked');
						  $(function(){
							  $.uniform.update("#no_asset");
							  $.uniform.update("#FApprove");
							  $.uniform.update("#FReam");
						  });
					  }
					  //if(json['FEditDate']!="")$('#close-job').show();
					  if(json['FStatus']=="waiting"){
						  $('#FApprove').attr('checked','checked');
						  $(function(){
							  $.uniform.update("#FApprove");
						  });
					  }
					  updateRepairItem(json['FRepairGroupItemID']);
					  $('.request_no').empty().html(json['FReqNo']);
					  $('#attachment').show();
					  $.ajax({
							type: "POST",
							url: ("../../../controllers/request_controller.php"),
							data: "1&function=list_attach&FRequestID="+$('#FRequestID').val(),
							dataType: 'json',
							success: function(json){
								for(var i=0;i<json.length;i++){
									var files = json[i];
									uploadComplete(files['FAttachID'],files['FAttachName'],files['FAttachLink']);
								}
							}
					  });
					  if($('#user_type').val()!='E'){
						     updateRepairType(json['FRepairItemID']);
							  $.ajax({
									type: "POST",
									url: ("../../../controllers/request_controller.php"),
									data: "1&function=load_support&FRequestID="+$('#FRequestID').val(),
									dataType: 'json',
									success: function(json){
										for(var i=0;i<json.length;i++){
											var fields = json[i];
											var name = fields['first_name']+'  '+fields['last_name'];
											selectSupport(fields['FOrder'],fields['FSupportID'],name,fields['FStatus'],fields['FStartDate'],fields['FFinishDate']);
										}
									}
							  });
							  if($('#user_type').val()=='M' || $('#user_type').val()=='AM'){
								  $('#FEditDate').val('');
								  $('#FDueDate').val('');
								  $.ajax({
										type: "POST",
										url: ("../../../controllers/request_controller.php"),
										data: "1&function=req-support&FRequestID="+$('#FRequestID').val()+'&FSupportID='+$('#user_id').val(),
										dataType: 'json',
										success: function(json){
											assignFields(json);
											if($('#user_type').val()=='AM' && (user_id!=json['FSupportID']))$('#closeJob-btn').hide();
											if(json['FStartDate']!=''){
												var dueDate = incr_date(json['FStartDate'],$('#FEstimate').val());
												$('#FDueDate').val(dueDate);
											}
										}
								  });
							  }
				     }
				}
			});
	}
	function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('FBranchID'),data);
							if(selected!=''){
									$("#brn_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('FBranchID');
					}
				});
	}	
	
</script>
</html>