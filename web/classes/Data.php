<?php

    require_once("Common.php");

	class Data 
	{
        public const DEFAULT_HOST = "127.0.0.1";
        public const DEFAULT_USER = "root";
        public const DEFAULT_PASSWORD = "erikado";
        public const DEFAULT_DATABASE = "final_project";


        //Fields
        private $_host = NULL;
        private $_user = NULL;
        private $_password = NULL;
        private $_database = NULL;

		function __construct()
		{ }
        
        //Properties
        public function getHost()
        {
            return $this->_host;
        }

        public function setHost($host)
        {
            $this->_host = $host;
            return $this;
        }

        public function getUser()
        {
            return $this->_user;
        }

        public function setUser($user)
        {
            $this->_user = $user;
            return $this;
        }

        public function getPassword()
        {
            return $this->_pasword;
        }

        public function setPassword($password)
        {
            $this->_password = $password;
            return $this;
        }

        public function getDatabase()
        {
            return $this->_database;
        }

        public function setDatabase($database)
        {
            $this->_database = $database;
            return $this;
        }

        //Methods
		
	}

?>