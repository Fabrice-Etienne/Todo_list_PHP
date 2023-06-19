<?php  

    // Database informations
    $server = "localhost" ;
    $username = "root" ;  
    $password = "" ; 
    $data_base_name = "todo_list_php" ;

    // Database connection
    $connection_bdd = mysqli_connect($server, $username, $password, $data_base_name) ;

    if (!$connection_bdd){
        die("Connexion impossible à la base de donnée : " . mysqli_connect_error()) ;
    }

    // utf8 encoding for the all requests to the database
    mysqli_set_charset($connection_bdd, "utf8") ;

?>