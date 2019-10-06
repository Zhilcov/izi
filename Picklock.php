<?php
/**
 * This class has to call out the lock objects by name. i.e. 'lock1' should call
 * the class Lock1. This should not be hardcoded and must support adding of
 * new locks later on.
 * Using rainbow tables is not allowed.
*/

namespace Izi;

use Izi\Lib\PicklockInterface;
use Izi\Lib\Lock;

class Picklock implements PicklockInterface
{
	// Don't change static fields
    private static $locks = array(
		'lock1' => array(
			'password' => NULL, 			// cracked password
			'millisecondsToUnlock' => 0,	// how many milliseconds did the cracking take
			'falseAttempt' => 0 			// count of tries
		),
		'lock2' => array(
			'password' => NULL,
			'millisecondsToUnlock' => 0,
			'falseAttempt' => 0
		),
		'hardlock3' => array(
			'password' => NULL,
			'millisecondsToUnlock' => 0,
			'falseAttempt' => 0
		),
        'my_lock' => array(
			'password' => NULL,
			'millisecondsToUnlock' => 0,
			'falseAttempt' => 0
		),
	);

	const minSymbols = 2;	// hint
	const maxSymbols = 4;	// hint

	private $symbols = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'õ', 'ä', 'ö', 'ü', 'x', 'y'); // see on kolmas ja viimane vihje


    public function brud_force ($size = 2, $array_elems_to_combine = array('a', 'b', 'c')){
        $current_set = array('');
        for ($i = 0; $i < $size; $i++) {
            $tmp_set = array();
            foreach ($current_set as $curr_elem) {
                foreach ($array_elems_to_combine as $new_elem) {
                    $tmp_set[] = $curr_elem . $new_elem;
                }
            }
            $current_set = $tmp_set;
        }
        return $current_set;
    }

    /**
     * @var array
     */

    public function unlock(Lock $lock){
        $start = microtime(true);
        for ($i = self::minSymbols; $i <= self::maxSymbols; $i++) {
            $all_cumbinations = $this->brud_force($i,$this->symbols);

            foreach ($all_cumbinations as $password) {
                if ($lock->open($password)){
                    self::$locks[$lock->getOwnClass()] = [
                        'password' => $password,
                        'millisecondsToUnlock' => round(microtime(true) - $start, 4)*1000,
                        'falseAttempt' =>$lock->falseAttempts
                    ];
                    return;
                }
            }
        }


    }

    public function unlockAllLocks(){
        foreach (array_keys(self::$locks) as $class_name) {
            $firsUpper = "Izi\Locks\\".ucfirst($class_name);
            $obj = new $firsUpper;
            $this->unlock($obj);
        }
    }

    /**
    Use this method to var_dump variable $locks
     */
    public function varDumpLockResults(){
        echo '<dl>';
        foreach (self::$locks as $key => $value) {
            echo '<dt>' .$key. '</dt>';
            echo '</dd>';
                echo '<ul type="square">';
                    foreach ($value as $key =>$item) {
                            echo '<li>';
                                echo  $key.': '. $item ;
                            echo '</li>';
                    }
                echo '</ul>';
            echo '</dd>';
        }
        echo '</dl>';
    }
}