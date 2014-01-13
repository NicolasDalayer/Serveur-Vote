<?php
require_once('database/db.php');
require_once('model/voteclose.php');
require_once('model/user.php');

/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : votesclose.php
 * + Description : Fichier .php utilisé pour obtenir la liste des votes terminés
 */
 
// Paramètre attendu dans la requête
$parameters = array
(
	':token' => null
);

// Mise en forme de la requête http en sql
foreach($_GET as $key => $value)
{
	$parameters[":$key"] = $value;
}

$json = array(
	'error' => true
);
// Connexion à la BDD
$config = require_once('config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

// Grâce au token en paramètre, trouver l'utilisateur
$user = $db->find('User', 'user', 'token = :token', $parameters);

// Si l'utilisateur existe
if($user !== false)
{
	$user->id = (int) $user->id;
	// Rechercher tous les votes terminés (complete = 1)
	$votes = $db->search('Vote', 'vote', 'complete = 1');

	foreach($votes as $vote)
	{
		unset($vote->complete);
	}
	
	$json = array(
		'error' => false,
		'votes' => $votes
	);
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);