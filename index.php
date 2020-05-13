
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
</head>
<body class="fullpage logged-out sidebar-false">
	
	<header id="main-header" class="main-header">
	</header>
	<div class="l-site">
		<div class="l-nav">
			<nav class="nav">
				<ul>
					<li class="nav-primary" id="title"><a href="index.php" >Sauze</a></li>
					<li class="nav-primary"><a href="profil.php"><?php echo $_SESSION['name']; ?></a></li>
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
			<?php 
				include 'db.php';
				//selection annee/mois
				$year = ($_GET['year']) ?? (date("Y"));
				$month = ($_GET['month'] ?? date("m"));

				if (! is_numeric($year)) {
					$year=date("Y");
				}
				if (! is_numeric($month)) {
					$month=date("m");
				}

				if (isset($_GET['action'])) {
					include 'traitement.php';
				}

				$requete=mysqli_query($link, 'SELECT * FROM reservation WHERE id_user='.$_SESSION['id'].' AND annee='.$year.' AND (mois=8 OR mois=7)');
				if ($requete) {
					echo '<strong class="good">Il vous reste '.($allowed_days-mysqli_num_rows($requete)).' jour(s) d\'été pour '.$year.'</strong>';
				}
				
				//on regarde les reservations pour ce mois on les trie par jours 
				$query=mysqli_query($link,'SELECT jour,id_user FROM reservation WHERE annee='.$year.' AND mois='.$month.' ORDER BY jour ASC');
				$occupes=mysqli_num_rows($query);//nombre de jours réservés
				$nb_jours= cal_days_in_month(CAL_GREGORIAN, $month, $year);//nombre de jours dans ce mois
				$date = $year.'-'.$month.'-'.'01';//date au format iso
				$days = array('D', 'L', 'M', 'M','J','V', 'S');//initiales des jours
				$months = array('Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
				$day = date('w', strtotime($date));//on récupère le premier jour du mois
				$nb_cases = intval($day)+$nb_jours;//on calcule le nombre de cases nécessaires au calendrier :nb de jours du mois+premier jour
				//affichage du squelette de la page
				?>
				</div>
				<div id="calendar-container">
					<div id="month-bar">
					<a href="index.php?month=<?php 
					if ($month==1){
						echo '12&year='.($year-1);
					} else {
						echo ($month-1).'&year='.$year;
					}?>"><</a>
					<?php	echo '<strong id="month">'.$months[$month-1].' '.$year.'</strong>';?>
					<a href="index.php?month=<?php 
					if ($month==12){
						echo '1&year='.($year+1);
					} else {
						echo ($month+1).'&year='.$year;
					}?>">></a>
					</div>
				<table id="calendar">
					<thead>
						<tr id="days">
						<?php //affichage des jours de la semaine (dimanche, lundi, etc.)
						foreach ($days as $n => $d) {
							echo '			<th>'.$d.'</th>';
						}?>
						</tr>
					</thead>
					<tbody>
					<?php
					$requete=mysqli_fetch_assoc($query);//on charge la première réservation
					for ($i=0; $i < $nb_cases/7; $i++) { 
						echo '		<tr>';//on charge une ligne
						foreach ($days as $n => $d) {
							$tag='<td ';//nouvelle case
							$num = ($i * 7)+$n+1;//numero de case
							if ( ($num < $day+1) || ($num > $nb_jours+$day)) {//si la case ne correspond pas a un jour du mois
								echo $tag.'class ="empty">';//on ne met rien dedans
							} else {//sinon
								if ($requete['jour'] <= $num-$day && $requete) {//si la case est réservée
									$tag = $tag.'class="booked';//on met le tag booked
									if ($requete['id_user'] == $_SESSION['id']) {//si elle est réservée par moi
										$tag=$tag.' mine"><a href="index.php?year='.$year.'&month='.$month.'&day='.($num-$day).'&action=unbook">';//on met le tag mine
									} else {
										$tag=$tag.'"><a href="index.php?year='.$year.'&month='.$month.'&day='.($num-$day).'&action=book">';
									}
									$requete=mysqli_fetch_assoc($query);//on passe à la réservation suivante
								} else {//si elle n'est pas réservée
									$tag=$tag.'class="free"><a href="index.php?year='.$year.'&month='.$month.'&day='.($num-$day).'&action=book">';//on met le tag free et on fait un lien pour pouvoir réserver ce créneau
								}
								echo $tag;//affichage
								echo '			'.($num-$day).'</a></td>';//on ferme l'élément
							}
						}
						echo '		</tr>';//on ferme le ligne
					}
					mysqli_close($link);//on ferme l'accès à la base de données
					?>
					</tbody>
				</table>
				</div>
			</div>
			</div>
			</section>
			<section class="band band-a"></section>
		</div>
	</div>
	<script src="script.js"></script>
</body>
</html>