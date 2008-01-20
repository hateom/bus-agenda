<?php 
    include_once( 'config.php' );

	session_start(); 
	if( isset( $_GET['logout'] ) ) { 
		session_destroy(); 
		$_SESSION['loggedin'] = '0';
	} 

	/* password sent */
	if( isset( $_POST['passwd'] ) ) {
        if( md5( $_POST['passwd'] ) === $root_pwd ) {
    		$_SESSION['loggedin'] = '1';
        } else {
            $error = "Login failed!";
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8"/>
		<title>Bus Agenda ::Tomasz Huczek &amp; Andrzej Jasiński :: &copy;</title>
		<meta name="description" content="content" />
		<meta name="keywords" content="Bus Agenda" />
		<link rel="stylesheet" href="./style/main.css" type="text/css" media="screen" />
        <script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/javascript" src="./js/jquery.form.js"></script>
        <script type="text/javascript" src="./js/busag.js"></script>
	</head>
<body>

<div id="main">
<?php
	require_once('smarty.php');
	require_once('dbdriver.php');
    require_once('dbreader.php');

    $db = new dbdriver();

	/* check login */
	if( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == 1 ) {
        // logged in
        $smarty->display( 'logout.tpl' );
        $smarty->display( 'admin_panel.tpl' );
	} else {
		// not logged in
		$smarty->display( 'login.tpl' );
	}

    $smarty->display( 'head.tpl' );
    $smarty->display( 'panel.tpl' );

    /* page selected */
     if( isset( $_GET['i']) ) {
        $i = $_GET['i'];
        /* connections */
        if( $i == 'conn' ) {
            if( isset( $_POST['search'] ) )
            {
                $from = $_POST['from'];
                $to   = $_POST['to'];
                $time = $_POST['time'];
				
				$smarty->assign( 'from', $db->get_bs_name( $from ) );
                $smarty->assign( 'to',   $db->get_bs_name( $to ) );
                
                if( $from == $to ) 
                {
                    $error = "Przystanek początkowy jest taki sam jak docelowy!";
                } else if( !preg_match( "/^\d{1,2}\:\d{2}$/i", $time ) ) {
                    $error = "Godzina musi być w formacie HH:MM";
                } else {
                    if( !($r_route = find_route( $db, $from, $to, $time )) ) {
                        $error = "Nie można odnaleźć żądanej trasy!";
                    } else {
                        $smarty->assign( 'r_route', $r_route );
                        $smarty->assign( 'time', $time );
                        $smarty->display( 'conn_result.tpl' );
                    }
                }
            }

            if( !($bs = read_bs( $db )) ) {
                $error = "Nie można odczytać przytsanku!";
            } else {
                $smarty->assign( 'bs', $bs );
                $smarty->assign( 'time', date('G:i') );
                $smarty->display( 'conn_form.tpl' );
            }
        /* lines */
        } else if( $i == 'line' ) {
            if( !($lines = read_lines( $db )) ) {
                $error = "Nie można odczytać lini!";
            } else {
                $smarty->assign( 'lines', $lines );
                $smarty->display( 'lines.tpl' );
            }
        /* bus stops */
        } else if( $i == 'bs' ) {
            if( !($bs = read_bs( $db )) ) {
                $error = "Nie można odczytać przytsanku!";
            } else {
                $smarty->assign( 'bs', $bs );
                $smarty->display( 'stops.tpl' );
            }
        }
    /* administrator mode*/
    } else if( isset( $_GET['a'] )) {
        if( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == 1 ) {
            $a = $_GET['a'];        
			if( !($str = read_streets( $db ))) {
				$error = "Nie można odczytać ulic!";
			}
			/* managing streets */
			if( $a == 'streets' ) {
				if( isset( $_POST['add'] ) ) {
					$ns = $_POST['add'];
					if( $ns === "" ) {
						$error = "Nazwa ulicy jest pusta!";
					} else {
						if( !$db->save_street( $ns ) ) {
							$error = "Nie można zapisać ulicy!";
						}
					}
				} else if( isset( $_POST['name']) ) {
					$nn = $_POST['name'];
					$id = $_POST['id'];
					if( !$db->update_street( $id, $nn ) ) {
						$error = "Nie można zmienić ulicy!";
					}
				}

				if( !($str = read_streets( $db ))) {
					$error = "Nie można odczytać ulic!";
				}
			
				$smarty->assign( 'streets', $str );
				$smarty->display( 'manage_s.tpl' );
            } else if( $a == 'bs' ) { /* managing bus stops */
				if( isset( $_POST['street1'] ) ) {
					$s1   = $_POST['street1'];
					$s2   = $_POST['street2'];
					$name = $_POST['name'];
					
					// update
					if( isset( $_GET['u'] ) ) {
						$bs_id = $_POST['bs_id'];
						if( !$db->update_bs( $bs_id, $name, $s1, $s2 ) ) {
							$error = "Nie można zapisać zmian w przystanku!";
						}
					} else { // insert new
						if( !$db->save_bs( $name, $s1, $s2 ) ) {
							$error = "Nie można zapisać nowego przystanku!";
						}
					}
				}
			
				if( isset( $_GET['e'] )) {
				    $bs = $_GET['e'];
					$row = $db->read_bs_id( $bs );
					$smarty->assign( 'bs', $row['nazwa'] );
					$smarty->assign( 'bs_id', $bs );
					$smarty->assign( 'street1', $row['ulica1'] );
					$smarty->assign( 'street2', $row['ulica2'] );
					$smarty->assign( 'street1_id', $row['ulica1_id'] );
					$smarty->assign( 'street2_id', $row['ulica2_id'] );
					$smarty->assign( 'streets', $str );
					$smarty->display( 'edit_bs.tpl' );
				} else if( isset( $_POST['add'] ) ) {
                    $nbs = $_POST['add'];
                    if( $nbs === "" ) {
                        $error = "Nazwa nie może być pusta!";
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
            /* managing lines */
            } else if( $a == 'line' ) {
			
				if( !($bs = read_bs( $db )) ) {
					$error = "Nie można odczytać przytsanków!";
				}
                
				if( isset( $_POST['store']) ) {
					$rroute = array();
					foreach( $_POST['route'] as $route )  { 
						$route = htmlspecialchars( $route, ENT_QUOTES ); 
						$rroute[] = $route; 
					}
					
					if( !$db->save_route( $_POST['line'], $_POST['desc'], $rroute ) )
					{
						$error = "Nie można zapisać nowej trasy!";
					}
				} else if( isset( $_POST['update'] ) ) {
					$rroute = array();
					foreach( $_POST['route'] as $route )  { 
						$route = htmlspecialchars( $route, ENT_QUOTES ); 
						$rroute[] = $route; 
					}
					
					if( !$db->update_route( $_POST['line'], $_POST['name'], $_POST['desc'], $rroute ) )
					{
						$error = "Nie można zaktualizować trasy!";
					}
				}
				
				if( isset( $_GET['add'] ) ) {
                    $nl=$_GET['add'];
                    if( $nl === "" ) {
                        $error = "Nazwa nie może być pusta!";
                    } else {
						$smarty->assign( 'bs', $bs );
						$smarty->assign( 'line', $nl );
	                    $smarty->display( 'add_route.tpl' );
                    }
                } else if( isset( $_GET['e'] ) ) {
                    $line = $_GET['e'];

                    if( !($route = read_route( $db, $line, FALSE ) )) {
                        $error = "Nie można odczytać trasy!";
                    } else {
                        $smarty->assign( 'route', $route );
	                    $smarty->assign( 'line', $line );
						$smarty->assign( 'bs', $bs );
						$smarty->assign( 'desc', "TODO" );
	                    $smarty->display( 'manage_route.tpl' );
                    }
                } else if( !($lines = read_lines( $db )) ) {
                    $error = "Nie można odczytać lini!";
                } else {
                    $smarty->assign( 'lines', $lines );
                    $smarty->display( 'manage_l.tpl' );
                }
            } 
        } else {
            /* no admin option selected - show intr0 */
            $smarty->display( 'intro.tpl' );
            $error = "Nie masz praw do oglądania tej strony!";
        }
    } else if( isset($_GET['l']) ) {
        $l = $_GET['l'];
        if( isset( $_GET['reverse'] )) {
            $reverse = TRUE;
        } else {
            $reverse = FALSE;
        }

        if( !($route = read_route( $db, $l, $reverse ) )) {
            $error = "Nie można odczytać trasy!";
        } else {
            if( isset( $_GET['tt'] ) ) {
                $bs = $_GET['tt'];
                if( !($ttable = read_ttable( $db, $l, $bs, $reverse )) ) {
                    $error = "Nie można odczytać rozkładu jazdy!";
                } else {
                    $smarty->assign( 'ttable', $ttable );
                }
            }

            if( !($dir = $db->read_direction($l) )) {
				$error = "Nie można odczytać trasy!";
			}
            $first = $db->get_bs_name( $dir['first'] );
            $last = $db->get_bs_name( $dir['last'] );
			
			// echo $first . " (" . $dir['first'] . ") , " . $last . " (" . $dir['last'] . ")<br/>";

            if( $reverse == TRUE ) {
                $smarty->assign( 'rev', 1 );
            } else {
                $smarty->assign( 'rev', 0 );
            }

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
        }
    } else if( isset( $_GET['b'] ) ) {
        $bs = $_GET['b'];
        if( !($bs_info = read_bs_info( $db, $bs ) )) {
            $error = "Nie można odczytać informacji o przystanku!";
        } else {
            $smarty->assign( 'bs_info', $bs_info );
            $smarty->assign( 'bs', $db->get_bs_name( $bs ) );
            $smarty->display( 'bs_info.tpl' );
        }
    } else {
        $smarty->display( 'intro.tpl' );
    }
    
    if( isset( $error ) ) {
        $smarty->assign( "error_msg", $error );
        $smarty->display( 'error.tpl' );
    }

    $smarty->display( 'foot.tpl' );
    $db->release();
?>

</div>
</body></html>

