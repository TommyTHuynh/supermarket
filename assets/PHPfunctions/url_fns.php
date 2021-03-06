<?php
	require_once('db_fns.php');
	
	function searchAndDestroy($table, $columns, $values){
		$conn = db_connect();
		
		$cname_string_query = "select ";
				$col_names = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$table'");				
				for($count = 0; $rows = $col_names->fetch_row(); ++$count)
				{	
					if($count == 0)
						$cname_string_query .= lcfirst($rows[0]);
					else
						$cname_string_query .= ", ".lcfirst($rows[0]);
				}
		
		
		
		foreach ($values as &$data){
			if (is_null($data)){
				$data = "%";
			}
			else {
				$data .= "%";
			}
		}
		unset($data);
		if ($table == 'customer'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] like '$values[0]' AND $columns[1] like '$values[1]' AND $columns[2] like '$values[2]' AND $columns[3] like '$values[3]'");
			if ($result->num_rows == 1){
				$values = $result->fetch_array();
				$conn->query("delete from person where Name = '$values[0]' and Phone = '$values[1]'");
				return true;
			}
			else{
				throw new Exception('Please enter more specific search criteria');
			}
		}
		if ($table == 'employee'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] like '$values[0]' AND $columns[1] like '$values[1]' AND $columns[2] like '$values[2]' AND $columns[3] like '$values[3]' AND $columns[4] like '$values[4]' AND $columns[5] like '$values[5]'");
			if ($result->num_rows == 1){
				$values = $result->fetch_array();
				$conn->query("delete from person where Name = '$values[0]' and Phone = '$values[1]'");
				return true;
			}
			else{
				throw new Exception('Please enter more specific search criteria');
			}
		}
		if ($table == 'product'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] like '$values[0]' AND $columns[1] like '$values[1]' AND $columns[2] like '$values[2]' AND $columns[3] like '$values[3]' AND $columns[4] like '$values[4]'");
			if ($result->num_rows == 1){
				$values = $result->fetch_array();
				$conn->query("delete from product where BarCode = '$values[0]'");
				return true;
			}
			else{
				throw new Exception('Please enter more specific search criteria');
			}
		}
		if ($table == 'sale'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] like '$values[0]' AND $columns[1] like '$values[1]' AND $columns[2] like '$values[2]' AND $columns[3] like '$values[3]'");
			if ($result->num_rows == 1){
				$values = $result->fetch_array();
				$conn->query("delete from sale where SaleID = '$values[0]' and StoreNum = 'values[1]'");
				return true;
			}
			else{
				throw new Exception('Please enter more specific search criteria');
			}
		}
		if ($table == 'supplier'){
			$result = $conn->query("$cname_string_query from $table where $columns[0] like '$values[0]' and $columns[1] like '$values[1]' and $columns[2] like '$values[2]' and $columns[3] like '$values[3]'");
			if ($result->num_rows == 1){
				$values = $result->fetch_array();
				$conn->query("delete from supplier where CompanyID = '$values[0]'");
				return true;
			}
			else{
				throw new Exception('Please enter more specific search criteria');
			}
		}
		return $result;
	}
	
	function search($table, $columns, $values){
		$conn = db_connect();
		
		$cname_string_query = "select ";
				$col_names = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$table'");				
				for($count = 0; $rows = $col_names->fetch_row(); ++$count)
				{	
					if($count == 0)
						$cname_string_query .= lcfirst($rows[0]);
					else
						$cname_string_query .= ", ".lcfirst($rows[0]);
				}
		
		
		
		foreach ($values as &$data){
			if (is_null($data)){
				$data = "%";
			}
			else {
				$data .= "%";
			}
		}
		unset($data);
		if ($table == 'customer'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] LIKE '$values[0]' AND $columns[1] LIKE '$values[1]' AND $columns[2] LIKE '$values[2]' AND $columns[3] LIKE '$values[3]'");
		}
		if ($table == 'employee'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] LIKE '$values[0]' AND $columns[1] LIKE '$values[1]' AND $columns[2] LIKE '$values[2]' AND $columns[3] LIKE '$values[3]' AND $columns[4] LIKE '$values[4]' AND $columns[5] LIKE '$values[5]'");
		}
		if ($table == 'product'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] LIKE '$values[0]' AND $columns[1] LIKE '$values[1]' AND $columns[2] LIKE '$values[2]' AND $columns[3] LIKE '$values[3]' AND $columns[4] LIKE '$values[4]'");
		}
		if ($table == 'sale'){
			$result = $conn->query("$cname_string_query FROM $table WHERE $columns[0] LIKE '$values[0]' AND $columns[1] LIKE '$values[1]' AND $columns[2] LIKE '$values[2]' AND $columns[3] LIKE '$values[3]'");
		}
		if ($table == 'store'){
			$result = $conn->query("$cname_string_query FROM $table where $columns[0] like '$values[0]' and $columns[1] like '$values[1]' and $columns[2] like '$values[2]' and $columns[3] like '$values[3]'");
		}
		if ($table == 'suppliedby'){
			$result = $conn->query("$cname_string_query from $table where $columns[0] like '$values[0]' and $columns[1] like '$values[1]'");
		}
		if ($table == 'supplier'){
			$result = $conn->query("$cname_string_query from $table where $columns[0] like '$values[0]' and $columns[1] like '$values[1]' and $columns[2] like '$values[2]' and $columns[3] like '$values[3]'");
		}
		return $result;
	}
	function get_all_table($var)
	{	
		$conn = db_connect();
		$result = $conn->query("select *
								from $var");
		
		if(!$result)
			return false;
		//create an array of the URLs
		$url_array = array();
		for($count = 1; $row = $result->fetch_row(); ++$count)
		{
			$url_array[$count] = $row[0];
		}

		return $url_array;
		
	}
	
	function get_col($var){
		$conn = db_connect();
		$result = $conn->query("select count(*)
								from information_schema.columns
								where table_schema = 'market'
								and table_name = '$var'");
		 if (!$result)
			return 0; 
		$row = $result->fetch_row();
		$number = $row[0];
		return $number;
	}
	
	function get_names($var){
		$conn = db_connect();
		$result = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$var'");
		if(!$result)
			return false;
		
		//create an array of the URLs
		$url_array = array();
		for($count = 1; $row = $result->fetch_row(); ++$count)
		{
		$url_array[$count] = $row[0];
		}
		return $url_array;
		
	}
	
?>
