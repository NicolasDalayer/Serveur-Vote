<?php
/*
 * + Projet : IF26 - Application de vote
 * + Date : Automne 2014
 * + Lieu : Université Technologique de Troyes (10000)
 * + Auteur : Nicolas D'ALAYER DE COSTEMORE D'ARC & Alexandre ORTIZ
 * -----------------------------------------------------------------
 * + Type : PHP
 * + Name : vote.php
 * + Description : Fichier .php utilisé pour récupérer la liste des votes en cours
 */
require_once('database/db.php');
require_once('model/vote.php');
require_once('model/votes.php');
require_once('model/user.php');
require_once('model/resultat.php');

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

// Vérification si l'utilisateur existe ou pas
$user = $db->find('User', 'user', 'token = :token', $parameters);

//Si l'utilisateur existe, alors
if($user !== false)
{
	$user->id = (int) $user->id;
	
	// Recherche de tous les votes qui ne sont pas terminés (complete=0)
	$votes = $db->search('Vote', 'vote', 'complete = 0');
	
	//Initialisation de la date courante au fuseau horraire Europe/Paris
	$currentdate = new DateTime("now", new DateTimeZone('Europe/Paris') );
	
	
	foreach($votes as $vote)
	{
		//Initialisation de la date limite du vote au fuseau horraire Europe/Paris
		$deadline = new DateTime($vote->deadline, new DateTimeZone('Europe/Paris'));
		
		// Si la date courrante est supérieur à la date du vote, alors :
		if($currentdate->diff( $deadline )->invert == 1)
		{
		//Fonction de cette partie du code : Mettre à jour la table "vote"
			//Initialiser le complete du vote à 1
			$vote->complete = 1;
			//Mettre à jour le vote dans la table
			$db->update($vote, 'vote', 'id = :id', array(':id' => $vote->id));
			
			//Fonction de cette partie du code : 
			// Rechercher les votes qui ont étés fait pour le vote.
			$resultats = $db->search('Votes', 'votes', 'idvote = :id', array(':id' => $vote->id));
			//Initialiser un tableau.
			$count = array();
			// Compter le nombre de ligne pour chacun des candidats
			foreach($resultats as $resultat)
			{
				if(isset ($count[$resultat->idcandidat]))
				{
					$count[$resultat->idcandidat]++;
				}
				else
				{
					$count[$resultat->idcandidat]=1;
				}
				
			}
			//Initialisation de la variable pour déterminer qui sera le gagnant
			$winner = array('idcandidat'=>0, 'nbvoies'=>0);
			
		//Fonction de cette partie du code : Déterminer qui est le gagnant suivant le nombre de voies.
			foreach($count as $key=>$values)
			{
				if($values > $winner['nbvoies'])
				{
					$winner['idcandidat']=$key;
					$winner['nbvoies']=$values;
				}
				$voies = new Resultat();
				$voies->vote = $vote->id;
				$voies->idcandidat = $key;
				$voies->nbvoies = $values;
				$voies->winner = 0;
				
				$db->insert($voies, 'resultat');
			}
		//Fonction de cette partie du code : Mettre toutes les données du vote et du candidat du foreach dans la table resultat.
			$dbwinner = new Resultat();
			$dbwinner->vote = $vote->id;
			$dbwinner->idcandidat = $winner['idcandidat'];
			$dbwinner->nbvoies = $winner['nbvoies'];
			$dbwinner->winner = 1;
			$db->update($dbwinner, 'resultat', 'vote = :vote AND idcandidat = :idcandidat', array(':vote' => $dbwinner->vote, ':idcandidat' => $dbwinner->idcandidat));
		}
		// Cacher la colonne complete.
		unset($vote->complete);
	}
	$json = array(
		'error' => false,
		'votes' => $votes
	);
}
// echo json_encode($json, JSON_PRETTY_PRINT);            5.4 required!!
echo json_encode($json);