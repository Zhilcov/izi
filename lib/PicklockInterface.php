<?php

namespace Izi\Lib;

interface PicklockInterface
{
	public function unlock(Lock $lock);
	
	public function unlockAllLocks();
	
	/**
	Use this method to var_dump variable $locks
	*/
	public function varDumpLockResults();
}