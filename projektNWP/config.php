<?php
	# Stop Hacking attempt
	if(!defined('__APP__')) {
		die("Hacking attempt");
	}
	
	# Work MySQL Server configuration
	$conf['MySQL']    = array(
		"Host"             => "localhost",              # MySQL hostname or IP address
		"User"             => "root",                   # MySQL user
		"Password"         => "",                       # MySQL password
		"Database"         => "projekt",                # MySQL database
	);