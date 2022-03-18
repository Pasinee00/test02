<?php
 class Model_Claim{
 	var $tbl_name = "";
 	var $key_id = "";
 	function Model_Claim(){
 		$this->tbl_name = "mtrequest_db.tbl_claim";
 		$this->key_id = "tbl_claim.FClaimID";
 	}
 	
 	function insert_data($fields,$id){
 		$field_sql = "";
 		$where_sql = "";
 		
 		foreach($fields as $key=>$val){
 			$field_sql .=(!$field_sql)?$key."='".iconv("utf-8","tis-620",$val)."'":",".$key."='".iconv("utf-8","tis-620",$val)."'";
 		}
 		if($id)$where_sql = $this->key_id."=$id";
 		if(!$id)$sql = "INSERT INTO ".$this->tbl_name." SET $field_sql";
 		else $sql = "UPDATE ".$this->tbl_name." SET $field_sql WHERE $where_sql";
 		$insert_rst = mysql_query($sql);
 		@mysql_free_result($insert_rst);
 	}/*End of function insert_data()*/

 	function get_data($id){
 		$dataArr = array();
 		$select_sql ="SELECT t1.*,t2.FSuplierName "
	 				."FROM  {$this->tbl_name} AS t1 "
	 				."LEFT JOIN general_db.tbl_suplier t2 ON(t2.FSuplierID = t1.FComClaimID) "
	 				."WHERE t1.FClaimID ='{$id}'";
 		$select_rst = mysql_query($select_sql);
 		$columns = mysql_num_fields($select_rst);
 		while($select_row=mysql_fetch_object($select_rst)){
 			for($i=0;$i<$columns;$i++){
 				$field_name = mysql_field_name($select_rst,$i);
 				$dataArr[$field_name] = iconv("tis-620","utf-8",$select_row->$field_name);
 			}
 		}
 		return $dataArr;
 		@mysql_free_result($insert_rst);
 	}//end function get_data($id)

 	function delete_data($id){
		$delete_sql = "DELETE FROM ".$this->tbl_name." WHERE ".$this->key_id."='$id'";
		$delete_rst = mysql_query($delete_sql);
	}
	function get_data_json($id){
		$select_sql = "SELECT * " .
				"FROM ".$this->tbl_name." t1 " .
				"INNER JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) ".
				"LEFT JOIN general_db.tbl_suplier t3 ON(t3.FSuplierID = t1.FComClaimID) ".
				"WHERE t1.FRequestID='{$id}'";
		$select_rst = mysql_query($select_sql);
		$_status = array("NEW"=>"package-status","SEND"=>"package-accept-status","BACK"=>"package-download-status");
		while($val=mysql_fetch_array($select_rst)){
		
			$cell = array(
					"order"=>$i
					,"FClaimID"=>iconv("TIS-620","UTF-8",$val['FClaimID'])
					,"FRequestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FItems"=>iconv("TIS-620","UTF-8",$val['FItems'])
					,"FAssetNo"=>iconv("TIS-620","UTF-8",$val['FAssetNo'])
					,"FComClaimID"=>iconv("TIS-620","UTF-8",$val['FComClaimID'])
					,"FSendDate"=>iconv("TIS-620","UTF-8",$val['FSendDate'])
					,"FReciveDate"=>iconv("TIS-620","UTF-8",$val['FReciveDate'])
					,"FType"=>iconv("TIS-620","UTF-8",$_types[$val['FType']])
					,"FDateRequest"=>iconv("TIS-620","UTF-8",$val['FDateRequest'])
					,"FRemark"=>iconv("TIS-620","UTF-8",$val['FRemark'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FSuplierName"=>iconv("TIS-620","UTF-8",$val['FSuplierName'])
					,"StatusIcon"=>iconv("TIS-620","UTF-8",$_status[$val['FClaimStatus']])
			);
		
			$rows[] = array(
					"id" => $val['FClaimID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['rows'] = $rows;
		return $data;
	}/*End of function get_data_json()*/
	function get_data_list($params){
		$page = $params['page']; // �Ѻ���˹�ҷ���ͧ��ù����ʴ�
		$rp = $params['rp']; // �Ѻ��Ҩӹǹ�ʴ���� 1 ˹��
		$sortname = $params['sortname']; //  �Ѻ������͹� field ����ͧ��èѴ���§
		$sortorder = $params['sortorder']; // �Ѻ����ٻẺ��èѴ���§������
		$search = $params['search'];
		$where = "";
		if(!empty($params['FRequestID']))$wher = " AND FRequestID='{$params['FRequestID']}' ";
		foreach($search as $key=>$val){
			//if(!empty($val))$where .= " AND ".$key." = '{$val}'";
			if($key == 'duplicate'){
				foreach($val as $index=>$item){
					if(!empty($item['value1']))$where .= " AND {$item['key']} {$item['condition1']} '{$item['value1']}'";
					if(!empty($item['value2']))$where .= " AND {$item['key']} {$item['condition2']} '{$item['value2']}'";
				}
			}else{
				if(!empty($val['value']))$where .= " AND {$key} {$val['condition']} '{$val['value']}'";
			}
			 
		}
		$_types = array("SR"=>"�觫���","SC"=>"�� claim");
		$_status = array("NEW"=>"package-status","SEND"=>"package-accept-status","BACK"=>"package-download-status");
			
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
					  "FROM ".$this->tbl_name." t1 " .
				      "INNER JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) ".
				      "LEFT JOIN general_db.tbl_suplier t3 ON(t3.FSuplierID = t1.FComClaimID) ".
				      "WHERE 1 $where";
			
		// ��ǹ��Ѻ������բ����ŷ������������ ��㹵���� $total
		$qr = mysql_query($select_sql);
		$total = mysql_num_rows($qr);
			
		// ��ǹ����Ѻ�֧�����������ҧ json ��� ����Ѻ�ʴ�
		$select_sql = "SELECT * " .
				      "FROM ".$this->tbl_name." t1 " .
				      "INNER JOIN mtrequest_db.tbl_request t2 ON(t2.FRequestID = t1.FRequestID) ".
				      "LEFT JOIN general_db.tbl_suplier t3 ON(t3.FSuplierID = t1.FComClaimID) ".
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
					,"FClaimID"=>iconv("TIS-620","UTF-8",$val['FClaimID'])
					,"FRequestID"=>iconv("TIS-620","UTF-8",$val['FRequestID'])
					,"FItems"=>iconv("TIS-620","UTF-8",$val['FItems'])
					,"FAssetNo"=>iconv("TIS-620","UTF-8",$val['FAssetNo'])
					,"FComClaimID"=>iconv("TIS-620","UTF-8",$val['FComClaimID'])
					,"FSendDate"=>iconv("TIS-620","UTF-8",$val['FSendDate'])
					,"FReciveDate"=>iconv("TIS-620","UTF-8",$val['FReciveDate'])
					,"FType"=>iconv("TIS-620","UTF-8",$_types[$val['FType']])
					,"FDateRequest"=>iconv("TIS-620","UTF-8",$val['FDateRequest'])
					,"FRemark"=>iconv("TIS-620","UTF-8",$val['FRemark'])
					,"FReqNo"=>iconv("TIS-620","UTF-8",$val['FReqNo'])
					,"FSuplierName"=>iconv("TIS-620","UTF-8",$val['FSuplierName'])
					,"StatusIcon"=>iconv("TIS-620","UTF-8",$_status[$val['FClaimStatus']])
			);
	
			$rows[] = array(
					"id" => $val['FClaimID'],
					"cell" => $cell
			);
			$i++;
		}
		$data['end'] = $i-1;
		$data['rows'] = $rows;
		return $data;
	}//end funciton get_data_list()
	function get_claim_state($cId){
		$query = "SELECT DATE_FORMAT(FDateRequest,'%d-%b-%Y') AS openDate"
				.",DATE_FORMAT(FSendDate,'%d-%b-%Y') AS FSendtDate"
				.",DATEDIFF(FSendDate,FDateRequest) AS numStart"
				.",DATE_FORMAT(FReciveDate,'%d-%b-%Y') AS FReciveDate"
				.",DATEDIFF(FReciveDate,FSendDate) AS numWork "
				."FROM mtrequest_db.tbl_claim "
				."WHERE FClaimID='{$cId}'";
				$result = mysql_query($query);
				while($row=mysql_fetch_object($result)){
					$_arr[0]['date'] = $row->openDate;
					$_arr[0]['numDay'] = 0;
					$_arr[0]['type'] = '';
					$_arr[0]['label'] = iconv("TIS-620","UTF-8",'�ѹ�֡');
						
					if(!empty($row->FSendtDate)){
						$_arr[1]['date'] = $row->FSendtDate;
						$_arr[1]['numDay'] = ($row->numStart>0)?$row->numStart:0.5;
						$_arr[1]['type'] = '';
						$_arr[1]['label'] = iconv("TIS-620","UTF-8",'�ѹ�����');
					}
						
					if(!empty($row->FReciveDate)){
						$_arr[2]['date'] = $row->FReciveDate;
						$_arr[2]['numDay'] = ($row->numWork>0)?$row->numWork:0.5;
						$_arr[2]['type'] = '';
						$_arr[2]['label'] = iconv("TIS-620","UTF-8",'�ѹ����Ѻ�׹');
					}
				}
				return $_arr;
	}/*End function get_claim_state*/
 }/*End of class Model_Claim*/
?>