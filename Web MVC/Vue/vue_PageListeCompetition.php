<?php
$res = '';

$competitions = loadListeCompetition(true);

$index =0;
foreach($competitions as $competition){

  $res = $res .'<tr>
                <td>
                  <input type="hidden" name="id_Competition" id="IdCompetition_'.$index.'" value="'.$competition->getId_Competition().'">
                </td>
                <td>
                  <input type="text" placeholder="" name="nom" id="IdNom" value="'.$competition->getTypeCompetition()['Nom'].'-'.$competition->getAdresse().'" readonly/>
                </td>
                <td>
                  <input type="number" placeholder="jour" name="jour" id="IdJour" min="1" max="31" value="'.$competition->getDateCompetition()->format('d').'" readonly/>
                  <input type="number" placeholder="mois" name="mois" id="IdMois" min="1" max="12" value="'.$competition->getDateCompetition()->format('m').'" readonly/>
                  <input type="number" placeholder="année" name="annee" id="IdAnnee" min="1950" max="'.date('Y').'" value="'.$competition->getDateCompetition()->format('Y').'" readonly/>
                </td>
                <td>
                  <input type="text" placeholder="" name="nomClub" id="IdNomClub" value="'.$competition->getClub()->getNom().'" readonly/>
                </td>
                <td>
                  <input type="submit" id="btnDetailCompetition" module="PageCompetition" regionSucess="#competition" regionError="#listeCompetition" donne="Competition_'.$index.'" value="Details"/>
                ';

  if($_SESSION['UtilisateurCourant']->asDroit(array("Entraineur","Secretaire"))){
    $res = $res .'<input type="submit" id="btnModifierCompetition" module="ModifierCompetition" regionSucess="#competition" regionError="#listeCompetition" donne="Competition_'.$index.'" value="Modifier"/>';
  }

  $res = $res .'</td></tr>';
  $index++;
}

$response_array = array();
$response_array['Status'] = "Success";
$response_array['Type'] = "Replace";
$response_array['Donne'] = '<div id="listeCompetition" class="div_liste_competition">
                              <h1> Liste des compétitions </h1>
                              <table>
                                '.
                                  $res
                                .'
                              </table>
                            </div>
                            <div id="competition">


                            </div>
                            ';
$response_array['Stop'] = "false";
$response_array['Region'] = $_POST['regionSucess'];

header('Content-type: application/json');
echo json_encode($response_array);


?>
