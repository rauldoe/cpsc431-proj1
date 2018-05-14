<?php

	class Common 
	{
        public const DEFAULT_HOST = "127.0.0.1";
        public const DEFAULT_USER = "default_app_user";
        public const DEFAULT_PASSWORD = "password";
        public const DEFAULT_DATABASE = "final_project";

		function __construct()
		{
        }
        
        //Methods
        public static function charFill($charToFill, $length)
        {
            return str_pad("",  $length, $charToFill);
        }
	}

?>