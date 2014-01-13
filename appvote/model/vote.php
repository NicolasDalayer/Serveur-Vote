<?php
class Vote
{
	public $id;
	public $name;
	public $description;
	public $deadline;
	public $complete = 0;

	public function toDB()
	{
		$object = get_object_vars($this);
		return $object;
	}
}