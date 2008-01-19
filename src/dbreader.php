<?php
    require_once('dbdriver.php');

    function read_bs( $dbd )
    {
        if( !$dbd->read_bs()) {
            return FALSE;
        } else {
            $bs = array();
            while( $row = $dbd->next_bs()) {
                $bs[] = $row;
            }
        }
        $dbd->free_result();

        return $bs;
    }

    function read_lines( $dbd )
    {
            if( !$dbd->read_lines()) {
                return FALSE;
            } else {
                $lines = array();
                while( $row = $dbd->next_line()) {
                    $lines[] = $row;
                }
            }
            $dbd->free_result();

            return $lines;
    }

    function read_route( $dbd, $line )
    {
        if( !$dbd->read_route( $line )) {
            return FALSE;
        } else {
            $bs = array();
            while( $row = $dbd->next_route()) {
                $route[] = $row;
            }
        }
        $dbd->free_result();

        return $route;
    }
?>

