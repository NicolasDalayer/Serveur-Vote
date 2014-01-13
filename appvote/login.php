<?php
/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : login.php
 * + Description : Fichier .php utilisé pour la connexion d'un utilisateur.
 */
require_once('database/db.php');
require_once('model/user.php');

// Paramètres attendu dans la requête
$parameters = array
(
	':email' => null,
	':password' => null
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

// Grâce à l'adresse e-mail et au mot de passe en paramètre, trouver l'utilisateur
$user = $db->find('User', 'user', 'email = :email AND password = :password', $parameters);

// Si l'utilisateur existe, alors
if($user !== false)
{
	// Si l'utilisateur n'a pas de token, alors en créer un
	$token = $user->token;
	if($token == null)
	{
		$token = md5(time() . $user->email . $user->password);
		$user->token = $token;
	}
	// Mettre à jour l'utilisateur avec le nouveau token, et envoyer en réponse le token de l'utilisateur.
	if($db->update($user, 'user', 'id = :id', array(':id' => $user->id)))
	{
		$json = array(
			'error' => false,
			'token' => $token
		);
	}
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);