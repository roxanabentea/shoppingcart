
<?php 

session_start();
if(!isset($_SESSION["vect"])){
	$_SESSION["vect"] = array();
}
if(!isset($_SESSION["moneda"])){
	$_SESSION["moneda"] = "USD";
}
if(isset($_POST["Submit"]))
{	
		$_SESSION["moneda"] = $_POST['currency'] ;
}

$connect = mysqli_connect("localhost", "root", "", "tema");

if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["shopping_cart"]))
	{
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count = count($_SESSION["shopping_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_price'		=>	$_POST["hidden_price"],
				'item_quantity'		=>	$_POST["quantity"],
				'item_description'  =>	$_POST["hidden_description"]
	
			);
			$_SESSION["shopping_cart"][$count] = $item_array;
			array_push($_SESSION["vect"],$_GET["id"]);
			
		}
		else
		{
			echo '<script>alert("Item Already Added")</script>';
		}
	}
	else
	{   $hideids = array();
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_price'		=>	$_POST["hidden_price"],
			'item_quantity'		=>	$_POST["quantity"],
			'item_description'  =>	$_POST["hidden_description"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}

if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])
			{	
				$i = array_search($_GET["id"],$_SESSION["vect"]);
				unset($_SESSION["vect"][$i]);
				unset($_SESSION["shopping_cart"][$keys]);
				echo '<script>window.location="index.php"</script>';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
	<style>
	.relative {
		position: relative;
		width: 100%;
	}

	div.highlight {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0;
		/* the offenders */
		z-index: 10;
		opacity: 0.8;
	}

	div.tag {
		background-color: grey;
		position: absolute;
		position: absolute;
		top: -1.5em;
		left: 8em;
		width: 1em;
		height: 1.5em;
		overflow: hidden;
		z-index: 20;
		font-size: 0.9em;
		text-align: left;
		border: solid 0.1em #000;
		padding-left: 0.3em;
	}

	div.tag:hover {
		width: 40em;
		z-index: 100;
	}
	</style>
		<title>Shopping Cart</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="money.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			// Load exchange rates data via AJAX:
			var total = 0;
			var isLoaded = false;
			// Check money.js has finished loading:
					if ( typeof fx !== "undefined" && fx.rates ) {
						fx.rates = JSON.parse(localStorage.getItem("rates"));
						fx.base = localStorage.getItem("base");
						//document.getElementById("valoare").innerHTML = fx.convert(1000, {from: "USD", to: "EUR"}); // 12014.50549 // 647.71034
						isLoaded = true;
						//localStorage.setItem("rates",JSON.stringify(fx.rates));
						//localStorage.setItem("base",fx.base);
						
					} else {
						// If not, apply to fxSetup global:
						var fxSetup = {
							rates : JSON.parse(localStorage.getItem("rates")),
							base : localStorage.getItem("base")
						}
					}
			$.getJSON(
				// NB: using Open Exchange Rates here, but you can use any source!
				'http://data.fixer.io/api/latest?access_key=bb540433bf623720dfb8ba40dd366e0b',
				function(data) {
					localStorage.setItem("rates",JSON.stringify(data.rates));
					localStorage.setItem("base",data.base);
					fx.rates = data.rates ;
					fx.base = data.base;
				}
			);
		</script>
	</head>
	<body>
		<br />
		<div class="container">
			<br />
			<br />
			<br />
			<h3 align="center">Shoping Cart</a></h3><br />
			<br /><br />
		
			<?php
				$query = "SELECT * FROM products ORDER BY price DESC";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
					{
						if(!in_array($row["id"],$_SESSION["vect"]) OR count($_SESSION["vect"])==0)
						{
							
				?>
			<div class="col-md-3">
				<form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
					<div style="border:3px solid #5cb85c; background-color:whitesmoke; border-radius:5px; padding:16px;" align="center">
						
						<h4 class="text-info"><?php echo $row["name"]; ?></h4>

						<h4 class="text-danger" id="<?php echo $row["id"]; ?>">
						
							<script>
								document.getElementById("<?php echo $row["id"]; ?>").innerHTML=(fx.convert(<?php echo $row["price"];?>,{from: "USD", to: "<?php echo $_SESSION["moneda"];?>"})).toFixed(2);
							</script>
							<?php echo" "; echo $_SESSION["moneda"];?>
						</h4>

						<input type="text" name="quantity" value="1" class="form-control" />

						<input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />

						<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
						
						<input type="hidden" name="hidden_description" value="<?php echo $row["description"]; ?>" />
						
						<input type="submit" name="add_to_cart"style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />

					</div>
				</form>
			</div>
			<?php
						}
					}
				}
			?>
			<div style="clear:both"></div>
			<br />
			<h3> Your currency:
			<form method = "POST" action="index.php?action=changeCurrency" >
				<select name="currency" id="currency">
								<option value="USD">$</option>
								<option value="EUR">€</option>
								<option value="GBP">£</option>
				</select>
				<input name= "Submit" type="submit" style="margin-top:5px;" class="btn btn-success" value="Submit" >
				</br></br>
			</form>
			</h3>	
			
			<h3>Products in your shopping cart</h3>
			<div class="table-responsive" id = "<?php echo $values["item_id"];?>">
				<table class="table table-bordered">
					<tr>
						<th width="40%">Product</th>
						<th width="10%">Quantity</th>
						<th width="20%">Value</th>
						<th width="15%">Total</th>
						<th width="5%">Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>
					<tr>
						<td>
							<?php echo $values["item_name"]; ?>
							<?php
								if(!empty($values["item_description"]))
								{ ?>
								<div class="relative">
									<div class="highlight">
									  <div class="tag">
										<?php echo $values["item_description"]; ?>
									  </div>
									</div>
								</div>
							<?php
								}
							?>
						</td>
						<td><?php echo $values["item_quantity"]; ?></td>
						<td id = "<?php echo $values["item_id"]; ?>">
							<script>
							document.getElementById("<?php echo $values["item_id"]; ?>").innerHTML=(fx.convert(<?php echo $values["item_price"];?>,{from: "USD", to: "<?php echo $_SESSION["moneda"];?>"})).toFixed(2);
							</script>
							<?php echo" "; echo $_SESSION["moneda"];?>
						</td>
						<td id = "<?php echo $values["item_name"]; ?>">
							<script>
							document.getElementById("<?php echo $values["item_name"]; ?>").innerHTML=(fx.convert(<?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?>,{from: "USD", to: "<?php echo $_SESSION["moneda"];?>"})).toFixed(2);
							</script>
							<?php echo $_SESSION["moneda"];?>
						</td>

						<td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
					</tr>
					<script>
						total = total + Number(fx.convert(<?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?>,{from: "USD", to: "<?php echo $_SESSION["moneda"];?>"}).toFixed(2));
					</script>
					<?php
							
						}
					?>
					<tr>
						<td colspan="3" align="right">Total</td>
						<td align="right">
						<script>
							document.write(total);
						</script>
						<?php echo" "; echo $_SESSION["moneda"];?></td>
						<td></td>
						
					</tr>
					<?php
					}
					?>
					
				</table>
			</div>
		</div>
	</div>
	<br />
	</body>
</html>