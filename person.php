<?php

// include function files for this application
require_once('assets/PHPfunctions/bookmark_fns.php');
session_start();

if(!(isset($_SESSION['valid_user'])) || !(isset($_SESSION['priviledge']))){
	do_html_header_universal('form');
	user_message('You are not logged in');
    do_html_url('index.html', 'Login');
    do_html_footer_universal(false, 'form');
	exit;
}	

//You have logged in as a customer, but you are trying to access an Employee page, so you are shown an error message
if($_SESSION['priviledge'] != "employee"){
	do_html_header_universal('form');
	user_message('You do not have clearance for this page');
    do_html_url('member.php', 'Go back');
    do_html_footer_universal(false, 'form');
	exit;
}
$var = 'person';

$conn = db_connect();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>SuperMarket</title>
		<link rel="stylesheet" href="assets/stylesheets/supermarket_design.css">
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300,100' rel='stylesheet' type='text/css'>
	</head>
	
	<body>
		<header class="primary-header container group">
		
			<h1 class="logo">
				<a href="member.php">SuperMarket</a>
			</h1>
			
			<div class="tagline">
				<h3>Become a Member</h3>
				<a class ="btn btn-alt" href="register_form.html">Sign Up</a>
			</div>
		</header>
	
		<section class="row">
			<div class="column container">
				<section class="home-block col-2-4">
	<?php
				echo '<img class="home-img" src="assets/images/John46.JPG" alt="temp-image">';
				$name = $_SESSION['valid_user'];
				echo "<h2 class='user-valid' >Logged in as $name </h2>";
	?>
					<nav class="home-menu">
						<ul>
						<?php
							if($_SESSION['priviledge'] == "customer"){
?>
							<li><a href="member.php">User Setting</a></li>
							<?php						
}
?>
							<li><a href="product.php">Products</a></li>
							<li><a href="store.php">Store</a></li>
<?php
							if($_SESSION['priviledge'] == "employee"){
?>
							<li><a href="supplier.php">Supplier</a></li>
							<li><a href="sale.php">Sale</a></li>
							<li><a href="suppliedby.php">SuppliedBy</a></li>
							<li><a href="customer.php">Customer</a></li>
							<li><a href="employee.php">Employee</a></li>
<?php
							}
?>
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</nav>
					
					<div class="member-info">
						<h3>SuperMarket Simple Steps</h3>
						<ul>
							<li>List of all contents</li>
							<li>Just select your Table and view the contents</li>
						</ul>
					
					</div>
				</section><!--
				--><section class="col-2-4">
<?php
				if ($col_names=get_names($var)){ 

				$result2 = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$var'");
						$url_array = get_all_table($var);
						$number = get_col($var);
						if($number != 0)
							display_table($var, $result2);
				}
?>	
				</section>
			</div>
		</section>
<?php		

// give menu of options
 do_html_footer_universal();
?>


