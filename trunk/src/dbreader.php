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

    function read_route( $dbd, $line, $reverse )
    {
        if( !$dbd->read_route( $line, $reverse )) {
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

    function read_bs_info( $dbd, $bs )
    {
        if( !$dbd->read_bs_info( $bs )) {
            return FALSE;
        } else {
            $bs_info = array();
            while( $row = $dbd->next_route()) {
                $bs_info[] = $row;
            }
        }
        $dbd->free_result();

        return $bs_info;
    }

    function find_route( $dbd, $from, $to, $time )
    {
        if( !$dbd->find_route( $from, $to, $time )) {
            return FALSE;
        } else {
            $r_route = array();
            while( $row = $dbd->next_route()) {
                $r_route[] = $row;
            }
        }
        $dbd->free_result();

        return $r_route;
    }

    function read_ttable( $dbd, $line, $bs, $rev )
    {
        if( !$dbd->read_ttable( $line, $bs, $rev )) {
            return FALSE;
        } else {
            $tt = array();
            while( $row = $dbd->next_ttable()) {
                $tt[] = $row;
            }
        }
        $dbd->free_result();

        return $tt;
    }
?>

