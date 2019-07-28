


<?php


/**
 * 	1. Récupérer la date de début et la date de fin et calculer le nombre de weekdays
 * 	2. Faire convertit date_début et date_fin en String 
 * 	3. Retourner 0 si il y'a zéro weekdays
 */

function getNumJoursSemaine($date_debut, $end_day)
{
	//use time stamp
	$date_debut_ts = strtotime($date_debut);
	$date_fin_ts = strtotime($end_day);
	$num_joursSemaine = 0;
	while ($date_debut_ts <= $date_fin_ts ) {
		if (date("N", $date_debut_ts) < 6){
			++$num_joursSemaine; 
		}
		$date_debut_ts += 86400;// Ajouter un jour ( 86400 secondes )
	}
	return $num_joursSemaine;
}

/**
 * Création d'un tableau de nombre aléatoires
 * Il faut normaliser le tableau 
 * Retourner un tableau normalisé 
 */


function getTableauNombresAleat($num_joursSemaine){
	$total = 0;
	$nombre_aleatoire = 0;
	$tableau_joursSemaine = array();
	$tableau_norm = array();		
	for ($i= 0 ; $i < $num_joursSemaine ; $i++) {


		// Générer un nombre aléatoire.
		// si on a un intervalle long, on dois utiliser une precision plus supérieure 
		
		
		$nombre_aleatoire = mt_rand(1,1000);


		// Ajouter le au total

		$total += $nombre_aleatoire;

		//  mettre le nombre aléatoire dans le tableau 


		array_push($tableau_joursSemaine , $nombre_aleatoire);
	}

	// Normaliser le tableau .


	$total = floatval($total);
	foreach ( $tableau_joursSemaine as $wd ) {	
		array_push( $tableau_norm, floatval($wd) / $total);		
	}
	return $tableau_norm;
}


// La fonction Main : récupérer la date_début et la date_fin et remplir le tableau avec les valeurs.

function dist_montant_aleatoirement($montant, $baseline, $start_date, $date_fin){


	// on va utiliser time stamp

	$date_debut_ts = strtotime($start_date);
	$date_fin_ts = strtotime($date_fin);


	// Calculer le nombre de weekdays

	$num_joursSemaine = getNumJoursSemaine($start_date, $date_fin);

	// un tableau unidimensionnel normalisé de taille $num_joursSemaine


	$tableau_norm = getTableauNombresAleat($num_joursSemaine);
	$indice_tableau_norm = 0;
	$tableau_sortie = array();

	// Calculer le baseline
	// nombre min des weekdays.

	$min_en_jours = floatval($montant)*floatval($baseline)/(floatval($num_joursSemaine)*100.0);


	// montant pour distribuer avec 1% de marge 

	$montant_a_distribuer = floatval(100-$baseline) *floatval($montant*1.01)/100.0;

	// remplir le tableau de sortie
	while ($date_debut_ts <= $date_fin_ts ) {		
		if (date("N", $date_debut_ts) < 6){

			// Créer un tableau non-zero elements.

			$montant_de_joursSemaine = $tableau_norm[$indice_tableau_norm]*$montant_a_distribuer+$min_en_jours;

			// Convertir à 2 nombres décimals.
			$montant_formate = number_format($montant_de_joursSemaine, 2,'.','');

			// Incrémenter l'indice du tableau normalisé
 			$indice_tableau_norm++;
		} else {

			// Créer un tableau de zero elements.
			$montant_formate = "0.00";	
		}
		// Convertir date_debut en String.
		$date_actuelle = date('Y-m-d',$date_debut_ts);

		// Ajouter la à la fin du tableau.
		$tableau_sortie += [$date_actuelle => $montant_formate];	
		// Ajouter 86400 secondes ( un jour ).				
		$date_debut_ts += 86400;
	}
	return $tableau_sortie;
}
// Affichage du tableau de sortie.

$sortie_finale = dist_montant_aleatoirement($_POST["total"], $_POST["baseline"], $_POST["start_date"], $_POST["end_date"]);

$weekdays =getNumJoursSemaine( $_POST["start_date"], $_POST["end_date"]);
$min_montant_par_jourSemaine = number_format(floatval($_POST["total"])*floatval($_POST["baseline"])/floatval($weekdays*100), 2,'.','');
$somme = 0;


?> 

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Contact V4</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>


	<div class="container-contact100">
		<div class="wrap-contact100">
			
		
				<span class="contact100-form-title">
					Plogg Test
				</span>
				
	
			<div  data-validate="Total is required">
				<label for="total">Le total à distribuer est : </label>
				<span class="label-input100"><?php echo $_POST["total"]; ?></span>	
				</div>




				<div  data-validate="Total is required">
				<label for="total">Le nombre de jours de semaine est : </label>
				<span class="label-input100"><?php echo $weekdays =getNumJoursSemaine( $_POST["start_date"], $_POST["end_date"]); ?></span>	
				</div>

				<div  data-validate="Total is required">
				<label for="total">Le montant minimum de chaque jour est : </label>
				<span class="label-input100"><?php echo $min_montant_par_jourSemaine; ?></span>	
				</div>



				
			
				
			
<table >
<thead>
<tr>
<th style="padding: 10px 55px 10px 55px;">Date</th>
<th style="padding: 10px 55px 10px 55px;">Valeur</th>
</tr>
</thead>
<tbody>
	<?php

foreach ($sortie_finale  as $key => $val){
	?>


<tr>
<td style="padding: 10px 45px 10px 45px;"><?php  echo $key ?></td>
<td style="padding: 10px 45px 10px 45px;"><?php  echo $val ?></td>
</tr>


<?php
	
	$somme += $val;
}

	?>

<div data-validate="Total is required">
				<label for="total">La somme de toutes les valeurs est : </label>
				<span class="label-input100"><?php echo $somme; ?></span>	
				</div>

</tbody>
</table>
				
			
			

				<div class="container-contact100-form-btn">
					<div class="wrap-contact100-form-btn">
						<div class="contact100-form-bgbtn"></div>
						<button class="contact100-form-btn">
							
							<a href="index.html" style="color:white;">
							<span>
							ESSAYER A NOUVEAU	
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
							</span>
							</a>
						</button>
					</div>
				</div>
			


			
		</div>
	</div>

	



	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
	</script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

</body>
</html>
