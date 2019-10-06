<?php
namespace Izi\Lib;

class Lock
{
	public $key;
	public $falseAttempts = 0;  // Count of attempts

	public function open($password)
	{
		if (sha1($password) == $this->key)
			return true;
		else
			return false;
	}
}