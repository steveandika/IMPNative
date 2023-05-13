<?php 
	class DatabaseClass  
	{  
		private $host = "192.168.1.5, 1435"; // your host name  
		private $username = "user_imp"; // your user name  
		private $password = "papaBr4v078"; // your password  
		private $db = "CSSCY"; // your database name  
		
		public function __construct()  
		{  
			mssql_connect($this -> host, $this -> username, $this -> password) or die(mssql_get_last_message);  
			mssql_select_db('CSSCY') or die(mssql_get_last_message);  
		}
		
		// this method used to execute mysql query  
		protected function query_executed($sql)  
		{  
			$c = mssql_query($sql);  
			return $c;  
		}  
		
		public function get_rows($fields, $id = NULL, $tablename = NULL)  
		{  
			$cn = !empty($id) ? " WHERE $id " : " ";  
			$fields = !empty($fields) ? $fields : " * ";  
			$sql = "SELECT $fields FROM $tablename $cn";  
			$results = $this -> query_executed($sql);  
			$rows = $this -> get_fetch_data($results);  
			return $rows;  
		}  
		
		public function get_listMntrEoRFin($queryString)
		{
			$sql = "SELECT * FROM $queryString";
			$results = $this -> query_executed($sql);
			$rows = $this -> get_fetch_data($results);
			return $rows;
		}		
    
		protected function get_fetch_data($r)  
		{  
			$array = array();  
			while ($rows = mssql_fetch_assoc($r))  
			{  
				$array[] = $rows;  
			}  
			return $array;  
		}  
	}  
?>