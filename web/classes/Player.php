<?php
	require_once("functions/setup_DB.php");

	//Player class
	class Player 
	{
		private $fname;
		private $lname;
		private $street;
		private $city;
		private $state;
		private $country;
		private $zipcode;
		private $is_active;

		function __construct($fname, $lname, $street, $city, $state, $country, $zipcode, $is_active=false)
		{
			$this->fname = $fname;
			$this->lname = $lname;
			$this->street = $street;
			$this->city = $city;
			$this->state = $state; 
			$this->country = $country;
			$this->zipcode = $zipcode;
			$this->is_active = $is_active;
		}

		function name()
		{
			// string name()
			if( func_num_args() == 0 )
			{
				return $this->lname.", ".$this->fname;
			}
			
			// void name($lname, $fname)
			else if ( func_num_args() == 2 )
			{
				$this->name = func_get_arg(0).", ".func_get_arg(1);
			}

			return $this;
		}

		function street()
		{
			// string street()
			if( func_num_args() == 0 )
			{
				return $this->street;
			}
			
			// void street($value)
			else if( func_num_args() == 1 )
			{
				$this->street = func_get_arg(0);
			}

			return $this;
		}

		function city()
		{
			// string city()
			if( func_num_args() == 0 )
			{
				return $this->city;
			}
			
			// void city($value)
			else if( func_num_args() == 1 )
			{
				$this->city = func_get_arg(0);
			}

			return $this;
		}

		function state()
		{
			// string state()
			if( func_num_args() == 0 )
			{
				return $this->state;
			}
			
			// void state($value)
			else if( func_num_args() == 1 )
			{
				$this->state = func_get_arg(0);
			}

			return $this;
		}

		function zipcode()
		{
			// string zipcode()
			if( func_num_args() == 0 )
			{
				return $this->zipcode;
			}
			
			// void zipcode($value)
			else if( func_num_args() == 1 )
			{
				$this->zipcode = func_get_arg(0);
			}

			return $this;
		}

		function country()
		{
			// string country()
			if( func_num_args() == 0 )
			{
				return $this->country;
			}
			
			// void country($value)
			else if( func_num_args() == 1 )
			{
				$this->country = func_get_arg(0);
			}

			return $this;
		}

		function is_active()
		{
			return $this->is_active;
		}

		function get_full_address()
		{
			$full_address = $this->street."<br/>".$this->city.", ".$this->state." ".$this->zipcode."<br/>".$this->country;
			return $full_address; 
		}

		function __toString()
		{
			return (var_export($this, true));
		}
	}

	

?>