<?php
class User
{
	public $id;
	public $email;
	public $password;
	public $token;

	public function toDB()
	{
		$object = get_object_vars($this);
		return $object;
	}
}