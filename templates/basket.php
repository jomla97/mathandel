<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/startpage.css">
		<link rel="stylesheet" type="text/css" href="stylesheets/basket.css">
	</head>
	<body>
		<main>
      <?php
        $pdo = pdo();
        $username = $_SESSION['username'];
        foreach($pdo->query("SELECT * FROM baskets INNER JOIN products ON baskets.item_id=products.id WHERE username LIKE '$username' GROUP BY(name)") as  $row){

					//get the quantity
					$name = $row['name'];
					$statement = $pdo->prepare("SELECT * FROM baskets INNER JOIN products ON baskets.item_id=products.id WHERE username LIKE '$username' AND name LIKE '$name'");
					$statement->execute();
					$rowCount = $statement->rowCount();

          echo '
            <div class="item">
							<img src="' . $row['image'] . '" alt="product image">
							<h1>' . $row['name'] . '</h1>
							<form action="index.php?page=basket" method="post">
								<input type="number" value="' . $rowCount . '" required>
								<a href="index.php?page=basket&delete=' . $row['item_id'] . '"><button class="delete-button">X</button></a>
							</form>
						</div>
          ';
        }
      ?>
		</main>
	</body>
</html>
