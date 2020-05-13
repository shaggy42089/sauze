<?php
	session_start();//actualisation de la session
	error_reporting( E_ALL );//affichage des erreurs
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Connection</title>
	<link type="text/css" rel="stylesheet" href="style.css?t=<? echo time(); ?>" media="all">
</head>
<body class="fullpage logged-out sidebar-false">
<header id="main-header" class="main-header"></header>	
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
		if (isset($_POST['password']) && isset($_POST['email']) && ! isset($_SESSION['name']))
		{//si email et mdp sont remplis
			//on regarde si un utilisateur utilisant cet email existe
			$query=mysqli_query($link,'SELECT * FROM utilisateur WHERE email="'.$_POST['email'].'"');
			if (mysqli_num_rows($query)==0) {//sinon on reagarde si un utilisateur avec ce nom existe
				$query=mysqli_query($link,'SELECT * FROM utilisateur WHERE name="'.$_POST['email'].'"');
			}
			if (mysqli_num_rows($query)>0) {//si oui
				$res=mysqli_fetch_assoc($query);//on charge ses informations dans res
				//on vérifie que le mdp rentré correspond
				if (password_verify($_POST['password'], $res['hash'])) {
					//si oui on charge les infos de l'utilisateur dans la session
					$_SESSION['name']=$res['name'];
					$_SESSION['id']=$res['id_user'];
					$_SESSION['email']=$res['email'];
					//on retourne à l'accueil
					header('Location: index.php');
				}
			}
			//si l'utilisateur utilisant cet email ou ce nom n'existe pas 
			echo '<strong class="warning">email ou mot de passe incorrect</strong>';//message d'erreur
		}
		include 'connec_form.html';//réaffichage du formulaire de connection
		mysqli_close($link);
	?>
	</div>
	<script src="script.js"></script>
</body>
</html>