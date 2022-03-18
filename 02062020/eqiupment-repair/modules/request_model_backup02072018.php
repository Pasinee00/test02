<?php
 class Model_Request{
 	var $tbl_name = "";
 	var $key_id = "";
 	function Model_Request(){
 		$this->tbl_name = "mtrequest_db.tbl_request";
 		$this->key_id = "tbl_request.FRequestID";
 	}
 	
 	function insert_data($fields,$id){
 		$field_sql = "";
 		$where_sql = "";
 		if(empty($fields['FReqNo'])){
 			list($y,$m,$d) = split("-",$fields['FReqDate']);
 			$sql  = "SELECT COUNT(FRequestID)+1 AS newReqNo "
 					." FROM {$this->tbl_name} "
 					." WHERE YEAR(FReqDate)= '$y' ";
 			$rst = mysql_query($sql);
 			if($row=mysql_fetch_array($rst)){
	 			$FReqNo = $row['newReqNo'];
			}//end if($row=mysql_fetch_array($rst))
 			else{
 				$FReqNo = 1;
 			}//end else's if($row=mysql_fetch_array($rst))
 				
 			if($FReqNo == NULL){$FReqNo = 1;}
 			
 			if($FReqNo <= 9 ){$FReqNo = "MT-".(substr($y,2,2)+43)."-00".$FReqNo;}
 			else if($FReqNo <= 99){$FReqNo = "MT-".(substr($y,2,2)+43)."-0".$FReqNo;}
 			else{$FReqNo = "MT-".(substr($y,2,2)+43)."-".$FReqNo;}
 			
 			$fields['FReqNo'] = $FReqNo;
 		}
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".iconv("utf-8","tis-620",$val)."'":",".$key."='".iconv("utf-8","tis-620",$val)."'";
 		}
 		if($id)$where_sql = $this->key_id."=$id";
 		if(!$id)$sql = "INSERT INTO ".$this->tbl_name." SET $field_sql";
 		else $sql = "UPDATE ".$this->tbl_name." SET $field_sql WHERE $where_sql";
 		$insert_rst = mysql_query($sql);
 		$_array = array();
 		$_array['req_no'] = $fields['FReqNo'];
 		if(!$id)$_array['req_id'] = mysql_insert_id();
 		else $_array['req_id'] =  $id;
 		return $_array;
 		@mysql_free_result($insert_rst);
 	}/*End of function insert_data()*/
 	function open_request($fields){
 		$query = "UPDATE mtrequest_db.tbl_requestowner SET "
 					   ."FStatus='inprogress' "
 					   .",FStartDate='{$fields['FStartDate']}' "
 					   ."WHERE FRequestID= {$fields['FRequestID']} "
 					   ."AND FSupportID= {$fields['FSupportID']} "
 					   ."AND (FStatus='new' OR FStatus='waiting') ";
 		$rst = mysql_query($query);
 		
 		$query = "UPDATE mtrequest_db.tbl_request SET FEditDate='{$fields['FStartDate']}',FDueDate='{$fields['FDueDate']}' "
 				."WHERE FRequestID='{$fields['FRequestID']}' "
 		        ."AND (FEditDate IS NULL OR FEditDate='0000-00-00')";
 		$rst = mysql_query($query);
 	}/*End of function open_request($fields,$id)*/
 	function close_request($fields){
 		$query = "UPDATE mtrequest_db.tbl_requestowner SET "
				 		."FStatus='finished' "
				 		.",FFinishDate='{$fields['FFinishDate']}' "
				 		."WHERE FRequestID= {$fields['FRequestID']} "
				 		."AND FSupportID= {$fields['FSupportID']} "
				 		."AND FStatus='inprogress' ";
 		$rst = mysql_query($query);
 		
 		/*$num_rec = 0;
 		$query = "SELECT COUNT(FSupportID) AS num_rec FROM mtrequest_db.tbl_requestowner WHERE FRequestID='{$fields['FRequestID']}' AND FStatus!='finished'";
 		$rst = mysql_query($query);
 		while($record=mysql_fetch_object($rst)){
 			$num_rec = $record->num_rec;
 		}
 		if($num_rec==0){
 			$query = "UPDATE mtrequest_db.tbl_request SET FFinishDate='{$fields['FFinishDate']}',FStatus='finished' "
 					."WHERE FRequestID='{$fields['FRequestID']}' ";
 			$rst = mysql_query($query);
 		}*/
 	}/*End of function close_request()*/
 	
 	function close_request_all($fields){
 		  $query = "UPDATE mtrequest_db.tbl_requestowner SET "
 		  			     ."FStatus='finished',"
 		  			     ."FFinishDate='{$fields['FFinishDate']}' "
 		  			     ."WHERE FRequestID={$fields['FRequestID']}";
 		  $rst = mysql_query($query);
 		  
 		  $query = "UPDATE mtrequest_db.tbl_request SET FFinishDate='{$fields['FFinishDate']}',FStatus='finished' "
 		  ."WHERE FRequestID='{$fields['FRequestID']}' ";
 		  $rst = mysql_query($query);
 	}
 	
 	function update_cost($record){
 		$query = "REPLACE INTO mtrequest_db.tbl_requestcost (FReqCostID,FRequestID,FReqCostDetail,FReqCost,FReqCostType)"
 				."VALUE("
 				." '{$record['FReqCostID']}'"
 				.",'{$record['FRequestID']}'"
 				.",'".iconv("utf-8","tis-620",$record['FReqCostDetail'])."'"
 				.",'{$record['FReqCost']}'"
 				.",'{$record['FReqCostType']}'"
 				.")";
 		$rst= mysql_query($query);
 		
 	}/*End of function update_cost()*/
 	function update_estimate($record){
 		$query = "REPLACE INTO mtrequest_db.tbl_requestestimate (FReqEstimateID,FRequestID,FReqEstimate)"
 				."VALUE("
 				." '{$record['FReqEstimateID']}'"
 		        .",'{$record['FRequestID']}'"
 		        .",'".iconv("utf-8","tis-620",$record['FReqEstimate'])."'"
 				.")";
 	 	$rst= mysql_query($query);
 	 					
 	}/*End of function update_cost()*/
 	function update_support($record){
 		foreach($record as $key=>$val){
 			if(!empty($val)){
 				$fields .=(!empty($fields))?",".$key : $key;
 				$value .=(!empty($value))?",'".$val."'" : "'".$val."'";
 			}
 			
 		}
 		$query = "REPLACE INTO mtrequest_db.tbl_requestowner (".$fields.") "
 				."VALUE(".$value.")";
 		$rst = mysql_query($query); 
 	}
 	
 	function load_support($_rId){
 		$_arr = array();
 		$index = 0;
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_requestowner "
 				."LEFT JOIN pis_db.tbl_user ON(tbl_user.user_id = tbl_requestowner.FSupportID) "
 				."WHERE tbl_requestowner.FRequestID='{$_rId}' "
 				."ORDER BY mtrequest_db.tbl_requestowner.FStartDate DESC,mtrequest_db.tbl_requestowner.FFinishDate DESC ";
 		$results = mysql_query($query);
 		$columns = mysql_num_fields($results);
 		while($select_row=mysql_fetch_object($results)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($results,$i);
 				$_arr[$index][$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 			$index++;
 		}
 		return $_arr;
 	}/*End of function load_support($_rId)*/
 	
 	function load_cost($_rId){
 		$_arr = array();
 		$index = 0;
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_purchase "
 				."WHERE tbl_purchase.FRequestID='{$_rId}' "
 				."ORDER BY tbl_purchase.FPurchaseID LIMIT 0,7";
 		$results = mysql_query($query);
 	    $columns = mysql_num_fields($results);
 		while($select_row=mysql_fetch_object($results)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($results,$i);
 				$_arr[$index][$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 			$index++;
 		}
 		if($index<7){
 			for($i=$index;$i<7;$i++){
 				$_arr[$i]['FPurchaseID'] = $i;
 				$_arr[$i]['FRequestID'] = "";
 				$_arr[$i]['FComClaimID'] = "";
 				$_arr[$i]['FItems'] = "";
 				$_arr[$i]['FPrice'] = "";
				$_arr[$i]['FAmount'] = "";
				$_arr[$i]['FUnit'] = "";
 			}
 		}
 	return $_arr;
 	}/*End of function load_cost()*/
 	function load_estimate($_rId){
 		$_arr = array();
 		$index = 0;
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_requestestimate "
 				."WHERE tbl_requestestimate.FRequestID='{$_rId}' "
 		        ."ORDER BY tbl_requestestimate.FReqEstimateID";
 		$results = mysql_query($query);
 		$columns = mysql_num_fields($results);
 		while($select_row=mysql_fetch_object($results)){
 		 	for($i=0;$i<$columns;$i++){
 		 		$field_name = mysql_field_name($results,$i);
 		 		$_arr[$index][$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 		 	}
 		 	$index++;
 		}
 		if(empty($_arr)){
 			for($i=1;$i<=6;$i++){
 				$_arr[$i]['FReqEstimateID'] = $i;
 				$_arr[$i]['FReqEstimate'] = '';
 			}
 		}
 		return $_arr;
 	}/*End of function load_estimate()*/
 	
 	function add_attach($fields){
 		$field_sql = "";
 		$where_sql = "";
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".$val."'":",".$key."='".$val."'";
 		}
 		$sql = "INSERT INTO mtrequest_db.tbl_attachment SET $field_sql";
 		$insert_rst = mysql_query($sql);
 		$FAttachID = mysql_insert_id();
 		return $FAttachID;
 	}
 	function list_attach($rId){
 		$query = "SELECT * "
 				."FROM mtrequest_db.tbl_attachment WHERE FRequestID='{$rId}'";
 		$results = mysql_query($query);
 		$index = 0;
 		$_arr = array();
 		while($record = mysql_fetch_object($results)){
 			$_arr[$index]['FAttachID'] = $record->FAttachID;
 			$_arr[$index]['FRequestID'] = $record->FRequestID;
 			$_arr[$index]['FAttachName'] = iconv("tis-620","utf-8",$record->FAttachName);
 			$_arr[$index]['FAttachLink'] = $record->FAttachLink;
 			$_arr[$index]['FAttachType'] = $record->FAttachType;
 			$_arr[$index]['FAttachSize'] = $record->FAttachSize;
 			
 			$index++;
 		}
 		return $_arr;
 	}/*End of function list_attach($rId)*/
 	function delete_file($rId,$id,$url){
 		$delete_sql = "DELETE FROM mtrequest_db.tbl_attachment WHERE FAttachID='$id'";
 		$delete_rst = mysql_query($delete_sql);
 		
 		unlink('../../attachment/reqNo-'.$rId.'/'.$url);
 	}
 	
 	function get_data($id){
 		$dataArr = array();
 		$select_sql ="SELECT t1.* "
 				    .",t2.emp_code,t2.emp_name "
 				    .",t4.sec_nameThai "
 				    .",t5.post_name AS FPosition "
 				    .",t6.brn_name "
 				    .",t7.FRepairGroupItemName "
 				    .",t9.brn_name AS FBranchName "
 				    .",t10.FRepairGroupName "
					.",t11.comp_code "		
 				    .",CONCAT(t8.first_name,' ',t8.last_name) AS recive_name "
 				    .",CASE t1.FLevel "
 				    ."		WHEN '1' THEN '�š�з��Ѻ�١����µç' "
 				    ."		WHEN '2' THEN '�š�з��ѺἹ���ҧ �' "
 				    ."		WHEN '3' THEN '�š�з�����Ἱ�' "
 				    ."END AS FLevelName "
 				    .",CASE t1.FJobresult "
 				    ."		WHEN '1' THEN '�����ͧ' "
 				    ."   	WHEN '2' THEN '������Ѻ���Ҵ��Թ���' "
 				    ."END AS FJobresultLabel "
	 				."FROM  {$this->tbl_name} t1 "
	 				."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
	 			    ."LEFT JOIN pis_db.tbl_employeehist t3 ON(t3.emp_code = t2.emp_code AND (t3.emp_flg IS NULL OR t3.emp_flg = '')) "
	 			    ."LEFT JOIN pis_db.tbl_section t4 ON(t4.sec_id = t1.FSectionID) "
	 			    ."LEFT JOIN pis_db.tbl_position t5 ON(t5.post_id = t3.post_id) "
	 			    ."LEFT JOIN pis_db.tbl_branch t6 ON(t6.brn_id = t2.brn_id) "
	 			    ."LEFT JOIN general_db.tbl_repairgroupitem t7 ON(t7.FRepairGroupItemID = t1.FRepairGroupItemID) "
	 			    ."LEFT JOIN pis_db.tbl_user t8 ON(t8.user_id = t1.FReciverID) "
	 			    ."LEFT JOIN pis_db.tbl_branch t9 ON(t9.brn_id = t1.FBranchID) "
	 			    ."LEFT JOIN general_db.tbl_repairgroup t10 ON(t10.FRepairGroupID = t1.FRepairGroupID) "
					."LEFT JOIN pis_db.tbl_company t11 ON(t11.comp_id = t1.FRepair_comp_id) "
	 				."WHERE t1.FRequestID ='{$id}'";
 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
 				$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 		}
 		return $dataArr;
		//return  $select_sql;
 		@mysql_free_result($insert_rst);
 	}//end function get_data($id)
 	function get_req_support($_rId,$_sId){
 		$dataArr = array();
 		$select_sql = "SELECT * "
 				     ."FROM mtrequest_db.tbl_requestowner "
 				     ."WHERE FSupportID='{$_sId}' "
 				     ."AND FRequestID='{$_rId}' ";
 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
 				if($field_name=="FStartDate"){
 					$dataArr['FEditDate'] = iconv("tis-620","utf-8",$select_row->$field_name);
 				}
 				$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 		}
 		return $dataArr;
 		@mysql_free_result($insert_rst);
 	}
 	function remove_support($_rId,$_sId,$_order){
 		$query = "DELETE FROM mtrequest_db.tbl_requestowner WHERE FRequestID={$_rId} AND FSupportID={$_sId} AND FOrder='{$_order}'";
 		$result = mysql_query($query);
 	}/*end of function remove_support($_sId)*/
 	function delete_data($id){
		$delete_sql = "DELETE FROM ".$this->tbl_name." WHERE ".$this->key_id."='$id'";
		$delete_rst = mysql_query($delete_sql);
		
		$_list =$this->list_attach($id);
		if(!empty($_list)){
			foreach($_list as $key=>$val){
				$this->delete_file($id,$val['FAttachID'],$val['FAttachLink']);
			}
		}
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_attachment WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_requestcost WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_requestestimate WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
		
		$delete_sql = "DELETE FROM mtrequest_db.tbl_requestowner WHERE FRequestID='{$id}'";
		$delete_rst = mysql_query($delete_sql);
	}
	function cancel_request($_id,$_remark){
		$query = "UPDATE mtrequest_db.tbl_request SET FStatus='cancel',FCancelRemark='".iconv("utf-8","tis-620",$_remark)."' WHERE FRequestID='{$_id}'";
		$update_rst = mysql_query($query);
	}/*End of function cancel_request()*/
	
	function get_data_section_list($params){
		$page = $params['page']; // �Ѻ���˹�ҷ���ͧ��ù����ʴ�
		$rp = $params['rp']; // �Ѻ��Ҩӹǹ�ʴ���� 1 ˹��
		$sortname = $params['sortname']; //  �Ѻ������͹� field ����ͧ��èѴ���§
		$sortorder = $params['sortorder']; // �Ѻ����ٻẺ��èѴ���§������
		$search = $params['search'];
		$status_where_close = $params['status_where_close'];
		$status_where_close_dis = $params['status_where_close_dis'];
		$where = "";
		$sec_code = "";
		$select_sql = "SELECT sec_code FROM pis_db.tbl_section WHERE sec_id='{$search["FSectionID"]["value"]}'";
		$rst = mysql_query($select_sql);
		if($row=mysql_fetch_object($rst))$sec_code = $row->sec_code;
		foreach($search as $key=>$val){
			//if(!empty($val))$where .= " AND ".$key." = '{$val}'";
			if($key == 'duplicate'){
				foreach($val as $index=>$item){
					if(!empty($item['value1']))$where .= " AND {$item['key']} {$item['condition1']} '{$item['value1']}'";
					if(!empty($item['value2']))$where .= " AND {$item['key']} {$item['condition2']} '{$item['value2']}'";
				}
			}else if($key=="multi"){
					$_search = $val['value'];	
					$where .= " AND (";
					$_i = 0;
					foreach($val['fields'] as $index=>$item){
						if($_i>0) $where .= " OR ";
						if($item == "like")$where .= "LOCATE('".iconv("UTF-8", "TIS-620",$_search)."',{$index})>0";
						else $where .= "{$index} {$item} '{$_search}'";
						$_i++;
					}
					$where .= ")";
			}else{
				if(!empty($val['value'])){
					if($val['condition']=="like"){
						$where .= " AND  LOCATE('".iconv("UTF-8", "TIS-620",$val['value'])."',{$key})>0";
					}else{
						if($key=="FSectionID"){
							if(!empty($sec_code)){
								$where .= " AND ({$key} {$val['condition']} '{$val['value']}')";
							}else{
								$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
							}
						}else{
							if($val['value']=="finished"){
								$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
								$where .= " AND t1.FFinishDate >= '2016-09-01'";
									if($status_where_close=='1'){
										$where .= " AND t1.status_closejob != ''";
									} 
									if($status_where_close_dis=='1'){
										$where .= " AND t1.status_closejob = '2'";
									}
									
							}else{
								$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
							}
							
						}
					}
					
				}
			}
			 
		}
		$_arr = array("new"=>"new-status","inprogress"=>"process-status","waiting"=>"wait-approval-status","cancel"=>"lock_disabled-status","finished"=>"lock-status","noapprove"=>"noapprov-status");
			
		// ��ǹ��á�˹���� �ó�������觤����
		if (!$sortname) $sortname = $this->order_name; // �������觤���� ��˹��� field ���� arti_id (��鹡Ѻ���������Ф�)
		if (!$sortorder) $sortorder = 'desc'; // ���������ٻẺ��èѴ���§�������� ����˹��� �ҡ�ҡ��ҹ��� desc
		if (!$page) $page = 1; //  ����������˹�ҷ���ͧ����ʴ��� ����ʴ�˹���á �� 1
		if (!$rp) $rp = 18; // �ҡ����˹���¡�÷����ʴ���� 1 ˹���� ����˹��� 10
			
		// ��ǹ����Ѻ�Ѵ�ٻẺ�ͺࢵ������͹䢢����ŷ���ͧ����ʴ�
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		//$sort = "ORDER BY $sortname $sortorder";
		//if($query){
		//	$where = " AND LOCATE('".iconv("UTF-8", "TIS-620",$query)."', FSuplier)>0 ";
		//}
		//$where.= " AND FSectionID='{$sectionID}'";
		//$where.= " AND FBranchID='{$branchID}'";
			
		$select_sql = "SELECT * "
				     ."FROM  mtrequest_db.tbl_request t1 "
				     ."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
				     ."LEFT JOIN pis_db.tbl_section t3 ON(t3.sec_id = t1.FSectionID) "
				     ."LEFT JOIN mtrequest_db.tbl_requestowner t4 ON (t4.FRequestID = t1.FRequestID) " 
				     ."WHERE 1 $where GROUP BY t1.FRequestID ";
			
		// ��ǹ��Ѻ������բ����ŷ������������ ��㹵���� $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ��ǹ����Ѻ�֧�����������ҧ json ��� ����Ѻ�ʴ�
		$select_sql = "SELECT t1.*,t2.*,t3.*,t4.FSupportID,t4.FStatus AS owner_status,t5.FRepairGroupItemName "
				     ."FROM ".$this->tbl_name." t1 "
				     ."LEFT JOIN pis_db.tbl_employee t2 ON(t2.emp_id = t1.FReqID) "
				     ."LEFT JOIN pis_db.tbl_section t3 ON(t3.sec_id = t1.FSectionID) "
				     ."LEFT JOIN mtrequest_db.tbl_requestowner t4 ON (t4.FRequestID = t1.FRequestID) "
				     ."LEFT JOIN general_db.tbl_repairgroupitem t5 ON (t5.FRepairGroupItemID = t1.FRepairGroupItemID) " 
				     ."WHERE 1 $where  GROUP BY t1.FRequestID ORDER BY t1.FRequestID DESC $limit ";
		$select_rst = mysql_query($select_sql);
		
		$i=$start+1;
		$data['page'] = intval($page);
		$data['total_page'] = ceil($total/$rp);
		$data['total'] = intval($total);
		$data['begin'] = $i;
		while($val=mysql_fetch_array($select_rst)){
			$status = $val['FStatus'];
			if(!empty($val['owner_status']))$status = $val['owner_status'];
			$val['FDetail'] = str_replace(iconv("UTF-8", "TIS-620",$params['keysearch']), "<font color=\"#FF0000\">".iconv("UTF-8", "TIS-620",$params['keysearch'])."</font>", $val['FDetail']);
			if (strlen($val['FRepairGroupItemName'])> 30){
				$val['FRepairGroupItemName'] = substr($val['FRepairGroupItemName'],0,30)."...";
			}
			$cell = array(
					"order"=>$i
					,"FRequestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FReqID"=>iconv("TIS-620","UTF-8",$val['FReqID'])
					,"FPosition"=>iconv("TIS-620","UTF-8",$val['FPosition'])
					,"FSectionID"=>iconv("TIS-620","UTF-8",$val['FSectionID'])
					,"FFnc"=>iconv("TIS-620","UTF-8",$val['FFnc'])
					,"FBranchID"=>iconv("TIS-620","UTF-8",$val['FBranchID'])
					,"FTel"=>iconv("TIS-620","UTF-8",$val['FTel'])
					,"FReqDate"=>iconv("TIS-620","UTF-8",$val['FReqDate'])
					,"FReqTime"=>iconv("TIS-620","UTF-8",$val['FReqTime'])
					,"FSupervisorID"=>iconv("TIS-620","UTF-8",$val['FSupervisorID'])
					,"FManagerID"=>iconv("TIS-620","UTF-8",$val['FManagerID'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FInf_no"=>iconv("TIS-620","UTF-8",$val['FInf_no'])
					,"RequestName"=>iconv("TIS-620","UTF-8",$val['emp_name'])
					,"FSectionName"=>iconv("TIS-620","UTF-8",$val['sec_nameThai'])
					,"StatusIcon"=>$_arr[$val['FStatus']]
					,"FStatus"=>iconv("TIS-620","UTF-8",$val['FStatus'])
					,"OwnerStatus"=>iconv("TIS-620","UTF-8",$val['owner_status'])
					,"OwnerStatusIcon"=>$_arr[$val['owner_status']]
					,"FDetail"=>iconv("TIS-620","UTF-8",$val['FDetail'])
					,"FRepairGroupItemName" =>iconv("TIS-620", "UTF-8", $val['FRepairGroupItemName'])
					,"status_closejob" =>iconv("TIS-620", "UTF-8", $val['status_closejob'])
					,"closejob_date" =>iconv("TIS-620", "UTF-8", $val['closejob_date'])
					,"closejob_detail" =>iconv("TIS-620", "UTF-8", $val['closejob_detail'])
					,"closejob_emp_date" =>iconv("TIS-620", "UTF-8", $val['closejob_emp_date'])
					,"closejob_emp_detail" =>iconv("TIS-620", "UTF-8", $val['closejob_emp_detail'])
					,"approve_date" =>iconv("TIS-620", "UTF-8", $val['approve_date'])
			);
		
			$rows[] = array(
					"id" => $val['FReqestID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
		//return $select_sql;
	}/*End of function get_data_user_list()*/
	
	function get_data_list($params){
		$page = $params['page']; // �Ѻ���˹�ҷ���ͧ��ù����ʴ�
		$rp = $params['rp']; // �Ѻ��Ҩӹǹ�ʴ���� 1 ˹��
		$sortname = $params['sortname']; //  �Ѻ������͹� field ����ͧ��èѴ���§
		$sortorder = $params['sortorder']; // �Ѻ����ٻẺ��èѴ���§������
		$query = $params['search'];
			
		// ��ǹ��á�˹���� �ó�������觤����
		if (!$sortname) $sortname = $this->order_name; // �������觤���� ��˹��� field ���� arti_id (��鹡Ѻ���������Ф�)
		if (!$sortorder) $sortorder = 'desc'; // ���������ٻẺ��èѴ���§�������� ����˹��� �ҡ�ҡ��ҹ��� desc
		if (!$page) $page = 1; //  ����������˹�ҷ���ͧ����ʴ��� ����ʴ�˹���á �� 1
		if (!$rp) $rp = 18; // �ҡ����˹���¡�÷����ʴ���� 1 ˹���� ����˹��� 10
			
		// ��ǹ����Ѻ�Ѵ�ٻẺ�ͺࢵ������͹䢢����ŷ���ͧ����ʴ�
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		//$sort = "ORDER BY $sortname $sortorder";
		//if($query){
		//	$where = " AND LOCATE('".iconv("UTF-8", "TIS-620",$query)."', FSuplier)>0 ";
		//}
			
			
		$select_sql = "SELECT * " .
				"FROM ".$this->tbl_name." WHERE 1 $where";
			
		// ��ǹ��Ѻ������բ����ŷ������������ ��㹵���� $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ��ǹ����Ѻ�֧�����������ҧ json ��� ����Ѻ�ʴ�
		$select_sql = "SELECT * " .
				"FROM ".$this->tbl_name." " .
				"WHERE 1 $where $sort $limit";
		$select_rst = mysql_query($select_sql);

		$i=$start+1;
		$data['page'] = intval($page);
		$data['total_page'] = ceil($total/$rp);
		$data['total'] = intval($total);
		$data['begin'] = $i;
		while($val=mysql_fetch_array($select_rst)){
	
			$cell = array(
					"order"=>$i
					,"FReqestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FReqID"=>iconv("TIS-620","UTF-8",$val['FReqID'])
					,"FPosition"=>iconv("TIS-620","UTF-8",$val['FPosition'])
					,"FSectionID"=>iconv("TIS-620","UTF-8",$val['FSectionID'])
					,"FFnc"=>iconv("TIS-620","UTF-8",$val['FFnc'])
					,"FBranchID"=>iconv("TIS-620","UTF-8",$val['FBranchID'])
					,"FTel"=>iconv("TIS-620","UTF-8",$val['FTel'])
					,"FReqDate"=>iconv("TIS-620","UTF-8",$val['FReqDate'])
					,"FReqTime"=>iconv("TIS-620","UTF-8",$val['FReqTime'])
					,"FSupervisorID"=>iconv("TIS-620","UTF-8",$val['FSupervisorID'])
					,"FManagerID"=>iconv("TIS-620","UTF-8",$val['FManagerID'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FInf_no"=>iconv("TIS-620","UTF-8",$val['FInf_no'])
					,"status_closejob"=>iconv("TIS-620","UTF-8",$val['status_closejob'])
					,"closejob_date"=>iconv("TIS-620","UTF-8",$val['closejob_date'])
					,"closejob_detail"=>iconv("TIS-620","UTF-8",$val['closejob_detail'])
					,"closejob_emp_date"=>iconv("TIS-620","UTF-8",$val['closejob_emp_date'])
					,"closejob_emp_detail"=>iconv("TIS-620","UTF-8",$val['closejob_emp_detail'])
			);
	
			$rows[] = array(
					"id" => $val['FReqestID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
	}//end funciton get_data_list()
	function get_graph_data($param){
		$where = "";
		foreach($param as $key=>$val){
			$where .= " AND ".$key." = '{$val}'";
		}
		$select_sql = "SELECT COUNT(t1.FRequestID) AS num_rec "
				     ."FROM {$this->tbl_name} t1 "
				     ."LEFT JOIN mtrequest_db.tbl_requestowner t2 ON(t2.FRequestID = t1.FRequestID) "
					 ."WHERE 1 $where AND YEAR(t1.FReqDate) = YEAR(NOW())  GROUP BY t1.FRequestID";
		$select_rst = mysql_query($select_sql);
		$num_rec = 0;
		while($row = mysql_fetch_object($select_rst)){$num_rec += $row->num_rec;}
		return $num_rec;
	}/*End of function get_graph_data($param)*/
	function get_request_state($rId){
		$_arr = array();
		$query = "SELECT DATE_FORMAT(FReqDate,'%d-%b-%Y') AS openDate"
				.",DATE_FORMAT(FReciveDate,'%d-%b-%Y') AS startDate"
				.",DATEDIFF(FReciveDate,FReqDate) AS numStart"
				.",DATE_FORMAT(FEditDate,'%d-%b-%Y') AS workDate"
				.",DATEDIFF(FEditDate,FReciveDate) AS numWork"
				.",DATE_FORMAT(FDueDate,'%d-%b-%Y') AS estimateDate"
				.",FEstimate AS estTime"
				.",DATE_FORMAT(FFinishDate,'%d-%b-%Y') AS finishDate"
				.",DATEDIFF(FFinishDate,FEditDate) AS numFinish "
				."FROM mtrequest_db.tbl_request "
			    ."WHERE FRequestID='{$rId}'";
		$result = mysql_query($query);
		while($row=mysql_fetch_object($result)){
			$_arr[0]['date'] = $row->openDate;
			$_arr[0]['numDay'] = 0;
			$_arr[0]['type'] = '';
			$_arr[0]['label'] = iconv("TIS-620","UTF-8",'��㺤���ͧ');
			
			if(!empty($row->startDate)){
				$_arr[1]['date'] = $row->startDate;
				$_arr[1]['numDay'] = ($row->numStart>0)?$row->numStart:0.5;
				$_arr[1]['type'] = '';
				$_arr[1]['label'] = iconv("TIS-620","UTF-8",'�ѹ����Ѻ');
			}
			
			if(!empty($row->workDate)){
				$_arr[2]['date'] = $row->workDate;
				$_arr[2]['numDay'] = ($row->numWork>0)?$row->numWork:0.5;
				$_arr[2]['type'] = '';
				$_arr[2]['label'] = iconv("TIS-620","UTF-8",'�ѹ�����������');
			}
			
			if(!empty($row->estimateDate)){
				$_arr[3]['date'] = $row->estimateDate;
				$_arr[3]['numDay'] = ($row->estTime>0)?$row->estTime:0.5;
				$_arr[3]['type'] = '-estimate';
				$_arr[3]['label'] = iconv("TIS-620","UTF-8",'�ѹ����˹�����');
			}
			
			if(!empty($row->finishDate)){
				$_arr[4]['date'] = $row->finishDate;
				$_arr[4]['numDay'] = ($row->numFinish>0)?$row->numFinish:0.5;
				if($_arr[4]['numDay']>$_arr[3]['numDay'])$_arr[4]['type'] = '-over';
				else $_arr[4]['type']='';
				$_arr[4]['label'] = iconv("TIS-620","UTF-8",'�ѹ�������');
			}
		}
		return $_arr;
	}
	function get_request_notify($_suportId){
		$_arr['new'] = 0;
		$_arr['approve'] = 0;
		$_arr['snew']=0;
		$_arr['start']=0;
		$_arr['sapprove']=0;
		$_arr['purchase']=0;
		$_arr['spurchase']=0;
		$query = "SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_request "
				         ."WHERE FStatus='new' "
				         ."GROUP BY FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['new']+=$row->num_rec;}
		
		$query ="SELECT COUNT(FRequestID) AS num_rec "
						 ."FROM mtrequest_db.tbl_request "
						 ."WHERE FStatus='waiting' "
						."GROUP BY FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['approve']+=$row->num_rec;}
		
		$query ="SELECT COUNT(t1.FPurchaseID) AS num_rec "
				         ."FROM mtrequest_db.tbl_purchase t1 "
				        ."WHERE t1.FPurchaseStatus IN ('NEW','PUR') "
						."GROUP BY t1.FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['purchase']+=$row->num_rec;}
		
		$query ="SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_requestowner "
				         ."WHERE FStatus='new' "
				         ."AND FSupportID='{$_suportId}' "
						."GROUP BY FSupportID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['snew']+=$row->num_rec;}
		
		$query ="SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_requestowner "
				         ."WHERE FStatus='inprogress' "
						."AND FSupportID='{$_suportId}' "
						."GROUP BY FSupportID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['start']+=$row->num_rec;}

		$query ="SELECT COUNT(FRequestID) AS num_rec "
				         ."FROM mtrequest_db.tbl_requestowner "
				         ."WHERE FStatus='waiting' "
						 ."AND FSupportID='{$_suportId}' "
						 ."GROUP BY FSupportID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['sapprove']+=$row->num_rec;}
		
		$query ="SELECT COUNT(t1.FPurchaseID) AS num_rec "
				         ."FROM mtrequest_db.tbl_purchase t1 "
				         ."LEFT JOIN mtrequest_db.tbl_requestowner t2 ON(t2.FRequestID = t1.FRequestID) "
				         ."WHERE t1.FPurchaseStatus IN ('NEW','PUR') "
				         ."AND t2.FSupportID='{$_suportId}' "
						 ."GROUP BY t1.FRequestID";
		$rst = mysql_query($query);
		while($row=mysql_fetch_object($rst)){$_arr['spurchase']+=$row->num_rec;}
		
		return $_arr;
	}/*End of function get_request_notify()*/
	
	function receive_doc($_id){
		    
		    $_arr = $this->get_data($_id);
			$query = "UPDATE mtrequest_db.tbl_request SET FStatus='inprogress',FReceiveDoc='Y'  WHERE FRequestID='{$_id}'";
			$rst = mysql_query($query);
			
			if($_arr['FEditDate'] !="")$_status = 'inprogress';
		    else $_status = 'new';
			
			$query = "UPDATE mtrequest_db.tbl_requestowner SET FStatus='{$_status}' WHERE FRequestID='{$_id}'";
			$rst = mysql_query($query);
	
	}/*End of function recieve_doc($_id)*/
	
	function check_owner($rId,$sId){
			$_result = 0;
			$query = "SELECT * FROM mtrequest_db.tbl_requestowner WHERE FSupportID='{$sId}' AND FRequestID='{$rId}'";
			$rst = mysql_query($query);
			while($row=mysql_fetch_object($rst)){$_result=1;}
			return $_result;
	}/*End of fuction check_owner()*/
	 
 }/*End of class model_user*/
?>