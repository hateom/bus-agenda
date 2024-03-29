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
	
        function checkBsDeletable($bs_id)
        {
            if( !$this->connect_db() ) return FALSE;
		$sql = 'SELECT DISTINCT(linie_id) FROM trasy WHERE przystanki_id = '.$bs_id.' ORDER BY linie_id ASC';
		$this->result = pg_query( $this->link, $sql );
                if( !$this->result )
                {
                    return FALSE;
                }
                $r_row = array();
                while( $row = pg_fetch_assoc( $this->result))
                {
                    $r_row[] = $row['linie_id'];
                }
                if (count($r_row)>0)
                {
                    echo '<div class="content"><p>Przystanek jest juz zwiazany z trasami:</p><ul>';
                    foreach($r_row as $row)
                    {
                        echo '<li>'.$row.'</li>';
                    }
					echo '</ul></div>';
                    return FALSE;
                }
		return TRUE;
        }
        
        function remove_street( $street_id )
	{
                 if( !$this->connect_db() ) return FALSE;
		$sql = 'SELECT nazwa FROM przystanki WHERE ulica1_id = '.$street_id.' OR ulica2_id ='.$street_id.' ORDER BY nazwa ASC';
		$this->result = pg_query( $this->link, $sql );
                if( !$this->result )
                {
                    return FALSE;
                }
                $r_row = array();
                while( $row = pg_fetch_assoc( $this->result))
                {
                    $r_row[] = $row['nazwa'];
                }
                if (count($r_row)>0)
                {
                    echo '<div class="content"><p>Ulica jest juz zwiazana z przystankami:</p><ul>';
                    foreach($r_row as $row)
                    {
                        echo '<li>'.$row.'</li>';
                    }
					echo '</ul></div>';
                    return FALSE;
                }
                $sql = 'DELETE FROM ulice_d WHERE id = '.$street_id;
                $this->result = pg_query( $this->link, $sql );
                if( !$this->result )
                {
                    return FALSE;
                }
		return TRUE;
	}
        
	function remove_bs( $bs_id )
	{
		if( !$this->checkBsDeletable($bs_id) )
                {
                    return FALSE;
                }
                #if( !$this->connect_db() ) return FALSE;
                $sql = 'DELETE FROM przystanki WHERE id = '.$bs_id;
                $this->result = pg_query( $this->link, $sql );
                if( !$this->result )
                {
                    return FALSE;
                }
		return TRUE;
	}
	
        function remove_departure( $departure_id )
        {
                if( !$this->connect_db() ) return FALSE;
                $sql = 'DELETE FROM odjazdy WHERE numer = '.$departure_id;
                $this->result = pg_query( $this->link, $sql );
                if( !$this->result )
                {
                    return FALSE;
                }
		return TRUE;
        }
        
	function remove_route( $route_id )
	{
                if( !$this->connect_db() ) return FALSE;
                $sql = 'DELETE FROM linie WHERE numer = '.$route_id;
                $this->result = pg_query( $this->link, $sql );
                if( !$this->result )
                {
                    return FALSE;
                }
		return TRUE;
	}
	
	function update_tt( $line, $reversed, $hour, $hour_id, $new_hour, $offset, $offset_id )
	{
        if( !$this->connect_db() ) return FALSE;
        $this->result = pg_query( $this->link, "begin" );
        if( !$this->result ) return FALSE;
        
        for($i=0;$i<count($hour);$i++)
        {
            $sql = 'UPDATE odjazdy SET "godzina" = \''.$hour[$i].'\' WHERE odjazdy.id = '.$hour_id[$i];
            $this->result = pg_query( $this->link, $sql );
            if( !$this->result )
            {
                echo $sql.' failed!';
                pg_query( $this->link, "rollback" );
                return FALSE;
            }
        }
	if(count($new_hour) > 0){
        foreach($new_hour as $nh)
        {
            $sql = 'INSERT INTO odjazdy("linie_id", "kierunek", "godzina") VALUES(\''.$line.'\',\''.$reversed.'\',\''.$nh.'\')';
            $this->result = pg_query( $this->link, $sql );
            if( !$this->result )
            {
                echo $sql.' failed!';
                pg_query( $this->link, "rollback" );
                return FALSE;
            }
        }
	}
        $this->result = pg_query( $this->link, "commit" );
        if(!$this->result) return FALSE;
        
        $this->result = pg_query( $this->link, "begin" );
        if( !$this->result ) return FALSE;
        
        for($i=0;$i<count($offset);$i++)
        {
            $sql = 'UPDATE przesuniecia SET "offset" = \''.$offset[$i].'\' WHERE przesuniecia.id = '.$offset_id[$i];
            $this->result = pg_query( $this->link, $sql );
            if( !$this->result )
            {
                echo $sql.' failed!';
                pg_query( $this->link, "rollback" );
                return FALSE;
            }
        }
        
        $this->result = pg_query( $this->link, "commit" );
        if(!$this->result) return FALSE;
		return TRUE;
	}

    function save_route( $line, $desc, $route )
	{
		if( !$this->connect_db() ) return FALSE;
		
        $this->result = pg_query( $this->link, "begin" );
        if( !$this->result ) return FALSE;
        $sql = 'INSERT INTO "linie"("numer","typ","opis") VALUES (\''.$line.'\', \'0\', \''.$desc.'\')';
        $this->result = pg_query( $this->link, $sql );
        if( !$this->result ) 
        {
            $res = pg_query( $this->link, "rollback" );
            return FALSE;
        }
        for($i=0;$i<count($route);$i++)
        {
            $sql = 'INSERT INTO "trasy"("linie_id", "przystanki_id", "numer_kolejny") VALUES (\''.$line.'\', \''. $route[$i] .'\',\''. $i.'\')';
            $res = pg_query( $this->link, $sql );
            if (!$res) 
            {
                $res = pg_query( $this->link, "rollback" );
                return FALSE;
            }
        }
        
        $sql = 'SELECT id FROM trasy WHERE linie_id = '.$line.' ORDER BY numer_kolejny ASC';
        $this->result = pg_query( $this->link, $sql );
        if(!$this->result)
        {
            $this->result = pg_query( $this->link, "rollback" );
            return FALSE;
        }
        $r_row = array();
        while( $row = pg_fetch_assoc( $this->result)) {
		$r_row[] = $row['id'];
        }
        for($i=0;$i<count($route);$i++)
        {
            $sql = 'INSERT INTO "przesuniecia"("offset", "trasy_id", "powrotna") VALUES (\'00:00\','.$r_row[$i].',\'0\')';
            $this->result = pg_query( $this->link, $sql );
        if(!$this->result)
        {
            $this->result = pg_query( $this->link, "rollback" );
            echo $sql.' failed';
	    return FALSE;
        }
            $sql = 'INSERT INTO "przesuniecia"("offset", "trasy_id", "powrotna") VALUES (\'00:00\','.$r_row[$i].',\'1\')';
            $this->result = pg_query( $this->link, $sql );
        if(!$this->result)
        {
            $this->result = pg_query( $this->link, "rollback" );
            return FALSE;
        }
        }
        
        $this->result = pg_query( $this->link, "commit" );
        if( !$this->result ) return FALSE;

        return TRUE;	
	}
	
	function save_bs( $name, $street1, $street2 )
	{
		if( !$this->connect_db() ) return FALSE;

		$sql = 'INSERT INTO "przystanki"("nazwa", "ulica1_id", "ulica2_id") VALUES (\''.$name.'\', \''. $street1 .'\',\''. $street2.'\')';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;

        return TRUE;		
	}
	
	function save_street( $name )
	{
		if( !$this->connect_db() ) return FALSE;

		$sql = 'INSERT INTO ulice_d("nazwa") VALUES(\''.$name.'\')';
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;

        return TRUE;		
	}
	
	function update_street( $id, $name )
	{
		if( !$this->connect_db() ) return FALSE;

		$sql = 'UPDATE ulice_d SET "nazwa" = \''.$name.'\' WHERE id = '.$id;
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;

        return TRUE;		
	}
	
	function line_exists( $line )
    {
        if( !$this->connect_db() ) return FALSE;
        $sql = 'SELECT COUNT(*) AS "ile" FROM "linie" WHERE "numer" = '.$line;
        $this->result = pg_query( $this->link, $sql );
    
	    if( !$this->result ) return FALSE;
		
		$row = pg_fetch_assoc( $this->result );
    
	    return $row['ile'] === "1";
    }
	
	function bs_exists( $bs )
    {
        if( !$this->connect_db() ) return FALSE;
        $sql = 'SELECT COUNT(*) AS "ile" FROM "przystanki" WHERE "nazwa" = \''.$bs.'\'';
        $this->result = pg_query( $this->link, $sql );
    
	    if( !$this->result ) return FALSE;
		
		$row = pg_fetch_assoc( $this->result );
    
	    return $row['ile'] === "1";
    }
	
	function update_bs( $bs_id, $name, $street1, $street2 )
	{
		if( !$this->connect_db() ) return FALSE;

		$sql = 'UPDATE przystanki SET "nazwa" = \''.$name.'\', ulica1_id = '.$street1.', ulica2_id = '.$street2.' WHERE id = '.$bs_id;
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;

        return TRUE;		
	}
	
	function update_route( $line, $newline, $desc, $rroute )
	{
		if( !$this->connect_db() ) return FALSE;
        $this->result = pg_query( $this->link, "begin" );
        if( !$this->result ) return FALSE;
        
        $sql = 'SELECT id FROM trasy WHERE linie_id = '.$line.' ORDER BY numer_kolejny ASC';
	$this->result = pg_query($this->link, $sql);
        if(!$this->result)
             {
	     echo $sql.' failed';
                pg_query( $this->link, "rollback" );
                return FALSE;
             }

        $r_row = array();
        while( $row = pg_fetch_assoc( $this->result)) {
		$r_row[] = $row['id'];
        }
     
        if($line != $newline)
        {
             $sql = 'UPDATE linie SET "numer" = \''.$newline.'\' WHERE numer = \''.$line.'\'';
	     $this->result = pg_query($this->link, $sql);
             if(!$this->result)
             {
                pg_query( $this->link, "rollback" );
		echo $sql.' Failed';
                return FALSE;
             }
        }
        for( $i=0; $i<count($r_row); $i++)
        {
             $sql = 'UPDATE trasy SET "przystanki_id" = \''.$rroute[$i].'\' WHERE id = \''.$r_row[$i].'\'';
             $this->result = pg_query($this->link, $sql);
             if(!$this->result)
             {
	     	echo $sql.' failed ';
                pg_query( $this->link, "rollback" );
                return FALSE;
             }
        }
        // wstawienie nowych 
        $count = count($r_row);
        if($count != count($rroute))
        {
	echo 'jesrt diff'.$count.' '.count($rroute);
            for($i=$count;$i<count($rroute);$i++)
            {
                $sql = 'INSERT INTO "trasy"("linie_id", "przystanki_id", "numer_kolejny") VALUES (\''.$newline.'\', \''. $rroute[$i] .'\',\''. $i.'\')';
                $res = pg_query( $this->link, $sql );
                if (!$res) 
                {
                    $res = pg_query( $this->link, "rollback" );
                    return FALSE;
                }
		echo 'nowa trasa';
            }
            //odszukanie nowych ID tras
	//    pg_query($this->link,"commit");
	  //  pg_query($this->link,"begin");
            $sql = 'SELECT id FROM trasy WHERE linie_id = '.$line.' ORDER BY numer_kolejny DESC LIMIT '.(count($rroute) - $count);
            $this->result = pg_query( $this->link, $sql );
	    if(!$res)
	    {
	    	pg_query($this->link,"rollback");
		echo $sql.' failed';
		return FALSE;
	    }
            $r_row = array();
            while( $row = pg_fetch_assoc( $this->result)) {
                $r_row[] = $row['id'];
            }
        //dodanie pustych offsetow
            for($i=0;$i<count($r_row);$i++)
        {
            $sql = 'INSERT INTO "przesuniecia"("offset", "trasy_id", "powrotna") VALUES (\'00:00\','.$r_row[$i].',\'0\')';
            $this->result = pg_query( $this->link, $sql );
        if(!$this->result)
        {
            $this->result = pg_query( $this->link, "rollback" );
            return FALSE;
        }
            $sql = 'INSERT INTO "przesuniecia"("offset", "trasy_id", "powrotna") VALUES (\'00:00\','.$r_row[$i].',\'1\')';
            $this->result = pg_query( $this->link, $sql );
        if(!$this->result)
        {
            $this->result = pg_query( $this->link, "rollback" );
            return FALSE;
        }
        }
        }
        $this->result = pg_query( $this->link, "commit" );
        if( !$this->result ) return FALSE;

        return TRUE;		
	}
    function get_line_desc( $line_number ) 
    {
             if( !$this->connect_db() ) return FALSE;

        $sql = 'SELECT opis FROM "public"."linie" WHERE numer ='. $line_number;
        $this->result = pg_query( $this->link, $sql );
		
        if( !$this->result ) return FALSE;
		
		$row = pg_fetch_assoc( $this->result );
        return $row['opis'];   
    }
	function read_offset($line, $reverse)
    {
        if( !$this->connect_db() ) return FALSE;

        if( $reverse == '0' )
        {
            $sortOrder = 'ASC';
        }
        else
        {
         	$sortOrder = 'DESC';
        }
		
        $sql = 'SELECT przesuniecia.id AS offset_id, przystanki.nazwa AS nazwa, przesuniecia.offset FROM przesuniecia LEFT JOIN trasy ON trasy.id = przesuniecia.trasy_id LEFT JOIN przystanki ON przystanki.id = trasy.przystanki_id WHERE trasy.linie_id = '. $line .' AND przesuniecia.powrotna = \''.$reverse.'\' ORDER BY numer_kolejny '. $sortOrder;
        $this->result = pg_query( $this->link, $sql );
	
        if( !$this->result ) return FALSE;
		
		$offset = array();
		while( $row = pg_fetch_assoc( $this->result ) )
		{
			$offset[] = $row;
		}
		
        return $offset;
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

    function read_table( $line, $reverse )
    {
        if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT id, godzina FROM odjazdy WHERE linie_id = '.$line.' AND kierunek = \''.$reverse.'\'';

        $this->result = pg_query( $this->link, $sql );
    
        if( !$this->result ) return FALSE;
		
		return TRUE;
    }
	
	function next_table()
    {
        if( !$this->result ) return FALSE;

        $row = pg_fetch_assoc( $this->result );
        return $row;
    }

    function read_ttable( $line, $bs, $reverse )
    {
        if( !$this->connect_db() ) return FALSE;

        $sql    = 'SELECT * FROM timetable_view WHERE linia_numer = '.$line .' AND przystanek_id = '.$bs .' AND kierunek = \''.$reverse.'\' ORDER BY odj ASC';
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
    
    function get_street_name($street_id)
    {
        if( !$this->connect_db() ) return FALSE;

        $sql = 'SELECT nazwa FROM "public"."ulice_d" WHERE id ='. $street_id;
        $this->result = pg_query( $this->link, $sql );
		
        if( !$this->result ) return FALSE;
		
		$row = pg_fetch_assoc( $this->result );
        return $row['nazwa'];
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

	function free_result()
	{
		pg_free_result( $this->result );
		$this->result = 0;
	}
	
};

?>
