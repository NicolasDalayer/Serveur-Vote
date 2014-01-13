<?php
class Resultat
{
	public $vote;
	public $idcandidat;
	public $nbvoies;
	public $winner;


	public function toDB()
	{
		$object = get_object_vars($this);
		return $object;
	}
}