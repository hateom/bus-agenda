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
        <script type="text/javascript" src="./js/busag.js"></script>
	</head>
<body>

<div id="main">
<?php
	require_once('smarty.php');
	require_once('dbdriver.php');

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

    if( isset( $error ) ) {
        $smarty->assign( "error_msg", $error );
        $smarty->display( 'error.tpl' );
    }

    $smarty->display( 'panel.tpl' );


    /* page selected */
     if( isset( $_GET['i']) ) {
        $i = $_GET['i'];
        /* connections */
        if( $i == 'conn' ) {
            $db = new dbdriver();
            if( !$db->read_bs()) {
                $error = "Nie można odczytać przystanku!";
            } else {
                $bs = array();
                while( $row = $db->next_bs()) {
                    $bs[] = $row;
                }
            }
            $db->free_result();

            if( isset( $error ) ) {
                $smarty->assign( "error_msg", $error );
                $smarty->display( 'error.tpl' );
            }

            $smarty->assign( 'bs', $bs );
            $smarty->display( 'connections.tpl' );
        /* lines */
        } else if( $i == 'line' ) {
            if( !$db->read_lines()) {
                $error = "Nie można odczytać lini!";
            } else {
                $lines = array();
                while( $row = $db->next_line()) {
                    $lines[] = $row;
                }
            }
            $db->free_result();

            if( isset( $error ) ) {
                $smarty->assign( "error_msg", $error );
                $smarty->display( 'error.tpl' );
            }

            $smarty->assign( 'lines', $lines );
            $smarty->display( 'lines.tpl' );
        /* bus stops */
        } else if( $i == 'bs' ) {
            $db = new dbdriver();
            if( !$db->read_bs()) {
                $error = "Nie można odczytać przystanku!";
            } else {
                $bs = array();
                while( $row = $db->next_bs()) {
                    $bs[] = $row;
                }
            }
            $db->free_result();

            if( isset( $error ) ) {
                $smarty->assign( "error_msg", $error );
                $smarty->display( 'error.tpl' );
            }

            $smarty->assign( 'bs', $bs );
            $smarty->display( 'stops.tpl' );
        }
    /* administrator mode*/
    } else if( isset( $_GET['a'] )) {
        if( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == 1 ) {
            $a = $_GET['a'];            
            /* managing bus stops */
            if( $a == 'bs' ) {
                $smarty->display( 'manage_bs.tpl' );
            /* managing lines */
            } else if( $a == 'line' ) {
                $smarty->display( 'manage_l.tpl' );
            } 
        } else {
            /* no admin option selected - show intr0 */
            $smarty->display( 'intro.tpl' );
        }
    } else if( isset($_GET['l']) ) {
        $l = $_GET['l'];
        $db = new dbdriver();
        if( !$db->read_route( $l )) {
            $error = "Nie można odczytać trasy!";
        } else {
            $bs = array();
            while( $row = $db->next_route()) {
                $route[] = $row;
            }
        }
        $db->free_result();

        if( isset( $error ) ) {
            $smarty->assign( "error_msg", $error );
            $smarty->display( 'error.tpl' );
        }

        $smarty->assign( 'line', $l );
        $smarty->assign( 'route', $route );
        $smarty->display( 'route.tpl' );
    } else {
        $smarty->display( 'intro.tpl' );
    }


    $smarty->display( 'foot.tpl' );
    $db->release();
?>

</div>
</body></html>

