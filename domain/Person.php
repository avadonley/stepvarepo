<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHC-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>, Sam Roberts, Allen Tucker
 * @version 3/28/2008, revised 7/1/2015
 */

// ONLY REQUIRED FIELDS HAVE BEEN ADDED SO FAR.
class Person {
	private $access_level; // normal user = 1, admin = 2, superadmin = 3
	// required fields
	private $id;
	private $username;
	private $first_name;
	private $last_name;
	private $birthday;
	private $street_address;
	private $city;
	private $state;
	private $zip;
	private $email;
	private $phone;
	private $phone_type;
	private $econtact_first_name;
	private $econtact_last_name;
	private $econtact_phone;
	private $econtact_relation;
	private $tshirt_size;
	private $school_affiliation;
	private $password;
	//private $volunteer_or_participant;
	private $type;
	private $status;

	// optional fields
	// (to be added...)

	/*
	 * This is a temporary mini constructor for testing purposes. It will be expanded later.
	 */
	function __construct($id, $first_name, $last_name,
						$birthday, $email, $password, $username, $street_address, $city, $state, $zip,
						$phone, $phone_type, $econtact_first_name, $econtact_phone,
						$econtact_relation, $type,
						$status
						) {
		$this->id = $id;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->birthday = $birthday;
		$this->email = $email;
		$this->password = $password;
		$this->username = $username;
		$this->street_address = $street_address;
		$this->city = $city;
		$this->state = $state;
		$this->zip = $zip;
		$this->phone = $phone;
		$this->phone_type = $phone_type;
		$this->econtact_first_name = $econtact_first_name;
		//$this->econtact_last_name = $econtact_last_name;
		$this->econtact_phone = $econtact_phone;
		$this->econtact_relation = $econtact_relation;
		//$this->tshirt_size = $tshirt_size;
		//$this->school_affiliation = $school_affiliation;
		//$this->volunteer_or_participant = $volunteer_or_participant;
		$this->type = $type;
		$this->status = $status;

		// access_level = 1 for users, and = 3 for admin
		if ($id == 'vmsroot') {
			$this->access_level = 3;
		} else {
			$this->access_level = 1;
		}
	}

	function get_access_level() {
		return $this->access_level;
	}

	function get_id() {
		return $this->id;
	}

	function get_first_name() {
		return $this->first_name;
	}

	function get_last_name() {
		return $this->last_name;
	}

	function get_birthday() {
		return $this->birthday;
	}

	function get_email() {
		return $this->email;
	}

	function get_password() {
		return $this->password;
	}

	function get_username() {
		return $this->username;
	}

	//function get_phone() {
	//	return $this->phone;
	//}
	function get_phone1() {
		return $this->phone;
	}

	function get_zip() {
		return $this->zip;
	}

	//function get_volunteer_or_participant() {
	//	return $this->volunteer_or_participant;
	//}

	function get_type() {
		return $this->type;
	}

	function get_status() {
		return $this->status;
	}
	
	function get_street_address() {
		return $this->street_address;
	}

}