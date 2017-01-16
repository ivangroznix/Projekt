<?php

	# Stop Hacking attempt
    define('__APP__', TRUE);
	
	# Start session
    session_start();
    
    # Configuration file
    include_once("./config.php");
    
    # ---------------------------------------------------------------------------#
	# Connect to MySQL database
	# ---------------------------------------------------------------------------#
	$MySQL = mysqli_connect($conf['MySQL']['Host'],$conf['MySQL']['User'],$conf['MySQL']['Password'],$conf['MySQL']['Database'])
	or die('Error connecting to MySQL server.');
	
	# Redirect
    $redirect = "";
		
	# ---------------------------------------------------------------------------#
    # Registration
    if($_POST['_action_'] == "registration") {
		
		$hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        #password_hash https://secure.php.net/manual/en/function.password-hash.php
		
        copy($_FILES['profile']['tmp_name'], "userspic/".$_FILES['profile']['name']);
		
        $query  = "INSERT INTO users (user_firstname, user_lastname, user_picture, user_name, user_pass, user_email, user_country)";
        $query .= " VALUES ('" . $_POST['fname'] . "', '" . $_POST['lname'] . "', '" . $_FILES['profile']['name'] . "', '" . $_POST['username'] . "', '" . $hash . "', '" . $_POST['email'] . "', '" . $_POST['country'] . "')";
        $result = @mysqli_query($MySQL, $query);
        
        $ID = mysqli_insert_id();
        $_SESSION['message'] = '<p>Uspješno ste se registrirali!</p>';
        
        $redirect .= "index.php?menu=5";
    }
	
	# ---------------------------------------------------------------------------#
	# Sign Up
	else if($_POST['_action_'] == "sign_up") {
	    $_username = $_POST['username'];
		$_password = $_POST['password'];

		$query  = "SELECT * FROM users";
		$query .= " WHERE user_name='" .  $_username . "'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (password_verify($_password, $row['user_pass'])) {
		#password_verify https://secure.php.net/manual/en/function.password-verify.php
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['user_id'];
			$_SESSION['user']['name'] = $row['user_name'];
			$_SESSION['user']['pic'] = $row['user_picture'];
		    $_SESSION['message'] = '<p>Dobrodošli ' . $_SESSION['user']['name'] . '</p>';
			$redirect .= "index.php?menu=100";
		}
		
		# Bad username or password
		else {
			unset($_SESSION['user']);
			$_SESSION['user']['valid'] = 'false';
			$_SESSION['message'] = '<p>Upisali ste pogrešno korisničko ime ili lozinku</p>';
			$redirect .= "index.php?menu=5";
		}
		
	}
	
	# ---------------------------------------------------------------------------#
	# Edit user
	else if($_POST['_action_'] == "edit_user") {
		$query  = "UPDATE users SET user_firstname='" . $_POST['fname'] . "', user_lastname='" . $_POST['lname'] . "', user_name='" . $_POST['username'] . "', user_email='" . $_POST['email'] . "' , user_country='" . $_POST['country'] . "'";
        $query .= " WHERE user_id=" . (int)$_POST['user_id'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		$_SESSION['message'] = '<p>Uspješno ste izmjenili podatke korisnika!</p>';
		$redirect = "index.php?menu=100";
	}
	
	# Close MySQL connection
    @mysqli_close($mysql);
    
    # Redirect
    header("Location: " . $redirect);
    exit;