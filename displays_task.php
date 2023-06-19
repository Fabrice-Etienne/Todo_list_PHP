<?php

// Include the content of the page (header.php)
require_once("./header.php") ;

// Include the content of the page (connection.php)
// Database connection
require_once("./connection.php") ;

// Select all elements of this table (tasks)
$sql = "SELECT tasks_name FROM tasks" ;
$request = mysqli_query($connection_bdd, $sql) ;

echo ("<div class=\"displays-task-page\">
    <h1>Liste des tâches</h1>
    <ul class=\"list-group\">\n") ;

        while($row = mysqli_fetch_assoc($request)){
            echo("<li class=\"list-group-item\">". $row['tasks_name']. "</li>\n") ;
        }

echo ("</ul>

<div class=\"d-flex justify-content-between\">
    <button type=\"button\" class=\"btn btn-primary\"><a href=\"./menu.php\" class=\"link-light\">Retour</a></button>
    <button type=\"submit\" class=\"btn btn-primary\"><a href=\"./delete_task.php\" class=\"link-light\">Supprimer</a></button>
</div>

</div>\n") ;

// Close the database connection
mysqli_close($connection_bdd) ;

?>