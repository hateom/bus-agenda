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
	</head>
<body>

<div id="main">
<?php
	require_once('smarty.php');

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


    if( !isset( $_GET['i'] ) && !isset( $_GET['a'] ) ) {
        $smarty->display( 'intro.tpl' );
    } else if( isset( $_GET['i']) ) {
        $i = $_GET['i'];
        if( $i == 'conn' ) {
            $smarty->display( 'connections.tpl' );
        } else if( $i == 'line' ) {
            $smarty->display( 'lines.tpl' );
        } else if( $i == 'bs' ) {
            $smarty->display( 'stops.tpl' );
        }
    } else if( isset( $_GET['a'] )) {
        if( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == 1 ) {
            $a = $_GET['a'];            
            if( $a == 'bs' ) {
                $smarty->display( 'manage_bs.tpl' );
            } else if( $a == 'line' ) {
                $smarty->display( 'manage_l.tpl' );
            } else if( $a == 'tt' ) {
                $smarty->display( 'manage_tt.tpl' );
            }
        } else {
            $smarty->display( 'intro.tpl' );
        }
    }


    $smarty->display( 'foot.tpl' );
?>

</div>
</body></html>

