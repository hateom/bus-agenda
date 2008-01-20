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

    function save_route( $line, $desc, $route )
	{/*
		if( !$this->connect_db() ) return FALSE;

		$sql = '';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;*/

        return TRUE;		
	}
	
	function save_bs( $name, $street1, $street2 )
	{/*
		if( !$this->connect_db() ) return FALSE;

		$sql = '';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;*/

        return TRUE;		
	}
	
	function save_street( $name )
	{/*
		if( !$this->connect_db() ) return FALSE;

		$sql = '';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;*/

        return TRUE;		
	}
	
	function update_street( $id, $name )
	{/*
		if( !$this->connect_db() ) return FALSE;

		$sql = '';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;*/

        return TRUE;		
	}
	
	function update_bs( $bs_id, $name, $street1, $street2 )
	{/*
		if( !$this->connect_db() ) return FALSE;

		$sql = '';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;*/

        return TRUE;		
	}
	
	function update_route( $line, $newline, $desc, $rroute )
	{/*
		if( !$this->connect_db() ) return FALSE;

		$sql = '';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;*/

        return TRUE;		
	}

    function read_route($line, $reverse)
	{
		if( !$this->connect_db() ) return FALSE;

        if( $reverse == FALSE ) {
            $sql    = 'SELECT * FROM "public"."trasy_view" WHERE "numer" = ' . $line .'ORDER BY "numer_kolejny"';
        } else {
            $sql    = 'SELECT * FROM "public"."trasy_view" WHERE "numer" = ' . $line .'ORDER BY "numer_kolejny" DESC';
        }
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;

        return TRUE;
	}

	function next_route()
	{
	    if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
	}

    function read_direction( $line )
    {
        if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT przystanki_id FROM (SELECT przystanki_id, numer_kolejny FROM trasy WHERE linie_id = '.$line.') AS kol where numer_kolejny=0 OR numer_kolejny = (SELECT numer_kolejny FROM trasy WHERE trasy.linie_id ='. $line .'ORDER BY numer_kolejny DESC LIMIT 1)';
        $this->result = pg_query( $this->link, $sql );
    
        if( !$this->result ) return FALSE;
        $row1 = pg_fetch_assoc( $this->result );
        $row2 = pg_fetch_assoc( $this->result );

		if( !$row1 || !$row2 ) {
			return FALSE;
		}

        $row['first'] = $row1['przystanki_id'];
        $row['last']  = $row2['przystanki_id'];

        return $row;
    }

    function read_bs_info( $bs )
    {
        if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT DISTINCT linie_id FROM "trasy" WHERE trasy.przystanki_id = ' .$bs;
        $this->result = pg_query( $this->link, $sql );
    
        if( !$this->result ) return FALSE;

        return TRUE;
    }

    function next_bs_info()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }

    function find_route( $from, $to, $time )
    {
        /*if( !$this->connect_db() ) return FALSE;

        $sql    = '';
        $this->result = pg_query( $this->link, $sql );
    
        if( !$this->result ) return FALSE;*/

        return TRUE;
    }

    function next_find_route()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }

    function read_ttable( $line, $bs, $reverse )
    {
        if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM timetable_view WHERE linia_numer = '.$line .' AND przystanek_id = '.$bs .' AND kierunek = \''.$reverse.'\' ORDER BY odj ASC';
        echo $sql;
        $this->result = pg_query( $this->link, $sql );
    
        if( !$this->result ) return FALSE;

        return TRUE;
    }

    function next_ttable()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }

    function read_lines()
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM "public"."linie" ORDER BY "numer"';
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

    function get_bs_name( $bs_id ) 
    {
        if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT nazwa FROM "public"."przystanki" WHERE "id"='.$bs_id.' LIMIT 1';
        $this->result = pg_query( $this->link, $sql );
        
        if( !$this->result ) return FALSE;
        $row = pg_fetch_assoc( $this->result );

        return $row['nazwa'];
    }

    function read_bs_id( $id )
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT id, przystanki.nazwa, ulica1_id, (SELECT ulice_d.nazwa FROM ulice_d WHERE ulice_d.id = ulica1_id) AS ulica1, ulica2_id, (SELECT ulice_d.nazwa FROM ulice_d WHERE ulice_d.id = ulica2_id) AS ulica2 FROM "public"."przystanki" WHERE przystanki.id='.$id.'ORDER BY "nazwa"';
        $this->result = pg_query( $this->link, $sql );
		
        if( !$this->result ) return FALSE;

		$row = pg_fetch_assoc( $this->result );
        return $row;
    }
	
	function read_bs()
    {
		if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT id, przystanki.nazwa, ulica1_id, (SELECT ulice_d.nazwa FROM ulice_d WHERE ulice_d.id = ulica1_id) AS ulica1, ulica2_id, (SELECT ulice_d.nazwa FROM ulice_d WHERE ulice_d.id = ulica2_id) AS ulica2 FROM "public"."przystanki" ORDER BY "nazwa";';
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

        $sql    = 'SELECT * FROM "public"."ulice_d" ORDER BY "nazwa"';
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
