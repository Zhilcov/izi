<?php
namespace Izi\Lib;

class Lock
{
	public $key;
	public $falseAttempts = 0;  // Count of attempts

    public function getOwnClass (){
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

	public function open($password)
	{
		if (sha1($password) == $this->key)
			return true;
		else
		    $this->falseAttempts++;
			return false;
	}
}