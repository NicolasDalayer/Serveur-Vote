<?php
/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : createaccount.php
 * + Description : Fichier .php utilisé pour la création d'un 
 * compte utilisateur.
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

// Grâce à l'adresse e-mail en paramètre, trouver l'utilisateur
$user = $db->find('User', 'user', 'email = :email', array(':email' => $parameters[':email']));

//Si l'utilisateur n'existe pas
if($user == false)
{
	//Mise en forme des données pour insertion
	$user = new User();
	$user->email = $parameters[':email'];
	$user->password = $parameters[':password'];
	$user->token = '';
	
	//Insertion dans la base de donnée
	$id = $db->insert($user, 'user');
	
	//Si l'insertion c'est correctement déroulée, alors
	if($id !== false)
	{
		//Créer un token pour cette utilisateur.
		$user->id = (int) $id;
		$token = md5(time() . $user->email . $user->password);
		$user->token = $token;
	}
	//Si la mise à jour de l'utilisateur pour mettre le token, alors envoyer le token en réponse.
	if($db->update($user, 'user', 'id = :id', array(':id' => $user->id)))
	{
			$json = array
			(
				'error' => false,
				'token' => $token
			);
	}
}
	

// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);