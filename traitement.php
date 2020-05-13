<?php
	if (isset($_GET['day']) && isset($_GET['month']) && isset($_GET['year'])) {
		$requete=mysqli_query($link,'SELECT * FROM reservation WHERE annee='.$_GET['year'].' AND mois='.$_GET['month'].' AND jour='.$_GET['day']);
		switch ($_GET['action']) {
			case 'book':
				if (mysqli_num_rows($requete)>0) {
					$tmp=mysqli_fetch_assoc($requete);
					if ($tmp['id_user']!=$_SESSION['id']) {
						$requete=mysqli_query($link,'SELECT name FROM utilisateur WHERE id_user='.$tmp['id_user']);
						$tmp=mysqli_fetch_assoc($requete);
						echo '<strong class="warning">Ce créneau a déjà été réservé par '.$tmp['name'].'</strong>';
					}
				} else {
					$requete=mysqli_query($link,'INSERT INTO reservation VALUES('.$_GET['day'].','.$_GET['month'].','.$_GET['year'].','.$_SESSION['id'].')');
					if ($requete) {
						echo '<strong class="good">Le créneau vous a été accordé avec succès</strong>';
					} else {
						echo '<strong class="warning">Une erreur s\'est produite</strong>';
					}
				}
				break;
			
			case 'unbook':
				if (! $requete) {
					echo '<strong class="warning">Ce créneau est déjà libre</strong>';
				} else {
					$tmp=mysqli_fetch_assoc($requete);
					if ($tmp['id_user']!=$_SESSION['id']) {
						$requete=mysqli_query($link,'SELECT name FROM utilisateur WHERE id_user='.$tmp['id_user']);
						$tmp=mysqli_fetch_assoc($requete);
						echo '<strong class="warning">Ce créneau appartient à '.$tmp['name'].'</strong>';
					} else {
						$requete=mysqli_query($link,'DELETE FROM reservation WHERE annee='.$_GET['year'].' AND mois='.$_GET['month'].' AND jour='.$_GET['day'].' AND '.'id_user='.$tmp['id_user']);
						if ($requete) {
							echo '<strong class="good">Le créneau a été libéré avec succès</strong>';
						} else {
							echo '<strong class="warning">Une erreur s\'est produite</strong>';
						}
					}
				}
				break;
			default:
				echo '<strong class="warning">Action invalide ou non implémentée</strong>';
				break;
		}
	}
?>