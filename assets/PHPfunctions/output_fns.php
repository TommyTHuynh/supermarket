<?php
	//This function creates a default HTML style for a page that has returned an error. This includes the header, and possibly an error message that we want the user to see. In the case that there is an error message, we'll need the section after the header to be a 'form'
	function do_html_header_universal($section = 'default'){
?>
		<!DOCTYPE html>
		<html lang="en">
	
		<head>
			<meta charset="utf-8">
			<title>Supermarket</title>
			<link rel="stylesheet" href="assets/stylesheets/supermarket_design.css">
			<link href='https://fonts.googleapis.com/css?family=Lato:400,300,100' rel='stylesheet' type='text/css'>
		</head>
	
		<body>
			<header class="primary-header container group">
			
				<h1 class="logo">
					<a href="member.php">Supermarket</a>
				</h1>
				
				<div class="tagline">
					<h3>Shop with us</h3>
					<a class ="btn btn-alt" href="register_form.html">Sign Up</a>
				</div>
			</header>
<?php		
		
		if($section == 'form'){
?>
		<section class="row">
			<div class="column container form-style">
				<form>
<?php
		}
	}
	
	//In the case we want to print something to the user, in an exception-style, we send a string to this function an return the message in HTML
	function user_message($mess)
	{
?>
	<h2 class="exception-message"><?php echo $mess; ?></h2>
<?php
	}
	
	//Function that has two strings as parameters, the first being a link and the second being the name of the page the link is attached to
	function do_html_URL($url, $name)
	{
	  // output URL as link and br
?>
			<br /><a class = "exception-message" href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
	}
	
	//To finish the page, we may need to close the <form> tag and may also want to show the user the different links he/she can go to. However, there are two cases, when the user has logged in and we want to show the user the links (inner is set to true because we are 'in' the website) or we had an error before logging in and don't have the right to access those links (inner set to false).
	function do_html_footer_universal($inner = false, $section = false){
		
		if ($section == 'form')
		{
?>	
				</form>
			</div>
		</section>
		<?php 
			if($inner)
				display_user_menu(); 	
		}
		else if($inner){
			display_user_menu(); 
		}
	}
	
	function display_user_menu()
	{
	  // display the menu options on this page 
?>
<nav class="user-menu">
	<ul>
	<li><a href="person.php">Person</a></li><!-- 
	--><li><a href="product.php">Products</a></li><!-- 
	--><li><a href="sale.php">Sale</a></li><!-- 
	--><li><a href="store.php">Store</a></li><!-- 
	--><li><a href="suppliedby.php">SuppliedBy</a></li><!-- 
	--><li><a href="supplier.php">Supplier</a></li><!-- 
	--><li><a href="customer.php">Customer</a></li><!-- 
	--><li><a href="employee.php">Employee</a></li>
	</ul>
</nav>

<?php
	}	
		
		
	function display_table_new($var, $every_row)
	{
		$col_names = get_names($var);
		
		error_reporting(E_ALL & ~E_NOTICE);
		
		$editables =array("product", "supplier", "employee");
		if($_SESSION['priviledge'] == 'employee' && in_array($var, $editables))
			$edit = false;
		
?>
					<form class="book-table" action="update.php" method="post">
						<table id="supermarket_info">
							<?php 
							echo "<tr>";
							foreach($col_names as $col)
							{
								echo "<th scope='row'>
								$col
								</th>";
							}
							if($edit){
	?>
							<th scope="row">
								Update
							</th>
<?php						}
							echo "</tr>";
							?>
<?php
	
		
	if (is_array($col_names) && count($col_names)>0)
	{
		$conn = db_connect();
		
		$col_names = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$var'");
		try{
			if ($every_row->num_rows > 0) {
					$test_array = array();
					
				
				for($count = 0; $rows = $col_names->fetch_row(); ++$count)
				{	
					$final = lcfirst($rows[0]);
					$test_array[$count] = $final;
				}
				while($row = $every_row->fetch_row()){
					$count = 0;
					echo "<tr>";
					$test = array();
					echo '<form action="update.php" method="post">';
					foreach($test_array as $testing){
							echo "<td>";
							$table_entry = $row["$count"];
							$test[] = $table_entry;
							$count++;
							//EDITABLE PAGES
							if($edit){
								echo '<input type="hidden" name="old[]" value="'.$table_entry.'"></input>';
								echo '<input type="hidden" name="table_name" value="'.$var.'"></input>';
								//echo "<input type='text' name='new' value='$table_entry' onkeypress=\"this.style.width = ((this.value.length + 1) * 8) + \" px\";\" ></input>";

								//echo '<input id="txt" type="text" onkeypress="this.style.width = ((this.value.length + 1) * 8) + "px";">';
								
								echo "<input type='text' name='new[]' value='$table_entry' style='width: 100px;'></input>";
							}
							//END EDITABLE
							else
								echo $table_entry;
							
							echo "</td>";
					}
					if($edit){
						echo "<td>
						<button class='btn' type='submit' name='edit' value='test'>Edit
						</button>";
						echo "<button class='btn' type='submit' name='delete' value='delete'>Delete</button>
						</td>";
					}
					echo "</tr>";
					echo '</form>';
				}		
			}
		}
		catch(Exception $e)
		{
			echo "<b>Error thrown</b>";
		}
				// TEST FOR ALL TABLES
		
	}
	else
		echo "<tr><td>No Contents on record</td></tr>";
?>
						</table> 
					</form>
<?php
	}

		function display_table($var, $col_names, $operation = false)
		{
			error_reporting(E_ALL & ~E_NOTICE);
			// display the table

			// set global variable, so we can test later if this is on the page
			global $bm_table;
			$bm_table = true;
			?>
			<form class="book-table" name='bm_table' action='delete_bms.php' method='post'>
				<table id="supermarket_info">
					<?php
					echo "<tr>";
					foreach($col_names as $col)
					{
						echo "<th scope='row'>
								$col
								</th>";
					}
					if ( $operation ) {
						echo "<th></th>";
					}
					echo "</tr>";
					?>
					<?php


					if (is_array($col_names) && count($col_names)>0)
					{
						$conn = db_connect();
						$test2 = "select ";

						$result2 = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$var'");
						for($count = 0; $rows = $result2->fetch_row(); ++$count)
						{
							if($count == 0)
								$test2 .= lcfirst($rows[0]);
							else
								$test2 .= ", ".lcfirst($rows[0]);
						}
						$test2 .= " from $var";
						$result = $conn->query($test2);
						$result2 = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$var'");
						try{
							if ($result->num_rows > 0) {
								$test_array = array();

								for($count = 0; $rows = $result2->fetch_row(); ++$count)
								{
									$final = lcfirst($rows[0]);
									$test_array[$count] = $final;
								}

								while($row = $result->fetch_assoc()){
									echo "<tr>";
									foreach($test_array as $testing){
										echo "<td>";
										$test2 = $row["$testing"];
										echo $test2;
										echo "</td>";
									}
									if ( $operation ) {

										if ( $var == 'employee' ) {
											$dId = base64_encode($row['eName']);
											$pg = 'employee.php';
										} elseif ( $var == 'product' ) {
											$dId = base64_encode($row['barCode']);
											$pg = 'product.php';
										} elseif ( $var == 'supplier' ) {
											$dId = base64_encode($row['companyID']);
											$pg = 'supplier.php';
										}
										echo "<td><a href='{$pg}?i={$dId}'>edit</a> | <a href='delete.php?t={$var}&i={$dId}'>delete</a> </td>";
									}
									echo "</tr>";
								}
							}
						}
						catch(Exception $e)
						{
							echo "<b>Error thrown</b>";
						}
						// TEST FOR ALL TABLES

					}
					else
						echo "<tr><td>No Contents on record</td></tr>";
					?>
				</table>
			</form>
			<?php
		}

		function generateForm( $tableName, $columns, array $errors = array(), $oldData = array() ) {


			$dropDownOption = $dropDown = '';
			$dropDownOption2 = $dropDown2 = '';

			if ( $tableName == 'product' ) {

				$stores = getStore();
				foreach ( $stores as $store ) {
					$dropDownOption .= '<option '.((isset($oldData['StoreID']) && $oldData['StoreID'] == $store['StoreID']) ? ' checked ' : '').' value="'.$store['StoreID'].'">'.$store['StoreID'].' - ' . $store['Address_city'] . ',' . $store['Address_state']. ', ' . $store['Addess_zip'] .'</option>';
				}

				$index = array_search('SoldAt', $columns);
				if ( $index !== false ) {
					unset($columns[$index]);
					$dropDown = '<div>
				<label>SoldAt: </label>
				<select name="SoldAt" id="SoldAt">'.$dropDownOption.'</select>
</div>';
				}
			}

			if ( $tableName == 'employee' ) {

				$stores = getStore();
				foreach ( $stores as $store ) {
					$dropDownOption .= '<option '.((isset($oldData['StoreID']) && $oldData['StoreID'] == $store['StoreID']) ? ' checked ' : '').' value="'.$store['StoreID'].'">'.$store['StoreID'].' - ' . $store['Address_city'] . ',' . $store['Address_state']. ', ' . $store['Addess_zip'] .'</option>';
				}

				$index = array_search('WorksAt', $columns);
				//$index2 = array_search('EPhone', $columns);
				//$index3 = array_search('EName', $columns);
				//$index4 = array_search('StartDate', $columns);
				//unset($columns[$index2]);
				//unset($columns[$index4]);

				if ( $index !== false ) {
					unset($columns[$index]);
					$dropDown = '<div>
				<label>WorksAt: </label>
				<select name="WorksAt" id="WorksAt">'.$dropDownOption.'</select>
</div>';
				}

				/*if ( $index3 !== false ) {*/

				/*foreach ( getPerson() as $person ) {*/
				/*$dropDownOption2 .= '<option value="'.$person['Name'].'">'.$person['Name'].' - ' . $person['Phone'] .'</option>';*/
				/*}*/

				/*unset($columns[$index3]);*/
				/*$dropDown2 = '<div>*/
				/*<label>EName: </label>*/
				/*<select name="EName" id="EName">'.$dropDownOption2.'</select>*/
				/*</div>';*/
				/*}*/
			}

			$columnsHtml = '';
			foreach ( $columns as $type => $columnName ) {
				$columnsHtml .= '<div>';
				$columnsHtml .= '<label for="'.$columnName.'">'.ucwords(str_replace('_',' ', $columnName)).'</label>';
				$columnsHtml .= '<input type="'.($columnName == 'StartDate' ? 'date' : 'text').'" name="'.$columnName.'" value="'.(!empty($oldData[$columnName]) ? $oldData[$columnName] : '').'" id="'.$columnName.'"">';
				if ( isset($errors[$columnName]) ) {
					$columnsHtml .= '<span class="help-block">'.$errors[$columnName].'</span>';
				}
				$columnsHtml .= '</div>';
			}

			$o = 'insertion';
			$b = 'Insert New';
			if ( isset($_GET['i']) && !empty($oldData) ) {
				$o = 'update';
				$b = 'Update';
			}

			$html = <<<HTML
			<form action="" method="post">
			<input type="hidden" name="table" value="{$tableName}">
			<input type="hidden" name="operation" value="{$o}">
			{$dropDown2} {$columnsHtml} {$dropDown}
			<button type="submit">{$b}</button>
</form>
HTML;

			//if ( $tableName == 'employee' && ! $dropDownOption2 ) {
			//return '<form>All your persons are associated with employee. Please add a new <a href="/person.php"">Person</a> before create an employee.</form>';
			//}

			return $html;
		}
?>
