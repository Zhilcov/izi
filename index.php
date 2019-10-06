<?php

// Run this file to crack the passwords.
// Please use an autoloader.

use Izi\Picklock;
use Izi\Locks\Lock1 as l1;

require_once __DIR__ . '/vendor/autoload.php';


$picklock_obj = new Picklock();

//$picklock_obj->unlock(new l1());

$picklock_obj->unlockAllLocks();

$picklock_obj->varDumpLockResults();

