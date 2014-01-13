<?php
/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : resultats.php
 * + Description : Fichier .php utilisé pour récupérer la liste des candidats
 * et le nombre de voie obtenu pour chacun.
 */
require_once('database/db.php');
require_once('model/resultat.php');
require_once('model/user.php');
require_once('model/candidat.php');

// Paramètres attendus dans la requête
$parameters = array
(
	':token' => null,
	':vote' => null
);

// Mise en forme de la requête http en sql
foreach($_GET as $key => $value)
{
	$parameters[":$key"] = $value;
}

$userParameters = array(
	array_shift(array_keys($parameters)) => array_shift($parameters)
);

$json = array(
	'error' => true
);

// Connexion à la BDD
$config = require_once('config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

// Grâce au token en paramètre, trouver l'utilisateur
$user = $db->find('User', 'user', 'token = :token', $userParameters);

// Si l'utilisateur existe, alors
if($user !== false)
{
	$user->id = (int) $user->id;
	// Rechercher les lignes dont le vote est celui rentré en paramètre
	$resultats = $db->search('Resultat', 'resultat', 'vote = :vote', $parameters);
	
	// Pour chacune des lignes trouvées
	foreach($resultats as $key => $value)
		{
			//Rechercher suivant l'id du candidat, son nom
			$name = $db->search('Candidat', 'candidat', 'id = :idcandidat', array('idcandidat' => $value->idcandidat));
			
			//Mettre la valeur dans une variable
			$value->idcandidat = $name[0]->nom;
			$resultats[$key] = $value;

			//Mettre à jour la table pour l'envoie de la requête
			$db->update($value, 'candidat', 'idcandidat = :idcandidat', array(':idcandidat' => $value->idcandidat));
			
			// Cacher les colonnes vote et winner dans la réponse
			unset($value->vote);
			unset($value->winner);
		}
		// Envoyer au format json.
		$json = array(
			'error' => false,
			'resultats' => $resultats
		);
	}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);
