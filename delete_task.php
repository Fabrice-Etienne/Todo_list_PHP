<?php

// Include the content of the page (header.php)
require_once("./header.php") ;

// Include the content of the page (connection.php)
// Database connection
require_once("./connection.php") ;

$sql = "SELECT tasks_name FROM tasks" ;
$request = mysqli_query($connection_bdd, $sql) ;

// Check if the user has sent the form
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST["send-form"])){
        $user_delete = htmlspecialchars($_POST['user_option']) ;

        if($user_delete != 'choisir'){

            $sql = "DELETE FROM tasks WHERE tasks_name = ?" ;
            $request_prepared = mysqli_stmt_prepare($connection_bdd, $sql) ;

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($request_prepared, 's', $user_delete) ;
            mysqli_stmt_execute($request_prepared) ;
            mysqli_stmt_close($request_prepared) ;
        }

    }

    else { 
        die("Une erreur est survenue lors de la soumission du formulaire. Veuillez réessayer plus tard.") ;
    }
}

// Close database connection
mysqli_close($connection_bdd) ;

?>

<div class="delete_task">
    <h1>Supprimer des tâches</h1>

    <form method="post" action="./delete_task.php">
        <select name="user_option">
            <option value="choisir" selected disabled>Choisir</option>
            <?php
                while($row = mysqli_fetch_assoc($request)){
                    echo("<option value=\"{$row['tasks_name']}\">".$row['tasks_name']."</option>\n") ;
                }
            ?>
        </select>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-primary"><a href="./menu.php" class="link-light">Retour</a></button>
            <button type="submit" class="btn btn-primary" name="send-form">Envoyer</button>
        </div>
    </form>
</div>