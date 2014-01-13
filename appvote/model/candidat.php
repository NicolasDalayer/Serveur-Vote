<?php
class Candidat
{
	public $id;
	public $nom;
	public $vote;
	
	public function toDB()
	{
		$object = get_object_vars($this);
		return $object;
	}
}