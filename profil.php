
<?php
	error_reporting( E_ALL );
	session_start();
	if (! isset($_SESSION['name'])) {
		header('Location: connection.php');
	}
	$allowed_days=21;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sauze</title>
	<link type="text/css" rel="stylesheet" href="style.css?t=<? echo time(); ?>" media="all">
	<style>
		.band-inner {
			flex-direction: row;
		}
	</style>
</head>
<body class="fullpage logged-out sidebar-false">
	<header id="main-header" class="main-header">
	</header>
	<div class="l-site">
		<div class="l-nav">
			<nav class="nav">
				<ul>
					<li class="nav-primary" id="title"><a href="index.php" >Sauze</a></li>
					<li class="nav-primary"><a href="#"><?php echo $_SESSION['name']; ?></a></li>
					<li class="nav-secondary last"><a href="deconnexion.php">Deconnexion</a></li>
				</ul>
			</nav>
		</div>
		<div class="menu">
      		<div class="menu-hamburger"></div>
    	</div>
		<div class="l-page">
			<section class="band band-a">
				<div class="band-container">
					<div class="band-inner">
						<div class="l-div">
							<ul>
								<?php include 'db.php' ?>
								<li>Nom : <?php echo $_SESSION['name'] ?></li>
								<li>Email : <?php echo $_SESSION['email'] ?></li>
								<li>Nombre de réservation cette année : 
									<?php 
										$requete = mysqli_query($link,'SELECT * FROM reservation where annee='.(date("Y")).' AND id_user='.$_SESSION['id']);
										if ($requete) {
											echo mysqli_num_rows($requete);
										} else echo 'erreur dans la base de donnée';?></li>
								<li>Nombre total de réservations : 
									<?php 
										$requete = mysqli_query($link,'SELECT * FROM reservation where id_user='.$_SESSION['id']);
										if ($requete) {
											echo mysqli_num_rows($requete);
										} else echo 'erreur dans la base de donnée';?></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</body>
<script src="script.js"></script>>
</html>