<?php

// auxiliar functions for the CLI test

// print help
function print_help() {
	global $argv;
	echo "\n./{$argv[0]} command args\n\n";
	echo "Commands:\n\n";
	echo "\tdescribe-plan [planname] [platform]\n";
	echo "\tdescribe-image [imageid]\n";
	echo "\tlist-instances\n";
	echo "\trun-instance [servername] [planname] [imageid] [serverqty]\n";
	echo "\tdescribe-instance [instanceid]\n";
	echo "\treboot-instance [instanceid] [reboottype]\n";
	echo "\tterminate-instance [instanceid]\n\n";
	die();
}

// prepares a string to be passed as an array with $argv[*]
function query_helper($string) {
	global $argv;
	$keys = explode(",",$string);
	if (!is_array($keys)) return false;
	$i = 1; $res = array();
	foreach ($keys as $v) {
		$i++;
		$res[$v] = $argv[$i];
	}
	return $res;
}
