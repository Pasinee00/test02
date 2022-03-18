<!DOCTYPE html>
<?php 
include '../../../lib/db_config.php';
include '../../../main/modules/Model_Utilities.php';
	
$utilMD = new Model_Utilities();
$comList = $utilMD->get_CompList();
$brnList = $utilMD->get_BranchList($comList);
$sectLst = $utilMD->get_SectList($comList, '');

$empId = $_REQUEST['e_id'];
$userId = $_REQUEST['u_id'];
$status = $_REQUEST['status'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>Request</title>
<link href="../../../css/sys_controll.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/jquery-confirm/jquery.confirm.css" rel="stylesheet" type="text/css">
<link href="../../../jsLib/datepicker/css/default.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../jsLib/uniform/css/uniform.default.css" type="text/css" media="screen">
<link href="../../../css/input.css" rel="stylesheet" type="text/css">

<script  type="text/javascript" src="../../../jsLib/jquery-1.8.0.min.js"></script>
<script  type="text/javascript" src="../../../jsLib/uniform/jquery.uniform.js"  charset="utf-8"></script>
<script  type="text/javascript" src="../../../jsLib/js_scripts/js_function.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/jquery.confirm.js"></script>
<script  type="text/javascript" src="../../../jsLib/jquery-confirm/js/script.js"></script>
<script type="text/javascript" src="../../../jsLib/datepicker/zebra_datepicker.js"></script>


<script>
$(function(){
    $("input, textarea").uniform();
    $(".uniform-select").uniform();
  });
</script>
</head>

<body scrolling="no">
  <div class="content-top">
  	<div class="_content-title">�Դ����ҹ��觫���  </div>
  	<div class="search-action">
  	    <span class="euro-sign-status" style="padding-left:30px;padding-right:10px;">�͡����觫���</span>
  		<span class="pound-sign-status" style="padding-left:30px;padding-right:10px;">�ӡ����觫�������</span>
  		<span class="dollar-sign-status" style="padding-left:30px;padding-right:10px;">���Ѻ�ͧ����</span>
  	</div>
  </div>
  <table width="98%" align="center">
    <tr>
  		<td width="6%">����ѷ :</td>
  		<td width="20%"><select name="fields[FRepair_comp_id]" id="FRepair_comp_id" class="uniform-select" onChange="javascript:updateBranch('');javascript:updateSection('');">
  		  <option value="">-------����ѷ-------</option>
  		  <?php if(!empty($comList)){ foreach ($comList as $key=>$val){?>
  		  <option value="<?php echo $val['comp_id'];?>" ><?php echo $val['comp_code']." - ".$val['comp_name'];?></option>
  		  <?php }}?>
	    </select></td>
  		<td width="7%">�Ң� :</td>
  		<td width="28%">
  				 <select name="FBranchID" id="FBranchID" class="uniform-select" >
	            	<option value="">---��س����͡�Ң�</option>
	            	<?php if(!empty($brnList)){
	            			foreach($brnList as $key=>$val){
	            	?>
	            				<option value="<?php print($val['brn_id']);?>" <?php if($val['brn_id']== $userInfo['brn_id']){?>selected<?php }?>><?php print($val['brn_name']);?></option>
	            	<?php }}?>
              </select>
  		</td>
  		<td width="7%">Ἱ� : </td>
  		<td width="24%"><select name="sec_id" id="sec_id" class="uniform-select">
  		  <option value="">---��س����͡Ἱ�---</option>
  		  <?php foreach($sectLst as $key=>$val){?>
  		  <option value="<?php print($val['sec_id']);?>">[<?php print($val['sec_code']);?>] <?php print($val['sec_nameThai']);?></option>
  		  <?php }?>
	    </select></td>
  		<td width="8%" rowspan="2" align="center"><a class="button-bule" href="javascript:void(0);" onclick="javascript:changePage();"> Search </a>
  		</td>
  
  	</tr>
    <tr>
      <td>ʶҹ� :</td>
      <td><select name="status" id="status" class="uniform-select">
        <option value="">---������---</option>
        <option value="NEW" <?php if($status=="NEW"){?> selected <?php }?>>---�͡����觫���---</option>
        <option value="PUR" <?php if($status=="PUR"){?> selected <?php }?>>---�ӡ����觫�������---</option>
        <option value="BUY" <?php if($status=="BUY"){?> selected <?php }?>>---���Ѻ�ͧ����---</option>
      </select></td>
      <td>�ѹ���ѹ�֡ :</td>
      <td><input type="text" name="StartDate" id="StartDate" style="width:100px;" value="">
        -
      <input type="text" name="EndDate" id="EndDate" style="width:100px;" value=""></td>
      <td>�Ӥ� : </td>
      <td><input type="text" name="key_search" id="key-search" style="width:180px;" value=""></td>
    </tr>
  </table>
  <div class="list-body">
    <div class="list-header">
      <ul>
	    <li></li>
	  		<li style="width:8%;">Req No</li>
	  		<li style="width:38%;text-align:left">��¡��</li>
	  		<li style="width:30%;text-align:left">����ѷ����� claim/����</li>
	  		<li style="width:5%;">ʶҹ�</li>
	  		<li style="width:10%;">�ѹ���</li>
	  		<li style="width:8%;">Action</li>
	  		<li>
	  			<!--  <span class="add-icon" onclick="javascript:addNewRequest();"></span>-->
	  		</li>
	  	</ul>
	</div>
	<div class="list-items">
	
	</div>
  </div>
  <div class="list-paging">
  		<div class="paging-infor"><span id="begin-item">1</span>-<span id="end-item">20</span> �ҡ������ <span id="total-item">45</span> ��¡��</div>
  		<div class="paging-action">
  			<ul class="nav-page">
  				<li><a href="javascript:void(0);" onclick="javascript:go2First();">&laquo;</a></li>
  				<li><a href="javascript:void(0);" onclick="javascript:go2Pre();">&lsaquo;</a></li>
  				<li class="paging-select">
  					<select id="select-page" onchange="javascript:changePage();">
  					</select>
  				</li>
  				<li><a href="javascript:void(0);" onclick="javascript:go2Next();">&rsaquo;</a></li>
  				<li><a href="javascript:void(0);" onclick="javascript:go2Last();">&raquo;</a></li>
  			</ul>
  			<input type="hidden" name="rg_id" id="rg_id" value="<?php print($repairgroup_id);?>">
  		</div>
  </div>
</body>
<script>
	$(document).ready(function (){
		var height = $(document).height();
		var main_body_height = height-140;
		$(".list-body").height(main_body_height);
		$(".list-items").height(main_body_height-40);

		$('#StartDate').Zebra_DatePicker({
			  //direction: true
		});
		$('#EndDate').Zebra_DatePicker({
			  //direction: true
		});

		changePage();
	});

	function go2First(){
		if(($('#select-page').val()*1)>1){
			var page = ($('#select-page').val()*1)-1;
			$('#select-page').val(1);
			changePage();
		}
	}
	function go2Pre(){
		var page = ($('#select-page').val()*1)-1;
		if(page>0){
			$('#select-page').val(page);
			changePage();
		}
	}
	function go2Next(){
		var page = $('#select-page').val()*1;
		var max = $('#select-page option').length*1;
		if(page<max){
			$('#select-page').val(page+1);
			changePage();
		}
	}
	function go2Last(){
		var page = $('#select-page').val()*1;
		var max = $('#select-page option').length*1;
		if(page<max){
			$('#select-page').val(max);
			changePage();
		}
	}
	function openInformation(id,no){
		var w = 700;
		var h = 500;
		if(isNaN(id))id='';
		parent.TINY.box.show({iframe:'../eqiupment-repair/templates/informations/information-purchase.php?id='+id+'&no='+no,boxid:'frameless',width:w,height:h,fixed:false,maskopacity:40});
	}
	function changePage(){
		$.ajax({ 
			url: "../../controllers/purchase_controller.php" ,
			type: "POST",
			datatype: "json",
			data: {"function":"list",
				   "page":$('#select-page').val(),
				   //"search[t2.FRepair_comp_id][value]":$('#FRepair_comp_id').val(),
				   //"search[t2.FRepair_comp_id][condition]":"=",
				   "search[t2.FSectionID][value]":$('#sec_id').val(),
				   "search[t2.FSectionID][condition]":"=",
				   "search[t2.FBranchID][value]":$('#FBranchID').val(),
				   "search[t2.FBranchID][condition]":"=",
				   "search[t1.FPurchaseStatus][value]":$('#status').val(),
				   "search[t1.FPurchaseStatus][condition]":"=",
				   "search[duplicate][0][key]":"t1.FDateRequest",
				   "search[duplicate][0][value1]":$('#StartDate').val(),
				   "search[duplicate][0][condition1]":">=",
				   "search[duplicate][0][value2]":$('#EndDate').val(),
				   "search[duplicate][0][condition2]":"<=",
				   "search[multi][value]":$('#key-search').val(),
				   "search[multi][fields][t2.FSerial]":"like",
				   "search[multi][fields][t2.FReqNo]":"like",
				   "keysearch":$('#key-search').val()
			}
		})
		.success(function(results) { 
			$(".list-items").empty();
			results = jQuery.parseJSON(results);
			var rows = results['rows'];
			var begin = results['begin'];
			var end = results['end'];
			var total = results['total'];
			var total_page = results['total_page'];
			var page = results['page'];
			if(rows!=null){
				for(var i=0;i<rows.length;i++){
					var cell = rows[i]['cell'];
					var ul = "<ul>";
						ul+= "<li></li>";
					  	ul+= "<li style=\"width:8%;\">"+cell['FReqNo']+"</li>";
					  	ul+= "<li style=\"width:38%;text-align:left;\">"+cell['FItems']+"</li>";
					  	ul+= "<li style=\"width:30%;text-align:left;\">"+cell['FSuplierName']+"</li>";
					  	ul+= "<li style=\"width:5%;\"><span class=\""+cell['StatusIcon']+"\"></span></li>";
					  	ul+= "<li style=\"width:10%;\">"+cell['FDateRequest']+"</li>";
					  	ul+= "<li style=\"width:6.33%;\"><span class=\"info-icon\" onclick=\"javascript:openInformation('"+cell['FPurchaseID']+"','"+cell['FReqNo']+"');\">Information</span></li>";
					  	ul+= "<li></li>";
					    ul+= "<ul>";
					$(".list-items").append(ul);
				}
			}
			$('#begin-item').empty().html(begin);
			$('#end-item').empty().html(end);
			$('#total-item').empty().html(total);
			renderPage(document.getElementById('select-page'),total_page);
			$('#select-page').val(page);
			
		});
	}/*End function renderPage()*/

	function cancelReq(no,id){
    	parent.TINY.box.show({iframe:'../eqiupment-repair/templates/general_user/forms/request-cancel.form.php?id='+id+'&no='+no,boxid:'frameless',width:570,height:240,fixed:false,maskopacity:40});
    }
   
	function openPrint(id){
	    	var width = screen.width-10;
			var height = screen.height-60;
	    	newwindow=window.open('../informations/print-request-preview.php?id='+id,
					  'requestInformationWindow-'+id,'width='+width+',height='+height+',left=0,top=0,screenX=0,screenY=0,status=no,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes,fullscreen=no');
	}/*End of function openInfor(id)*/
	
	function updateBranch(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
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
	function updateSection(selected){
			$.ajax({
					type: "POST",
					url: ("../../../main/controllers/utilities_controller.php"),
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
</script>
</html>