<?php 
include '../../../../lib/db_config.php';
include '../../../../main/modules/Model_Utilities.php';
include '../../../modules/purchase_model.php';

$utilMD = new Model_Utilities();
$objMD = new Model_Purchase();
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');
$_suplierList = $utilMD->get_suplierList();

if($cmd=='up_nature'){
			//check num worl form master
			$sql_check="SELECT
							mtrequest_db.tbl_request.FRequestID,
							mtrequest_db.tbl_request.FMachineTypeID,
							mtrequest_db.tbl_request.FJobLevel,
							mtrequest_db.tbl_request.FJob_description,
							mtrequest_db.tbl_request.FReqID,
							mtrequest_db.tbl_request.FReqNo
							FROM
							mtrequest_db.tbl_request
							WHERE
							mtrequest_db.tbl_request.FMachineTypeID IS NOT NULL AND
							mtrequest_db.tbl_request.FJobLevel IS NOT NULL ";
			$query_check=mysql_query($sql_check);
			while($row_check= mysql_fetch_assoc($query_check)){
					    $sql_general="SELECT
							general_db.tbl_machinetype.FMachineTypeID,
							general_db.tbl_machinetype.FJob_description,
							general_db.tbl_machinetype.FJobLevel,
							general_db.tbl_machinetype.FMachineTypeName
							FROM
							general_db.tbl_machinetype 
							WHERE general_db.tbl_machinetype.FMachineTypeID = '".$row_check[FMachineTypeID]."' ";
						$query_general=mysql_query($sql_general);
						$row_general= mysql_fetch_assoc($query_general);
				
						if($row_general[FJob_description]=='1'){
							$FMachineTypeName = '�ҹ�����';
						}else if($row_general[FJob_description]=='2'){
							$FMachineTypeName = '�ҹ����ͧ�ѡ�';
						}else if($row_general[FJob_description]=='3'){
							$FMachineTypeName = '����Ẻ�����';
						}else if($row_general[FJob_description]=='4'){
							$FMachineTypeName = '�ҹ�͡Ἱ';
						}else if($row_general[FJob_description]=='5'){
							$FMachineTypeName = '�ҹ����';
						}else if($row_general[FJob_description]=='6'){
							$FMachineTypeName = '�ҹ����ͼ������þԨ�ó�';
						}
						
				$sql_up_mt = "UPDATE mtrequest_db.tbl_request SET";
				$sql_up_mt .= " mtrequest_db.tbl_request.FJob_description = '".$FMachineTypeName."' ";
				$sql_up_mt .= " WHERE mtrequest_db.tbl_request.FRequestID = '".$row_check[FRequestID]."' ";
				//echo $sql_up_mt;
				$query_up_mt=mysql_query($sql_up_mt);
				
			}
	exit();
}
?>
<!DOCTYPE HTML">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS874">
<script  type="text/javascript" src="../../../../jsLib/jquery-1.8.0.min.js"></script>
<script src="../../../../jsLib/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script src="../../../../jsLib/js_scripts/js_function.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="../../../../jsLib/datepicker/zebra_datepicker.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../../jsLib/jquery-confirm/js/script.js"></script>

<link href="../../../../css/dialog-box.css" rel="stylesheet" type="text/css">
<link href="../../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../../css/input.css" rel="stylesheet" type="text/css">
<link href="../../../../css/stylesheet_report.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<title>��§ҹ��ػ��Ш���͹</title>
<script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select").uniform();
      });
</script>
</head>
<body>
   <div class="dialog-panel condition-panel">
   		<div class="top-row">
   			<div class="left"></div>
   			<div class="center">
   				<span class="dialog-title">��§ҹ��ػ��Ш���͹</span>
   			</div>
   			<div class="right"></div>
   		</div> 
   		<div class="middle-row" style="height:100%;">
   			<div class="left"></div>
   			<div id="dialog-body" class="center">
   				<table width="100%" border="0">
                	<tr>
   							<td style="width:15%"><strong>����ѷ :</strong></td>
   							<td colspan="5"><select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
             						<option value="">---������---</option>
             						<?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
             						<option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
             						<?php }}?>
        						</select></td>
   				    </tr>
   				   <tr>
   						<td style="width:10%"><strong>�Ң� :</strong></td>
   						<td colspan="5"><select name="brn_id" id="brn_id" class="uniform-select">
						  <option value="">---������---</option>
   							  <?php if(!empty($brnList)){
				            			foreach($brnList as $key=>$val){
				            	?>
   							  <option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
   							  <?php }}?>
						  </select>
   						</td>
   					</tr>
   					 <tr>
   						<td><span style="width:10%"><strong>Ἱ� :</strong></span></td>
   						<td colspan="5"><select name="sec_id" id="sec_id" class="uniform-select">
   						  <option value="">---������---</option>
   						  <?php if(!empty($sectList)){
				            			foreach($sectList as $key=>$val){
				            	?>
   						  <option value="<?php print($val['sec_id']);?>"><?php print($val['sec_nameThai']);?></option>
   						  <?php }}?>
					    </select></td>
   					</tr>
   					<tr>
   						<td><strong>�ѹ����� :</strong></td>
   						<td colspan="2" style="width:38%">
   							<input type="text" name="SRequestDate" id="SRequestDate" style="width:80%;" value="">-<input type="text" name="ERequestDate" id="ERequestDate" style="width:80%;" value="">
   						</td>
   						<td style="width:13%"><strong>��˹����� :</strong></td>
   						<td colspan="2" style="width:44%">
   							<input type="text" name="SDueDate" id="SDueDate" style="width:80%;" value="">-<input type="text" name="EDueDate" id="EDueDate" style="width:80%;" value="">
   						</td>
   					</tr>
   					<!--  <tr>
   						<td style="vertical-align: top;"><strong>Suport :</strong></td>
   						<td>
   							<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                   <tbody id="support-list">
			                    
			                   </tbody>
			                </table>
   						</td>
   						<td style="vertical-align: top;" colspan="4">
   							<span class="add-icon" style="width:24px;" onclick="javascript:openPopup();"></span>
						</td>
   					</tr>-->
   				</table>
   			</div>
   			<div class="right"></div>
   		</div>
   		<div class="bottom-row">
   			<div class="left"></div>
   			<div class="center">
   				<table width="100%">
   			    	<tr>
   			    		<td width="50%" align="left">
   			    				<a class="button-bule" href="javascript:void(0);" onclick="javascript:new_oldWindow();"> EXCEL��§ҹ���  </a>
   			    		</td>
   			    		<td align="right">
   			    				<?php /* <a class="button-bule" href="javascript:void(0);" onclick="javascript:up_nature();">111</a> */?> <a class="button-bule" href="javascript:void(0);" onclick="javascript:newWindow();"> �͡��§ҹ  </a>
   			    		</td>
   			    	</tr>
   			    </table>
   			</div>
   			<div class="right"></div>
   		</div>
   </div>
</body>
<script>
$(document).ready(function() {
	 var doc_height = $(document).height();
	 var condition_height = $('.condition-panel').height();
	 var log_top = (doc_height-condition_height)/2;
	 
	 $('.condition-panel').css('top',log_top+"px");

	 $('#SRequestDate').Zebra_DatePicker();
	 $('#ERequestDate').Zebra_DatePicker();
	 $('#SDueDate').Zebra_DatePicker();
	 $('#EDueDate').Zebra_DatePicker();
	 $('.selector').css('width','80%');
	 $('.selector > span').css('width','80%');
	 $('.uniform-select').css('width','85%');
});
function openPopup(id){
	if(isNaN(id))id='';
	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/support-information.php?id='+id,boxid:'frameless',width:650,height:350,fixed:false,maskopacity:40});
}
function getSupport(){
	var ids = 0;
	$('#support-list').find('input[id^=support_]').each(function(){
		ids +=','+$(this).val();
	});
	return ids;
}
function selectSupport(id,name){
	var html = '<tr id="support-'+id+'">';
        html+= '   <td>&nbsp;'+name+'</td>';
        html+= '   <td align="center" width="24">';
		html+= '      <span class="remove-icon" style="padding-left:0px;width: 24px;" onClick="javascript:confirmRemove('+id+');"></span>';
		html+= '	  <input type="hidden" name="fields[supports]['+id+'][id]" id="support_'+id+'" value="'+id+'">';
        html+= '   </td>';
        html+= '</tr>';
    $('#support-list').append(html);
}
function confirmRemove(sId){
	var buttons = '[{"title":"OK","class":"blue","action":"removeSupport('+sId+');"},{"title":"Cancel","class":"blue","action":""}]';
	buttons = eval(buttons);
	_confirm("warning","Warning","Confirm to remove",buttons);
}
function removeSupport(sId){
	$('#support-'+sId).remove();
}

function newWindow(){
	var width = screen.width-10;
	var height = screen.height-60;
	var params = "";
	params +="sec_id="+$('#sec_id').val();
	params +="&brn_id="+$('#brn_id').val();
    params +="&FRepair_comp_id="+$('#FRepair_comp_id').val();
	params +="&SRequestDate="+$('#SRequestDate').val();
	params +="&ERequestDate="+$('#ERequestDate').val();
	params +="&SDueDate="+$('#SDueDate').val();
	params +="&EDueDate="+$('#EDueDate').val();
	newwindow=window.open('request-report.page.tpl.php?'+params,
								  'reportWindow'+Math.random()*10000,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}

function new_oldWindow(){

	var width = screen.width-10;
	var height = screen.height-60;
	var params = "";
	params +="sec_id="+$('#sec_id').val();
	params +="&brn_id="+$('#brn_id').val();
    params +="&FRepair_comp_id="+$('#FRepair_comp_id').val();
	params +="&SRequestDate="+$('#SRequestDate').val();
	params +="&ERequestDate="+$('#ERequestDate').val();
	params +="&SDueDate="+$('#SDueDate').val();
	params +="&EDueDate="+$('#EDueDate').val();
    params +="&cmd=excel";
	newwindow=window.open('request-report.page.tpl.php?'+params,
								  'reportWindow'+Math.random()*10000,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
}
	function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_BranchJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('brn_id'),data);
							if(selected!=''){
									$("#brn_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('FBranchID');
					}
				});
	}	
	function updateSection(selected){
			$.ajax({
					type: "POST",
					url: ("../../../../main/controllers/utilities_controller.php"),
					data: "1&function=get_SectJson&comp_id="+$('#FRepair_comp_id').val(),
					dataType: 'json',
					success: function(data){
							appendOption(document.getElementById('sec_id'),data);
							if(selected!=''){
									$("#sec_id option[value=" +selected+"]").attr("selected","selected") ;
							}
							setSelectValue('sec_id');
					}
				});
	}
    
    function up_nature(){
            param ="cmd=up_nature";			
            param +="&xid="+Math.random();		
            getData = $.ajax({
                        url:'?',
                        data:encodeURI(param),
                        async:false,
                        success: function(getData){
                            alert(getData);
                        }
                    }).responseText;	
	
	}
</script>
</html>