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

<?php

    include_once('dbdriver.php');

    $db = new dbdriver();

    if( $db->test() == FALSE ) {
        echo "<h1>FAILED</h1>";
    } else {
        echo "<h1>OK!</h1>";
    }

    if( !$db->read_lines() ) {
        echo "<p>ERROR reading lines!</p>";
    } else {
        echo "<ul>";
        while( $row = $db->next_line() ) {
            echo "<li>Linia: " . $row['numer'] . ", " . $row['typ'] .", ". $row['opis'] . "</li>";
        }
        echo "</ul>";
    }
    $db->free_result();

    if( !$db->read_bs() ) {
        echo "<p>ERROR reading bus stops!</p>";
    } else {
        echo "<ul>";
        while( $row = $db->next_bs() ) {
            echo "<li>Przystanek: " . $row['nazwa'] . "</li>";
        }
        echo "</ul>";
    }
    $db->free_result();

    if( !$db->read_streets() ) {
        echo "<p>ERROR reading streets!</p>";
    } else {
        echo "<ul>";
        while( $row = $db->next_bs() ) {
            echo "<li>Ulica: " . $row['nazwa'] . "</li>";
        }
        echo "</ul>";
    }
    $db->free_result();


    $db->release();

?>
</body>
</html>
