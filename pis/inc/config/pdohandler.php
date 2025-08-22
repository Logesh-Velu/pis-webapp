<?php
//ini_set('display_errors', 0);
class dbhandler
{
    private $conn;
	public function __construct()
	{	
		try
		{
			require_once dirname(__FILE__) . '/pdoconfig.php';		
			$this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USERNAME ,DB_PASSWORD);		
			$this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->conn->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}
		catch(PDOException  $e)
		{
			echo "Error Connecting Host :" .$e->getMessage();
		}
		catch(Exception  $e)
		{
			echo $e->getMessage();
		}
	}
	public function GetLastRecord($tbl,$sf,$wf,$val,$order)
	{
		$SQL = "SELECT ".$sf." FROM ".$tbl." WHERE ".$wf." = '".$val."' ORDER BY ".$order." LIMIT 1";
		$res = $this->conn->query($SQL);
		$sf_value = $res->fetchColumn();
		return $sf_value;
	}
	public function GetSingleReconrd($tbl,$sf,$wf,$val)
	{	
	   try
	   {
	      $SQL = "SELECT ".$sf." FROM ".$tbl." WHERE ".$wf." = '".$val."' ";
	      $res = $this->conn->query($SQL);
		// echo $SQL;
		  $sf_value ='';
		    if ($res->rowCount() > 0)
			$sf_value = $res->fetchColumn();
	    	return $sf_value;	
	   }
	    catch(Exception  $e)
		{
			echo $e->getMessage();
		}
	}
	public function GetMultiReconrd($tbl,$sf,$wf,$val){
	 try
	   {	
		$str = "";
		$SQL = "SELECT DISTINCT ".$sf." AS sf FROM ".$tbl." WHERE ".$wf." = '".$val."' ";
		$res = $this->conn->query($SQL);
		//echo mysql_error();
		if ($res->rowCount() > 0) { 
			while ($obj5 = $res->fetch(PDO::FETCH_OBJ)) {
				if($str != ""){
					$str = $str.', '.$obj5->sf;
				}else{
					$str = $obj5->sf;
				}
			}
		}
		return $str;
		}
	    catch(Exception  $e)
		{
			echo $e->getMessage();
		}
	}
	public function GetSum($tbl,$sf,$wf,$val){
	 try
	   {
		$total = 0;
		$SQL = "SELECT SUM(".$sf.") FROM ".$tbl." WHERE ".$wf." = ".$val." ";
		$result1 = $this->conn->query($SQL);
		$result = $result1->fetch(PDO::FETCH_NUM);
		//return $result;
		if ($result[0]!=""){
			$total = $result[0];
		}
		return $total;
		}
	    catch(Exception  $e)
		{
			echo $e->getMessage();
		}
	}
	public function GetCount($tbl,$wf,$val)
	{
		$nos = 0;
		$SQL = "SELECT COUNT(*) as records_count FROM ".$tbl." WHERE ".$wf." = ".$val." ";		
		$res = $this->conn->query($SQL);			
		$records_count = $res->fetchColumn();
		return $records_count;		
	}
	public function GetCountDistinct($tbl,$wf,$val,$fld){
		$nos = 0;
		$SQL = "SELECT COUNT(DISTINCT ".$fld.") as records_count  FROM ".$tbl." WHERE ".$wf." = ".$val." ";
		$res = $this->conn->query($SQL);
		$records_count = $res->fetchColumn();
		return $records_count;		
	}
	public function GetMaxValue($tbl,$sf,$wf,$val){
		$SQL = "SELECT MAX(".$sf.") AS val FROM ".$tbl." WHERE ".$wf." = '".$val."'";
		$result = $this->conn->query($SQL);
		if ($result->rowCount() > 0)
		$obj5 = $result->fetch();
		return $obj5->val;
	}
	public function fnFillComboFromTable_Where( $field1, $field2, $table, $field3, $where )
	{
		$strOption = ""; $result = ""; $SQL = "";
		$SQL = "SELECT $field1 AS a,$field2 AS b FROM $table $where ORDER BY $field3";
		$result = $this->conn->query($SQL);
		if ($result->rowCount() > 0)
		{
			while ($obj = $result->fetch())
			{
				$strOption .="<option value=\"". $obj->a ."\">". $obj->b ."</option>";		
			}
			return $strOption;
		}
	}
	public function fnFillComboFromTable( $field1, $field2, $table, $field3)
	{
		$strOption = ""; $result = ""; $SQL = "";
		$SQL = "SELECT $field1,$field2 FROM $table ORDER BY $field3";
		$result = $this->conn->query($SQL);
		if ($result->rowCount() > 0)
		{
			while ($obj = $result->fetch())
			{
				$strOption .="<option value=\"". $obj->$field1 ."\">". $obj->$field2 ."</option>";
			}
			return $strOption;
		}
	}
	
	public function getColumns($tableName) {
		try{
			$q = $this->conn->prepare("DESCRIBE ".DB_NAME.".$tableName");
			$q->execute();
			return $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	 
	public function CIS_InsertRecord($table, $CISPOST, $action) {
		try{
			$real_columns = $this->getColumns($table);
			$fields = array_intersect_key($CISPOST, array_flip($real_columns));
			if (!$fields) {
				// no POST fields match the real columns
				return false;
			}
			$columns = array_map(function($col) { return "`".$col."`"; }, array_keys($fields));
			$holders = array_map(function($col) { return ":".$col; }, array_keys($fields));
			$values = $fields;
			$sql = "INSERT INTO $table (".join(",",$columns).")VALUES(".join(",",$holders).")";  
			if($action == 1){
				$insert_id = 0;
				if (($stmt = $this->conn->prepare($sql)) === false) {
					die(print_r('prepare-'.$this->conn->errorInfo(), true));
				}			
				if (($retval = $stmt->execute($values)) === false) {
					die (print_r('execute-'.$stmt->errorInfo(), true));
				}else{
					$insert_id = $this->conn->lastInsertId();
				}
				return $retval.'~'.$insert_id.'~Success';
			}else{
				return $sql;
			}
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	public function CIS_UpdateRecord($table, $Where, $CISPOST, $action) {
		try{
			$real_columns = $this->getColumns($table);
			$fields = array_intersect_key($CISPOST, array_flip($real_columns));
			if (!$fields) {
				// no POST fields match the real columns
				return false;
			}
			$columns = array_map(function($col) { return $col."=:".$col; }, array_keys($fields));
			//$holders = array_map(function($col) { return ":".$col; }, array_keys($fields));
			$values = $fields;
			$sql = "UPDATE $table SET ".join(",", $columns)." WHERE ".$Where;  
			if($action == 1){				
				if (($stmt = $this->conn->prepare($sql)) === false) {
					die(print_r('prepare-'.$this->conn->errorInfo(), true));
				}			
				if (($retval = $stmt->execute($values)) === false) {
					die (print_r('execute-'.$stmt->errorInfo(), true));
				}
				return $retval.'~Success';
			}else{
				return $sql;
			}
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	
	
}
?>