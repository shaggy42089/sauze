<?php
	session_start();//actualisation de la session
	error_reporting( E_ALL );//affichage des erreurs
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Inscription</title>
	<link type="text/css" rel="stylesheet" href="style.css?t=<? echo time(); ?>" media="all">
</head>
<body>	
<div class="l-site">
	<div class="l-nav">
		<nav class="nav">
			<ul>
				<li class="nav-primary" id="title"><a href="index.php" >Sauze</a></li>
			</ul>
		</nav>
	</div>
	<div class="menu">
        <div class="menu-hamburger"></div>
    </div>
	<div class="l-page">
	<section class="band band-a">
	<div class="band-container">
	<div class="container">
	<?php
		include 'db.php';//base de données
		if (! isset($_SESSION['name']) && isset($_POST['name']))
		{
			//on regarde si le mail et le nom sont déjà pris
			$query=mysqli_query($link,'SELECT * FROM utilisateur WHERE email="'.$_POST['email'].'" OR name="'.$_POST['name'].'"');
			if (mysqli_num_rows($query) == 0) {//if person doesn't already exists in db

				if ($_POST['password'] == $_POST['confirm']) {//if password and confirmed password are same

					if ($_POST['cat'] != "je suis un enfant de Papi") {//if the person if either a friend or a familly member
						$ref=0;
					} else {

						//storing own id in var $id
						$requete = mysqli_query($link, 'SELECT id_user FROM utilisateur WHERE name="'.$_POST['child'].'"');
						$ref=$requete['id'];
					}

						//inserting itself in db
						$requete = mysqli_query($link, 'INSERT INTO utilisateur(re_id, name, email, hash, isAccepted) VALUES('.$ref.'"'.$_POST['name'].'","'.$_POST['email'].'","'.password_hash($_POST['password'], PASSWORD_DEFAULT).'",0)');

					$requete = mysqli_query('SELECT id_user FROM utilisateur WHERE name='.$_POST['name']);
					$id=$requete['id_user'];

					//storing own info in a session
					$_SESSION['name']  = $_POST['name'];
					$_SESSION['email'] = $_POST['email'];
					$_SESSION['id']    = $id;

					//heading to homepage
					header('Location: index.php?action=creation');
				} else{
					echo '<strong class="warning">Mot de passe et confirmation ne sont pas identiques</strong>';
				}
			} else {
				echo '<strong class="warning">Ce nom d\'utilisateur ou cette adresse email sont déjà utilisés</strong>';
			}
		}
		include 'inscrip_form.html';//affichage du formulaire d'inscription
		$query = mysqli_query($link, 'SELECT * FROM utilisateur');
		echo '<input class="childHidden" type="radio" name="child1" value="'.$query['name'].'" checked>';
		while (mysqli_fetch_assoc($query)) {
			echo '<input type="radio" name="child" value="'.$query['name'].'">';
		}
		include 'form_end.html';//affichage du formulaire d'inscription

		mysqli_close($link);
	?>
	</div>
</body>
<script src="script.js"></script>
</html>