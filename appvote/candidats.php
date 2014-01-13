<?php
/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : candidats.php
 * + Description : Fichier .php utilisé pour obtenir
 * la liste des candidats participants au vote en paramètre.
 */

require_once('database/db.php');
require_once('model/vote.php');
require_once('model/candidat.php');
require_once('model/user.php');

// Paramètres attendu dans la requête
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

//Si l'utilisateur existe, alors
if($user !== false)
{
	$user->id = (int) $user->id;

	// Rechercher tous les candidats participants au vote entré en paramètre
	$candidats = $db->search('Candidat', 'candidat', 'vote = :vote', $parameters);

	//Pour chacun des candidats dans la liste, retirer la colonne "vote"
	foreach($candidats as $candidat)
	{
		unset($candidat->vote);
	}
	//Mise en forme de la réponse en json.
	$json = array(
		'error' => false,
		'candidats' => $candidats
	);
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);