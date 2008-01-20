<?php

	require_once('view_base.php');

	class browser extends view_base
	{
		function browser()
		{
		}

		function validate_time( $time )
		{
			return preg_match( "/^\d{1,2}\:\d{2}$/i", $time );
		}

		function show_connections( $post, $db, $smarty )
		{
			if( isset( $post['search'] ) )
	        {
                $from = $post['from'];
       	        $to   = $post['to'];
           	    $time = $post['time'];
				
				$smarty->assign( 'from', $db->get_bs_name( $from ) );
                $smarty->assign( 'to',   $db->get_bs_name( $to ) );
                
       	        if( $from == $to ) {
               	    parent::err("Przystanek poczatkowy jest taki sam jak docelowy!");
	            } else if( !validate_time( $time ) ) {
                    parent::err("Godzina musi byc w formacie HH:MM");
       	        } else {
           	        if( !($r_route = find_route( $db, $from, $to, $time )) ) {
               	        parent::err("Nie mozna odnalezc zadanej trasy!");
                   	} else {
	                    $smarty->assign( 'r_route', $r_route );
                        $smarty->assign( 'time', $time );
           	            $smarty->display( 'conn_result.tpl' );
       	            }
               	}
	         }

            if( !($bs = read_bs( $db )) ) {
       	        parent::err("Nie mozna odczytac przytsanku!");
           	} else {
	            $smarty->assign( 'bs', $bs );
                $smarty->assign( 'time', date('G:i') );
       	        $smarty->display( 'conn_form.tpl' );
	        }
		}
		
		function show_lines( $db, $smarty )
		{
			if( !($lines = read_lines( $db )) ) {
				parent::err("Nie mozna odczytac lini!");
	        } else {
   		        $smarty->assign( 'lines', $lines );
       		    $smarty->display( 'lines.tpl' );
        	}
		}
		
		function show_bs( $db, $smarty )
		{
			if( !($bs = read_bs( $db )) ) {
				parent::err("Nie mozna odczytac przytsanku!");
			} else {
				$smarty->assign( 'bs', $bs );
				$smarty->display( 'stops.tpl' );
			}
		}
		
		function show_line( $l, $get, $post, $db, $smarty )
		{
			if( isset( $get['reverse'] )) {
        		$reverse = TRUE;
				$smarty->assign( 'rev', 1 );
			} else {
				$reverse = FALSE;
				$smarty->assign( 'rev', 0 );
			}

			if( !($route = read_route( $db, $l, $reverse ) )) {
            	parent::err("Nie mozna odczytac trasy!");
				return FALSE;
			} 
			
            if( isset( $get['tt'] ) ) {
                $bs = $get['tt'];
	            if( !($ttable = read_ttable( $db, $l, $bs, $reverse )) ) {
    	            parent::err("Nie mozna odczytac rozkladu jazdy!");
					return FALSE;
        	    } else {
            	    $smarty->assign( 'ttable', $ttable );
                }
	        }

            if( !($dir = $db->read_direction($l) )) {
				parent::err("Nie mozna odczytac trasy!");
				return FALSE;
			}
			
       	    $first = $db->get_bs_name( $dir['first'] );
   	        $last = $db->get_bs_name( $dir['last'] );

			if( isset($bs) ) {
   	        	$smarty->assign( 'tt', 1 );
        	    $smarty->assign( 'bs', $db->get_bs_name($bs) );
			} else {
	            $smarty->assign( 'tt', 0 );
			}

           	$smarty->assign( 'first', $first );
       	    $smarty->assign( 'last', $last );
   	        $smarty->assign( 'line', $l );
            $smarty->assign( 'route', $route );
           	$smarty->display( 'route.tpl' );

			return TRUE;
		}
		
		function show_bs_info( $bs, $db, $smarty )
		{
    	    if( !($bs_info = read_bs_info( $db, $bs ) )) {
        	    parent::err("Nie mozna odczytac informacji o przystanku!");
	        } else {
    	        $smarty->assign( 'bs_info', $bs_info );
        	    $smarty->assign( 'bs', $db->get_bs_name( $bs ) );
            	$smarty->display( 'bs_info.tpl' );
	        }
		}
		
		function execute( $get, $post, $db, $smarty )
		{
			if( isset( $get['i'] )) {
				$i = $get['i'];
        		if( $i == 'conn' ) {
    	        	$this->show_connections( $post, $db, $smarty );
	   		    } else if( $i == 'line' ) {
       			    $this->show_lines( $db, $smarty );
        		} else if( $i == 'bs' ) {
					$this->show_bs( $db, $smarty );
		        }
			} else if( isset( $get['l'] ) ) {
				$l = $get['l'];
		        $this->show_line( $l, $get, $post, $db, $smarty );
			} else if( isset( $get['b'] ) ) {
				$bs = $_GET['b'];
				$this->show_bs_info( $bs, $db, $smarty );
			}
			
			return TRUE;
		}
	};

?>