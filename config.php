<?php 

    define('DB_dsn', 'mysql:dbname=stack_over;host=localhost');
    define('DB_user', 'root');
    define('DB_password', '');

    //starting session
    session_start();
    $db;
           
     //Establish Database Connection
    try {

        $db = new PDO(DB_dsn, DB_user, DB_password);
        
    } catch (PDOException $err) { 
        
        die("Could not connect to database");
        
    }



        // santizing function
    function santize_array ( $array ){

        return  filter_var_array( $array, FILTER_SANITIZE_STRING );
        
    }

    function fetch_table( $table_name ){
        global $db;
        $qr = $db->query( "SELECT * FROM $table_name" );

        return $qr->fetchAll();
    }
    

  
    function insert_row(  ){
        global $db;

        $table_name = func_get_arg(0);
        $columns = "";
        $values = "";

        if ( func_num_args() > 2 ) {

            for ( $i=1; $i< func_num_args(); $i = $i + 2  ){
                
                $element = func_get_arg($i);
                $columns .= "$element, ";
                
            
            }

            for ( $i=2; $i< func_num_args(); $i = $i + 2  ){
                
                $element = func_get_arg($i);
                if( is_string($element) ){
                    $element = "'$element', ";
                }else{
                    $element = "$element, ";
                }
                $values .= $element;
                
            
            }
            $columns = substr($columns, 0, strlen($columns)-2);
            $values = substr($values, 0, strlen($values)-2);
            try {


                $qr = $db->query( "INSERT INTO $table_name ( $columns ) VALUES ( $values )" );
                return true;

            } catch (\Throwable $th) {

                return false;
                
            }
            

        }else{

            return false;

        }
    }




    function values_in_array(  ){
        // first argument is the array
        // the rest of the arguments are the elements of the array that must be checked
        $final_value = true;
        $array = func_get_arg(0);
        if ( func_num_args() > 1 ) {

            for ( $i=1; $i< func_num_args(); ++$i ){
                
                if( ! isset( $array[ func_get_arg($i) ] ) ){

                    $final_value = false;
                    break;

                }else{
                    $element = $array[ func_get_arg($i) ];
                    if( is_string($element) && $element == "" ){
                        $final_value = false;
                        break;
                    }
                }
            
            }
            return $final_value;

        }else{

            die("insuffcient number of arguments given");

        }



    }



   
    