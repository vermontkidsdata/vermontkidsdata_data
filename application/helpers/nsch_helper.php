<?php
    defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Building Bright Futures - NSCH Helper
 *
 * A helper class to manage data from the National Survey of Children's Health
 *
 * @package     NSCH Helper
 * @author      David Lapointe
 */

 // ------------------------------------------------------------------------

/**
 * Get variable name mappings from NSCH
 *
 * @param 
 * @return array of variables and values 
 */
if( ! function_exists('nsch_adverse_experiences') )
{
	function nsch_adverse_experiences()
	{
		return array(
		'ACE10' => "Child Experienced - Treated Unfairly Because of Race",
		'ACE9' => "Child Experienced - Lived with Person with Alcohol/Drug Problem",
		'ACE8' => "Child Experienced - Lived with Mentally Ill",
		'ACE7' => "Child Experienced - Victim of Violence",
		'ACE6' => "Child Experienced - Adults Slap, Hit, Kick, Punch Others",
		'ACE5' => "Child Experienced - Parent or Guardian Time in Jail",
		'ACE4' => "Child Experienced - Parent or Guardian Died",
		'ACE3' => "Child Experienced - Parent or Guardian Divorced",
		'ACE1' =>	"Hard to Cover Basics Like Food or Housing",
		'ACE1R' => "Hard to Cover Basics Like Food or Housing"
		);
	}
}


?>