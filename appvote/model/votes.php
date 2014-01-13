<?php
class Votes
{
	public $idcandidat;
	public $idvote;
	public $token;

	public function toDB()
	{
		$object = get_object_vars($this);
		return $object;
	}
}