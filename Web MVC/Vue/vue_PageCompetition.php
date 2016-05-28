<?php

$competition = loadCompetition(array("Id"=>$data['Competition']));

$infoCourse = '';
$index = 0;

foreach (loadCourseCompetition($competition->getId_Competition()) as $course) {
	if($course!=null){
		$infoCourse = $infoCourse .'<tr><th>Information Course</th>
		<td><input type="hidden" name="id_course" id="IdCourse_'.$index.'" value="'.$course->getId_Course().'" readonly/></td>
		<td><input type="text" name="distance" value="'.$course->getDistance().'" readonly/></td>
		<td><input type="text" name="categorie" value="'.$course->getCategorie()['Nom'].'" readonly/></td>
		<td><input type="text" name="typeEmbarcation" value="'.$course->getTypeSpecialite()['Nom'].'" readonly/></td>
		</tr>
		';

		if($_SESSION['UtilisateurCourant']->asDroit(array("Parent","Entraineur")) || get_class($_SESSION['UtilisateurCourant'])=="Competiteur"){ // on regarde si l'utilisateur est un competiteur ou un parent

			if(get_class($_SESSION['UtilisateurCourant'])=="Competiteur" && $competition->getSexe()['Nom'] == $_SESSION['UtilisateurCourant']->getSexe()['Nom']){
				$courseParticipeComp = null;
				$courseParticipeComp = $_SESSION['UtilisateurCourant']->getCourseParticipeId($course->getId_Course());

				if(!isset($courseParticipeComp)){

					if($course->getIsEquipe()){
						$infoCourse = $infoCourse .'<th>Selection Equipe</th><tr>';

						$infoCourse = $infoCourse .'<td><select name="Equipe" id="IdEquipe_'.$index.'" onchange="if(this.selectedIndex == 0){$(".NewEquipe").show();}else{$(".NewEquipe").hide();}" readonly>
						<option value="New" selected="selected">Nouvelle Equipe</option>';

						foreach($_SESSION['UtilisateurCourant']->getEquipeParticipe() as $IdEquipe){ //prend les equipe du competiteur en cours
							$equipe = loadEquipe(array("Id"=>$IdEquipe));

							if($equipe->getTypeSpecialite() == $course->getTypeSpecialite())
							$infoCourse = $infoCourse .'<option value="'.$IdEquipe.'"> '.$equipe->getNom().'</option>';

							unset($equipe);
						}

						$infoCourse = $infoCourse .'</select></td>';

						$competiteurs = BDD::getInstance()->getManager("Competiteur")->getListCategorie($_SESSION['UtilisateurCourant']->getCategorie());

						for($i=1;$i<str_split($course->getTypeSpecialite()['Nom'])[1];$i++){ //on recupere le chiffre a la fin du type ex: K2 et on ajoute les combobox

							$infoCourse = $infoCourse .'<td class="NewEquipe"><select id="IdParticipant'.$i.'_'.$index.'"readonly>';

							foreach($competiteurs as $competiteur){// on remplit les combobox avec les competiteurs de la meme categorie
								$infoCourse = $infoCourse .'<option value="'.$competiteur->getId_Competiteur().'">'.$competiteur->getPrenom().' '.$competiteur->getNom().'</option>';
							}

							$infoCourse = $infoCourse .'</select></td>';
							unset($competiteurs);

							$infoCourse = $infoCourse .'<td><input type="submit" name="Inscrit" id="btnInscritCourse_'.$index.'" module="InscriptionCourse;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Course_'.$index.';Equipe_'.$index.';Participant1_'.$index.';Participant2_'.$index.';Participant3_'.$index.'" value="Inscrire" onclick="Action(btnInscritCourse_'.$index.')"/></td></tr>';

						}
					}else{ // en solo
						$infoCourse = $infoCourse .'<td><input type="submit" name="Inscrit" id="btnInscritCourse_'.$index.'" module="InscriptionCourse;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Course_'.$index.'" value="Inscrire" onclick="Action(btnInscritCourse_'.$index.')/></td>>';
					}
				}else{//si inscrit
					$inscrit;
					if($courseParticipeComp['Validation']==1){
						$inscrit = "Valider";
					}else{
						$inscrit = "En attente de validation";
					}
					$infoCourse = $infoCourse .'<th>Statut participation</th><td><input type="text" name="Inscrit" id="IdInscrit" value="'.$inscrit.'" readonly/></td></tr>';
				}
			}

			if($_SESSION['UtilisateurCourant']->asDroit("Parent")){
				foreach($_SESSION['UtilisateurCourant']->getParente()['Enfant'] as $IdEnfant){
					$courseParticipeEnfant = null;
					$idComp = isCompetiteurUtilisateur($IdEnfant);

					if($idComp){
						$courseParticipeEnfant = loadCompetiteur(array("Id"=>$idComp['Id_Competiteur']))->getCourseParticipeId($course->getId_Course());
					}

					if(!isset($courseParticipeEnfant)){// si le competiteur ne participe pas

						if($course->getIsEquipe()){ //si la course est en equipe
								$infoCourse = $infoCourse .'<tr><th>Selection Equipe Enfant</th>';

								$IdEnfants = $_SESSION['UtilisateurCourant']->getParente()['Enfant'];

								$infoCourse = $infoCourse .'<td><select name="Enfant" id="IdEnfant_'.$index.'" readonly>';

								foreach($IdEnfants as $IdEnfant){ // on prend tous ces enfants
									$idComp = isCompetiteurUtilisateur($IdEnfant);
									if($idComp){// on regarde si l'enfant est un competiteur
										$enfant = loadCompetiteur(array("Id"=>$idComp['Id_Competiteur']));

										$infoCourse = $infoCourse .'<option value="'.$enfant->getId_Competiteur().'">'.$enfant->getPrenom().' '.$enfant->getNom().'</option>';
									}

								}

								$infoCourse = $infoCourse .'</select></td>';

								$competiteurs = BDD::getInstance()->getManager("Competiteur")->getListMineur();

								for($i=1;$i<str_split($course->getTypeSpecialite()['Nom'])[1];$i++){ //on recupere le chiffre a la fin du type ex: K2 et on ajoute les combobox

									$infoCourse = $infoCourse .'<td><select id="IdParticipantEnfant'.$i.'_'.$index.'"readonly>';

									foreach($competiteurs as $competiteur){// on remplit les combobox avec les competiteurs de la meme categorie
										$infoCourse = $infoCourse .'<option value="'.$competiteur->getId_Competiteur().'">'.$competiteur->getPrenom().' '.$competiteur->getNom().'</option>';
									}

									$infoCourse = $infoCourse .'</select></td>';
								}
								unset($competiteurs);

								$infoCourse = $infoCourse .'<td><input type="submit" name="Inscrit" id="btnInscritCourse_'.$index.'" module="InscriptionCourse;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Course_'.$index.';Enfant_'.$index.';ParticipantEnfant1_'.$index.';ParticipantEnfant2_'.$index.';ParticipantEnfant3_'.$index.'" value="Inscrire" onclick="Action(btnInscritCourse_'.$index.')"/></td></tr>';


						}else{// si la course est en solo
								$infoCourse = $infoCourse .'<tr><th>Selection Enfant</th>';

								$IdEnfants = $_SESSION['UtilisateurCourant']->getParente()['Enfant'];

								$infoCourse = $infoCourse .'<td><select name="Enfant" id="IdEnfant_'.$index.'" readonly>';

								foreach($IdEnfants as $IdEnfant){ // on prend tous ces enfants

									if($idComp = isCompetiteurUtilisateur($IdEnfant)){// on regarde si l'enfant est un competiteur
										$enfant = loadCompetiteur(array("Id"=>$idComp['Id_Competiteur']));

										$infoCourse = $infoCourse .'<option value="'.$enfant->getId_Competiteur().'">'.$enfant->getPrenom().' '.$enfant->getNom().'</option>';
									}

								}

								$infoCourse = $infoCourse .'</select></td>';

								$infoCourse = $infoCourse .'<td><input type="submit" name="Inscrit" id="btnInscritCourse_'.$index.'" module="InscriptionCourse;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Course_'.$index.';Enfant_'.$index.'" value="Inscrire" onclick="Action(btnInscritCourse_'.$index.')"/></td></tr>';

						}
					}else{//si inscrit
						$inscrit;
						if($courseParticipeEnfant['Validation']==1){
							$inscrit = "Valider";
						}else{
							$inscrit = "En attente de validation";
						}
						$infoCourse = $infoCourse .'<th>Statut participation</th><td><input type="text" name="Inscrit" id="IdInscrit" value="'.$inscrit.'" readonly/></td></tr>';
					}
				}

			}

			if($_SESSION['UtilisateurCourant']->asDroit("Entraineur")){

				if($course->getIsEquipe()){
					$infoCourse = $infoCourse .'<tr><th>Validation Equipe</th>';
					
					$i=0;
					foreach ($course->getParticipant() as $IdParticipant){

						
						$equipe = loadEquipe(array("Id"=>$IdParticipant['Id']));

						$infoCourse = $infoCourse .'<td>'.$equipe->getNom().'</td>';

						if(!$IdParticipant['Validation']){
							$infoCourse = $infoCourse .'<td><input type="hidden" name="id_equipeValider" id="IdEquipeValider_'.$i.'_'.$index.'" value="'.$equipe->getId_Equipe().'" readonly/>';
							$infoCourse = $infoCourse .'<input type="submit" name="Validation" id="btnValidationEquipe_'.$index.'" module="ValidationEquipe;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Course_'.$index.';EquipeValider_'.$i.'_'.$index.'" value="Valider" onclick="Action(btnValidationEquipe_'.$index.')"/></td>';
						}else{
							$infoCourse = $infoCourse .'<td>Valider</td>';
						}

						$i++;
					}

					$infoCourse = $infoCourse .'</tr>';
				}else{
					$infoCourse = $infoCourse .'<tr><th>Validation Competiteur</th>';
					
					$i=0;
					foreach ($course->getParticipant() as $IdParticipant) {
						$comp = loadCompetiteur(array("Id"=>$IdParticipant['Id']));

						$infoCourse = $infoCourse .'<td>'.$comp->getNom().' '.$comp->getPrenom().'</td>';

						if(!$IdParticipant['Validation']){
							$infoCourse = $infoCourse .'<td><input type="hidden" name="id_competiteureValider" id="IdCompetiteurValider_'.$i.'_'.$index.'" value="'.$comp->getId_Competiteur().'" readonly/>';
							$infoCourse = $infoCourse .'<input type="submit" name="Validation" id="btnValidationCompetiteur_'.$index.'" module="ValidationCompetiteur;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Course_'.$index.';CompetiteurValider_'.$i.'_'.$index.'" value="Valider" onclick="Action(btnValidationCompetiteur_'.$index.')"/></td>';
						}else{
							$infoCourse = $infoCourse .'<td>Valider</td>';
						}
						$i++;
					}

					$infoCourse = $infoCourse .'</tr>';
				}
			}

		}
		$infoCourse = $infoCourse .'</tr>';
		$index++;
	}
}

$infoVoyage = '<tr><th>Informations Voyage</th>';
$voyage = loadVoyageCompetition($competition->getId_Competition());

if($voyage!=null){
	$infoVoyage = $infoVoyage .'
	<td><input type="hidden" name="id_voyage" id="IdVoyage" value="'.$voyage->getId_Voyage().'" readonly/></td>
	<td><input type="text" name="voyageTransport" id="IdTransport" value="'.$voyage->getTransport().'" readonly/></td>
	<td><input type="text" name="voyageHebergement" id="IdHebergement" value="'.$voyage->getHebergement().'" readonly/></td>
	</tr>';
	$infoVoyage = $infoVoyage .'<th>Participants</th>';

	foreach ($voyage->getParticipe() as $infoParticipant){
		$participant = loadCompetiteur(array('Id' => $infoParticipant["Id_Competiteur"]));

		$infoVoyage = $infoVoyage .'<tr>
		<td>'.$participant->getNom().' '.$participant->getPrenom().'</td>
		<td>'.$infoParticipant['Type_Voyage']['Nom'].'</td>';

		if($infoParticipant['Type_Voyage']['Nom']!="Club"){
			$uti = loadUtilisateur(array("Id" => $infoParticipant['Id_Utilisateur']));
			$infoVoyage = $infoVoyage .'<td>'.$uti->getNom().' '.$uti->getPrenom().'</td>';
		}

		if($participant->getDateNaissance()->diff(new DateTime())->format('Y')<18){
			$auto;
			if($infoParticipant['Autoriser']==1){
				$auto = 'Autoriser';
			}else{
				$auto = 'En attente d&apos;autorisation';
			}
			$infoVoyage = $infoVoyage .'<td>'.$auto.'</td>';
		}

		$infoVoyage = $infoVoyage .'</tr>';
	}

	$infoVoyage = $infoVoyage .'<th>Charge</th>';

	foreach ($voyage->getCharge() as $infoCharge){
		$uti = loadUtilisateur(array('Id' => $infoCharge["Id_Utilisateur"]));

		$infoVoyage = $infoVoyage .'<tr>
		<td>'.$uti->getNom().' '.$uti->getPrenom().'</td>
		<td>'.$infoCharge['Role']['Titre'].'</td>
		<td>'.$infoCharge['Tache']['Nom'].'</td></tr>';
	}
}

$infoMoyenTransport = ''; //recupere le type de transport
foreach(BDD::getInstance()->getManager("Type_Voyage")->getList() as $typeTransport) {
	$infoMoyenTransport = $infoMoyenTransport.'<option value="'.$typeTransport["Id"].'" >'.$typeTransport["Nom"].'</option>';
}

$infoUtilisateurTransport = ''; //recupere les utilisateurs pour le transport
foreach(BDD::getInstance()->getManager("Utilisateur")->getListMajeur() as $ut){
	$infoUtilisateurTransport = $infoUtilisateurTransport . '<option value="'.$ut->getId_Utilisateur().'">'.$ut->getNom().' '.$ut->getPrenom().'</option>';
}

$infoTransport = '';
if(get_class($_SESSION['UtilisateurCourant'])=="Competiteur"){
	$i = 0;
	$trouver = false;
	$inf;
	while($i!=count($voyage->getParticipe()) && $trouver==false){
		if($_SESSION['UtilisateurCourant']->getId_Competiteur()==$voyage->getParticipe()[$i]['Id_Competiteur']){
			$trouver == true;
			$inf = $voyage->getParticipe()[$i];
		}
		$i++;
	}

	if(!$trouver){
		$infoTransport = $infoTransport .'<label>Moyen de transport</label> <select name="TypeTransport" id="IdTypeTransport" readonly>
		'.
		$infoMoyenTransport
		.'</select></br>
		<p>Si vous utilisez un autre moyen de transport que le club veuillez le renseigner si dessous.</p></br>
		<select name="role" id="IdUtilisateurTransport" readonly>
		'.
		$infoUtilisateurTransport
		.'
		</select><br/>
		<input type="submit" name="AjouterTransport" id="btnAjouterTransport" module="AjouterTransport;PageCompetition" regionSucess="#competition;#competition" regionError="#competition;#competition" donne="Competition;Voyage;TypeTransport;UtilisateurTransport" value="Valider"/>';
	}else{
		$infoTransport = $infoTransport .'<label>Moyen de transport</label> <input type="text" placeholder="" name="nom" id="IdTransportUtiliser" value="'.$inf['Type_Voyage']['Nom'].'" readonly/><br/>';
		if($inf['Type_Voyage']['Nom']!="Club"){
			$uti = loadUtilisateur(array('Id' => $inf["Id_Utilisateur"]));
			$infoTransport = $infoTransport .'<input type="text" placeholder="" name="nom" id="IdUtilisateurTransport" value="'.$uti->getNom().' '.$uti->getPrenom().'" readonly/><br/>';
		}
	}
}

$response_array = array();
$response_array['Status'] = "Success";
$response_array['Type'] = "Replace";
$response_array['Donne'] = '<input type="hidden" name="id_Competition" id="IdCompetition" value="'.$competition->getId_Competition().'">
<label>Nom de la compétition</label> <input type="text" placeholder="" name="nom" id="IdNom" value="'.$competition->getTypeCompetition()['Nom'].'-'.$competition->getAdresse().'" readonly/><br/>
<label>Date de la compétition</label> <input type="number" placeholder="jour" name="jour" id="IdJour" min="1" max="31" value="'.$competition->getDateCompetition()->format('d').'" readonly/>
<input type="number" placeholder="mois" name="mois" id="IdMois" min="1" max="12" value="'.$competition->getDateCompetition()->format('m').'" readonly/>
<input type="number" placeholder="année" name="annee" id="IdAnnee" min="1950" max="'.date('Y').'" value="'.$competition->getDateCompetition()->format('Y').'" readonly/><br/>
<label>Nom du club organisateur</label> <input type="text" placeholder="" name="nomClub" id="IdNomClub" value="'.$competition->getClub()->getNom().'" readonly/><br/>
<label>Limitation de sexe</label> <input type="text" placeholder="" name="limiteSexe" id="IdSexe" value="'.$competition->getSexe()['Type'].'" readonly/><br/>

<table name="InfoEpreuve">
'.
$infoCourse
.'
</table>

<div id="transport">
'.
$infoTransport
.'
</div>

'.
(($_SESSION['UtilisateurCourant']->asDroit(array("Secrétaire","Entraineur")))?('<table name="InfoVoyage>"
'.$infoVoyage.'
</table>')
:(''))
.'

';
$response_array['Stop'] = "false";
$response_array['Region'] = $_POST['regionSucess'];

header('Content-type: application/json');
echo json_encode($response_array);



?>
