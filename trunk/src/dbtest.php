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

