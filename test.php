<?php

// TEST
require_once("atlantic-api.php");
require_once("functions.php");

// ANC_ACCESS_KEY_ID
define("ATL_API_KEY","ATL9498i673c2a10987ce119028a9867e25");

// ANC_PRIVATE_KEY
define("ATL_API_PKEY","*******");

// API version
define("ATL_API_VER", "2010-12-30");

$a = new Atlantic();

if (!isset($argv[1])) print_help($argv[0]);

switch ($argv[1]) {
	case "describe-plan"     : $a->build_query(query_helper("planname,platform")); break;
	case "describe-image"    : $a->build_query(query_helper("imageid")); break;
	case "list-instances"	 : break;
	case "run-instance"      : $a->build_query(query_helper("servername,planname,imageid,serverqty")); break;
	case "describe-instance" : $a->build_query(query_helper("instanceid")); break;
	case "reboot-instance"   : $a->build_query(query_helper("instanceid,RebootType")); break;
	case "terminate-instance": $a->build_query(query_helper("instanceid")); break;
}

if (!$a->call_method($argv[1],$args)) print_help($argv[0]);

print_r($a->output());
