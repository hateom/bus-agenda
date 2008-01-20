<?php

	class view_base
	{
		var $is_err;
		var $error;
		var $is_ntf;
		var $notify;
		
		function browser()
		{
			$this->is_err = FALSE;
			$this->error = "";
		}
		
		function err( $e )
		{
			$this->error .= "<li>" .$e. "</li>";
			$this->is_err = TRUE;
		}
		
		function get_error()
		{
			return "<ol>" . $this->error . "</ol>";
		}
		
		function is_error()
		{
			return $this->is_err;
		}
		
		function ntf( $n )
		{
			$this->notify .= "<li>" .$n. "</li>";
			$this->is_ntf = TRUE;
		}
		
		function get_notify()
		{
			return "<ol>" . $this->notify . "</ol>";
		}
		
		function is_notify()
		{
			return $this->is_ntf;
		}
	};

?>