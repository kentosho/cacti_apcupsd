<?php

/*	Name:		apcupsd query 
	Created by:	BSOD2600
	Version:	1.0	
	Summary:	This script is designed to run against a host running the APC Daemon, Apcupsd,
				with the NIS (Network Information Server) feature enabled.  Furthermore, it
				utlizes the 'apcaccess status' command to remotely query a APC status.  This 
				is done primarely for the older APC devices which do not support SNMP.  The
				script will still work for the SNMP enabled devices, but will be a little slower.
				
	Installation:
				Modify the APCACCESS_PATH variable to reflect the actual path where apcaccess lives.
				Manually run the script once to verify it can retrieve the data from the remote PC.
				Example: php query_apcupsd myserver 3551
						
				
				Note: Windows users *MUST* use the 8.3 file path!
 */

$APCACCESS_PATH = "/sbin/";

/* **** DO NOT EDIT BELOW THIS LINE ****************************************************************** */

/* do NOT run this script through a web browser */
if (!isset($_SERVER["argv"][0]) || isset($_SERVER['REQUEST_METHOD'])  || isset($_SERVER['REMOTE_ADDR'])) {
	die("<br><strong>This script is only meant to run at the command line.</strong>");
}

$no_http_headers = true;

/* display No errors */
error_reporting(0);

ArgCheck();

function ArgCheck()
{
	if ($_SERVER["argc"] != 3)
	{
		fwrite(STDOUT,
		"\nUsage: query_apcupsd HOST PORT
		  HOST \t Hostname/IP of system running apcupsd
		  PORT \t Port number of NIS (default is 3551)\n"); 
	}
	else
	{
		apcupsd_query ($_SERVER["argv"][1], $_SERVER["argv"][2]);
	}			
}

function apcupsd_query($hostname, $port)
{
	/* Might need to tweak to make it work on Linux */
	$exe = "apcaccess";
	$command = " status " . $hostname . ":" . $port;
	
	$output = shell_exec($GLOBALS['APCACCESS_PATH'] . $exe . $command);
	apcupsd_parse($output);
}

function apcupsd_parse($input)
{
	$result = "";
	$lines = explode (PHP_EOL, $input);

	foreach($lines as $line) 
	{
		/* Basic APC parameters */
		if (preg_match("/^LINEV\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("LINEV", $matches[1]);			
		elseif (preg_match("/^LOADPCT\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("LOADPCT", $matches[1]);
		elseif (preg_match("/^BCHARGE\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("BCHARGE", $matches[1]);
		elseif (preg_match("/^TIMELEFT\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("TIMELEFT", $matches[1]);
		elseif (preg_match("/^BATTV\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("BATTV", $matches[1]);
		elseif (preg_match("/^NOMINV\s+:\s+(\d+)/", $line, $matches))
			$result .= parse_output("NOMINV", $matches[1]);
		elseif (preg_match("/^NOMBATTV\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("NOMBATTV", $matches[1]);
		elseif (preg_match("/^TONBATT\s+:\s+(\d+)/", $line, $matches))
			$result .= parse_output("TONBATT", $matches[1]);
		
		/* Advanced APC parameters */
		if (preg_match("/^MAXLINEV\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("MAXLINEV", $matches[1]);
		elseif (preg_match("/^MINLINEV\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("MINLINEV", $matches[1]);
		elseif (preg_match("/^ITEMP\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("ITEMP", $matches[1]);
		elseif (preg_match("/^LINEFREQ\s+:\s+(\d+\.\d+)/", $line, $matches))
			$result .= parse_output("LINEFREQ", $matches[1]);
	}
	
	print(trim($result));	
}

function parse_output($name, $data)
{
	return is_numeric($data) ? ($name . ":" . $data . " ") : ($name . ":NaN ");
}

?>
