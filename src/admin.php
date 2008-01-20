<?php

	require_once('view_base.php');

	class admin extends view_base
	{
		function admin()
		{
		
		}
		
		function manage_streets( $post, $db, $smarty )
		{
			if( isset( $post['add'] ) ) {
				$ns = $post['add'];
				if( $ns === "" ) {
					parent::err("Nazwa ulicy jest pusta!");
				} else {
					if( !$db->save_street( $ns ) ) {
						parent::err("Nie można zapisać ulicy!");
					} else {
						parent::ntf("Dodano nową ulicę!");
					}
				}
			} else if( isset( $post['name']) ) {
				$nn = $post['name'];
				$id = $post['id'];
				if( !$db->update_street( $id, $nn ) ) {
					parent::err("Nie można zmienić ulicy!");
				} else {
					parent::ntf("Ulica ostała zaktualizowana!");
				}
			}

			if( !($str = read_streets( $db ))) {
				parent::err("Nie można odczytać ulic!");
				return FALSE;
			}
			
			$smarty->assign( 'streets', $str );
			$smarty->display( 'manage_s.tpl' );
			
			return TRUE;
		}
		
		function manage_bs( $get, $post, $db, $smarty )
		{
			if( isset( $post['street1'] ) ) {
				$s1   = $post['street1'];
				$s2   = $post['street2'];
				$name = $post['name'];
					
				// update
				if( isset( $get['u'] ) ) {
					$bs_id = $post['bs_id'];
					if( !$db->update_bs( $bs_id, $name, $s1, $s2 ) ) {
						parent::err("Nie można zapisać zmian w przystanku!");
					} else {
						parent::ntf("Zmieniono nazwę przystanku!");
					}
				} else { // insert new
					if( !$db->save_bs( $name, $s1, $s2 ) ) {
						parent::err("Nie można zapisać nowego przystanku!");
					} else {
						parent::ntf("Nowy przystanek został dodany!");
					}
				}
			}
			
			if( isset( $get['e'] )) {
				if( !($str = read_streets( $db ))) {
					parent::err("Nie można odczytać ulic!");
					return FALSE;
				}
			
			    $bs = $get['e'];
				$row = $db->read_bs_id( $bs );
				$smarty->assign( 'bs', $row['nazwa'] );
				$smarty->assign( 'bs_id', $bs );
				$smarty->assign( 'street1', $row['ulica1'] );
				$smarty->assign( 'street2', $row['ulica2'] );
				$smarty->assign( 'street1_id', $row['ulica1_id'] );
				$smarty->assign( 'street2_id', $row['ulica2_id'] );
				$smarty->assign( 'streets', $str );
				$smarty->display( 'edit_bs.tpl' );
			} else if( isset( $post['add'] ) ) {
                $nbs = $post['add'];
                if( $nbs === "" ) {
                    $error = "Nazwa nie może być pusta!";
                } else if( $db->bs_exists( $nbs ) ) {
					$error = "Przystanek o takiej nazwie już istnieje!";
				} else {
                    $smarty->assign( 'bs', $nbs );
					$smarty->assign( 'streets', $str );
					$smarty->display( 'add_bs.tpl' );
                }
            } else if( !($bs = read_bs( $db )) ) {
                $error = "Nie można odczytać przytsanku!";
            } else {
                $smarty->assign( 'bs', $bs );
                $smarty->display( 'manage_bs.tpl' );
            }
		}
		
		function save_route( $post, $db )
		{
			$rroute = array();
			foreach( $post['route'] as $route )  { 
				$route = htmlspecialchars( $route, ENT_QUOTES ); 
				$rroute[] = $route; 
			}
					
			if( !$db->save_route( $post['line'], $post['desc'], $rroute ) ) {
				parent::err("Nie można zapisać nowej trasy!");
			} else {
				parent::ntf("Trasa zapisana!");
			}
		}
		
		function update_route( $post, $db )
		{
			$rroute = array();
			foreach( $post['route'] as $route )  { 
				$route = htmlspecialchars( $route, ENT_QUOTES ); 
				$rroute[] = $route; 
			}
					
			if( !$db->update_route( $post['line'], $post['name'], $post['desc'], $rroute ) ) {
				parent::err("Nie można zaktualizować trasy!");
			} else {
				parent::ntf("Trasa została zaktualizowana!");
			}
		}
		
		function manage_lines( $get, $post, $db, $smarty )
		{
			if( !($bs = read_bs( $db )) ) {
				parent::err("Nie można odczytać przytsanków!");
				return FALSE;
			}
                
			if( isset( $post['store']) ) {
				$this->save_route( $post, $db );
			} else if( isset( $post['update'] ) ) {
				$this->update_route( $post, $db );
			}
				
			if( isset( $get['add'] ) ) {
                $nl=$get['add'];
                if( $nl === "" ) {
                    parent::err("Nazwa nie może być pusta!");
                } else if( $db->line_exists( $nl ) ) {
					parent::err("Taka linia już istnieje!");
				} else {
					$smarty->assign( 'bs', $bs );
					$smarty->assign( 'line', $nl );
                    $smarty->display( 'add_route.tpl' );
                }
            } else if( isset( $get['e'] ) ) {
//				if( !($str = read_streets( $db ))) {
//					parent::err("Nie można odczytać ulic!");
//					return FALSE;
//				}
			
//				$smarty->assign( 'streets', $str );
	
				$line = $get['e'];
				if( !($route = read_route( $db, $line, FALSE ) )) {
                    parent::err("Nie można odczytać trasy!");
                } else {
                    $smarty->assign( 'route', $route );
                    $smarty->assign( 'line', $line );
					$smarty->assign( 'bs', $bs );
					$smarty->assign( 'desc', "TODO" );
                    $smarty->display( 'manage_route.tpl' );
                }
			} else if( isset( $get['ett'] ) ) {
				$line = $get['ett'];

				if( !($table = $db->read_table( $line, '0' )) ) {
    	            parent::err("Nie można odczytać rozkładu jazdy!");
					return FALSE;
        	    } else {
            	    $smarty->assign( 'table', $table );
                }

				$smarty->assign( 'line', $line );
				$smarty->display( 'edit_tt.tpl' );
            } else if( !($lines = read_lines( $db )) ) {
                parent::err("Nie można odczytać lini!");
            } else {
                $smarty->assign( 'lines', $lines );
                $smarty->display( 'manage_l.tpl' );
            }
		}
		
		function execute( $get, $post, $db, $smarty )
		{
			$a = $get['a'];        

			if( $a == 'streets' ) {
				/* managing streets */
				$this->manage_streets( $post, $db, $smarty );
            } else if( $a == 'bs' ) { 
				/* managing bus stops */
				$this->manage_bs( $get, $post, $db, $smarty );
            } else if( $a == 'line' ) {
				/* managing lines */
				$this->manage_lines( $get, $post, $db, $smarty );
            }
		}
	}

?>