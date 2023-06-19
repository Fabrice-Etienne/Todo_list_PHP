<?php 
// Include the content of the page (header.php)
require_once("./header.php") ;

// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Check if the input (user_task) is defined and not empty
    if (isset($_POST['user_task']) && !empty($_POST['user_task'])){

        if(strlen($_POST['user_task']) > 50){
            die("Votre tâche ne doit pas dépasser 50 caractères.") ;
        }

        $user_task = htmlspecialchars($_POST['user_task']) ;

        // Include the content of the page (connection.php)
        // Database connection function
        require_once("./connection.php") ;

        // request prepared
        $sql = "INSERT INTO tasks (tasks_name) VALUES (?)" ;
        $request_prepared = mysqli_stmt_prepare($connection_bdd, $sql) ;
        
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($request_prepared,'s', $user_task) ;

        // Execute request prepared
        mysqli_stmt_execute($request_prepared) ;

        // Close request prepared
        mysqli_stmt_close($request_prepared) ;

        // Close the database connection
        mysqli_close($connection_bdd);

    }

    else { 
        die("Une erreur est survenue lors de la soumission du formulaire. Veuillez réessayer plus tard.") ;
    }
}


?>

<!-- HTML code -->
<div class="add-task-page">
    <h1>Ajouter des tâches</h1>
    <form method="post" action="./add_task.php" id="form-add-task">
    <div class="form-group">
        <label for="user_task">Ajouter votre tache</label>
        <input type="text" class="form-control" name="user_task" id="user_task" placeholder="Ajouter votre tache..." required="required">
    </div>
    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-primary"><a href="./menu.php" class="link-light">Retour</a></button>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>
</form>
</div>