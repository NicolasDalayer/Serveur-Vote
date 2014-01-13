<?php
require_once('database/db.php');
require_once('model/resultat.php');
require_once('model/user.php');
require_once('model/candidat.php');
/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : winner.php
 * + Description : Fichier .php utilisé pour obtenir le gagnant du vote sélectionné
 */
 
// Paramètre attendu dans la requête
$parameters = array
(
	':token' => null,
	':vote' => null,
	':winner' => null
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

// Si l'utilisateur existe
if($user !== false)
{
	$user->id = (int) $user->id;
	// Rechercher dans la table des résultats le gagnant correspondant au vote
	$resultats = $db->search('Resultat', 'resultat', 'vote = :vote AND winner = :winner', $parameters);
	
foreach($resultats as $key => $value)
	{
		//Rechercher suivant l'id du candidat, son nom dans la table candidat
		$name = $db->search('Candidat', 'candidat', 'id = :idcandidat', array('idcandidat' => $value->idcandidat));
		
		// Sauvegarde du $nom dans $value
		$value->idcandidat = $name[0]->nom;
		
		// Sauvegarde de $value dans $resultats
		$value->idcandidat = $name[0]->nom;
		$resultats[$key] = $value;
		
		$db->update($value, 'candidat', 'idcandidat = :idcandidat', array(':idcandidat' => $value->idcandidat));
		
		unset($value->vote);
		unset($value->winner);
	}
	$json = array(
		'error' => false,
		'resultats' => $resultats
	);
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);