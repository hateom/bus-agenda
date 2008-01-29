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
	
	require_once('browser.php');
	require_once('admin.php');

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

	if( isset( $_GET['delete'] ) ){
		$smarty_assigned = TRUE;
		$delete = $_GET['delete'];
		if( $delete === "street" ) {
			$smarty->assign( 'msg', 'Czy na pewno chcesz usunąć ulicę '.$db->get_street_name($_GET['street']).'?' );
			$smarty->assign( 'action', 'street' );
			$smarty->assign( 'redir', 'streets' );
			$smarty->assign( 'id', $_GET['street'] );
		} else if( $delete === "bs" ) {
			$smarty->assign( 'msg', 'Czy na pewno chcesz usunąć przystanek "'.$db->get_bs_name($_GET['bs']).'"?' );		
			$smarty->assign( 'action', 'bs' );
			$smarty->assign( 'redir', 'bs' );
			$smarty->assign( 'id', $_GET['bs'] );
		} else if( $delete === "route" ) {
			$smarty->assign( 'msg', 'Czy na pewno chcesz usunąć trasę '.$_GET['route'].'?' );		
			$smarty->assign( 'action', 'route' );
			$smarty->assign( 'redir', 'line' );
			$smarty->assign( 'id', $_GET['route'] );
		}
		$smarty->display( 'delete_confirm.tpl' );
    } else if( isset( $_POST['d'] ) ) {
		$smarty_assigned = TRUE;
		$d = $_POST['d'];
		$id = $_POST['id'];
		if( $d === "street" ) {
			if( !$db->remove_street( $id ) )
			{
				$error = "Nie można usunąć ulicy ".$id."!";
			} else {
				$notify = "Ulica została usunięta.";
			}
		} else if( $d === "bs" ) {
			if(!$db->remove_bs( $id ) )
			{
				$error = "Nie można usunąć przystanku ".$db->get_bs_name($id)."!";
			} else {
				$notify = "Usunięto przystanek ".$db->get_bs_name($id).".";
			}
		} else if( $d === "route" ) {
			if( !$db->remove_route( $id ) )
			{
				$error = "Nie można usunąć linii " . $id . "!";
			} else {
				$notify = "Linia ".$id." została usunięta.";
			}
		}
	}

    /* page selected */
     if( isset( $_GET['i'] ) || isset( $_GET['l'] ) || isset( $_GET['b'] ) ) {
	 	$smarty_assigned = TRUE;
        $brows = new browser();
		$brows->execute( $_GET, $_POST, $db, $smarty );
    	if( $brows->is_error() ) {
			$error = $brows->get_error();
		}
		if( $brows->is_notify() ) {
			$notify = $brows->get_notify();
		}
    } else if( isset( $_GET['a'] )) { /* administrator mode*/
		$smarty_assigned = TRUE;
        if( isset( $_SESSION['loggedin'] ) && $_SESSION['loggedin'] == 1 ) {
            $adm = new admin();
			$adm->execute( $_GET, $_POST, $db, $smarty ); 
	    	if( $adm->is_error() ) {
				$error = $adm->get_error();
			}			
			if( $adm->is_notify() ) {
				$notify = $adm->get_notify();
			}
        } else {
            /* no admin option selected - show intr0 */
            $smarty->display( 'intro.tpl' );
            $error = "Nie masz praw do oglądania tej strony!";
        }
	}

	if( !isset( $smarty_assigned )) {
        $smarty->display( 'intro.tpl' );
    }
    
    if( isset( $error ) ) {
        $smarty->assign( "error_msg", $error );
        $smarty->display( 'error.tpl' );
    }

    if( isset( $notify ) ) {
        $smarty->assign( "notify_msg", $notify );
        $smarty->display( 'notify.tpl' );
    }

    $smarty->display( 'foot.tpl' );
    $db->release();
?>

</div>
</body></html>

