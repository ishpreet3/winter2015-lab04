<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class install_model extends Application{
	
	public function use_sql_string($sql)
	{
            $host = $this->db->hostname;
            $user = $this->db->username;
            $password = $this->db->password;
            $db = $this->db->database;
            //connect and run the sql query
            $con=mysqli_connect($host,$user,$password,$db);
	     mysqli_multi_query($con, $sql);		
	}
}	
?>