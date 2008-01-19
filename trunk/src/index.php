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
                        $smarty->assign( 'from', $db->get_bs_name( $from ) );
                        $smarty->assign( 'to',   $db->get_bs_name( $to ) );
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
            /* managing bus stops */
            if( $a == 'bs' ) {
                if( isset( $_POST['add'] ) ) {
                    $nbs = $_POST['add_bs'];
                    if( $nbs === "" ) {
                        $error = "Nazwa nie może być pusta!";
                    } else {
                        echo "SQL INSERT = ".$nbs;
                    }
                }

                if( !($bs = read_bs( $db )) ) {
                    $error = "Nie można odczytać przytsanku!";
                } else {
                    $smarty->assign( 'bs', $bs );
                    $smarty->display( 'manage_bs.tpl' );
                }
            /* managing lines */
            } else if( $a == 'line' ) {
                if( isset( $_GET['add'] ) ) {
                    $nl=$_GET['add'];
                    if( $nl === "" ) {
                        $error = "Nazwa nie może być pusta!";
                    } else {
						if( !($bs = read_bs( $db )) ) {
		                    $error = "Nie można odczytać przytsanków!";
        		        } else {
							$smarty->assign( 'bs', $bs );
							$smarty->assign( 'line', $nl );
	                        $smarty->display( 'add_route.tpl' );
						}
                    }
                }

                if( isset( $_GET['e'] ) ) {
                    $line = $_GET['e'];

                    if( !($route = read_route( $db, $line, FALSE ) )) {
                        $error = "Nie można odczytać trasy!";
                    } else {
                        $smarty->assign( 'route', $route );
	                    $smarty->assign( 'line', $line );
	                    $smarty->display( 'manage_route.tpl' );
                    }
                }

                if( !($lines = read_lines( $db )) ) {
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

            $dir = $db->read_direction($l);
            $first = $db->get_bs_name( $dir['first'] );
            $last = $db->get_bs_name( $dir['last'] );

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

