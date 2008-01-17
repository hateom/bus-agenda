<?php

require_once('config.php');

class dbdriver
{
	var $link;
	var $result;
	
	function dbdriver()
	{
		$this->link = 0;
		$this->result = 0;
	}
	
	function connect_db()
	{
		global $db_host;
		global $db_user;
		global $db_pass;
		global $db_name;
		
		if( $this->link != 0 ) return TRUE;
		
		$this->link = pg_connect( "host=${db_host} dbname=${db_name} user=${db_user} password=${db_pass}" );
		if( !$this->link ) {
			return FALSE;
		}

		return TRUE;
	}
	
	function release()
	{
		if( $this->link == 0 ) return TRUE;
		
		pg_close( $this->link );
		$this->link = 0;
	}

    function test()
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM "public"."linie"';
        $result = pg_query( $this->link, $sql );
		
        return $result;
    }

    function read_lines()
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM "public"."linie"';
        $this->result = pg_query( $this->link, $sql );
		
        if( !$this->result ) return FALSE;

        return TRUE;
    }

    function next_line()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }

    function read_bs()
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM "public"."przystanki"';
        $this->result = pg_query( $this->link, $sql );
		
        if( !$this->result ) return FALSE;

        return TRUE;
    }

    function next_bs()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }

    function read_streets()
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM "public"."ulice_d"';
        $this->result = pg_query( $this->link, $sql );
		
        if( !$this->result ) return FALSE;

        return TRUE;
    }

    function next_street()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }


/*
	function foo()
	{
		if( !$this->connect_db() ) return FALSE;
		
		if( $type == "SENT" ) {
			$id = 1;
		} else {
			$id = 2;
		}
		
        $sql    = "INSERT INTO `iMessage` (`id` ,`sender` ,`date` ,`time`, `type` ,`content` ) ";
        $result = pg_query( $sql, $this->link );
		
		return $result;
	}
*/	
	function free_result()
	{
		pg_free_result( $this->result );
		$this->result = 0;
	}
	
};

?>
