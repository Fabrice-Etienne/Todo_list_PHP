<?php
session_start();  // Start the session
$user_email = $_SESSION["user_email"];  // Get the user's email from the session

// Include the content of the page (header.php)
require_once("./header.php");
// Include the content of the page (connection.php)
// Database connection
require_once("./connection.php");
require_once("./functions.php");
$user_id = getUsersId($user_email);

// Select task names from the tasks table
$sql_task = "SELECT tasks_name FROM tasks WHERE id_users = ?";
$request_prepared = mysqli_prepare($connection_bdd, $sql_task);

// Check if the query preparation failed
if (!$request_prepared) {
    die("Erreur au niveau de la préparation de la requete d'afficher les taches : " . mysqli_error($connection_bdd));
}

mysqli_stmt_bind_param($request_prepared, "i", $user_id);  // Bind parameter
mysqli_stmt_execute($request_prepared);  // Execute the query
$result_request_task = mysqli_stmt_get_result($request_prepared);  // Get the query result

// Close the database connection
mysqli_close($connection_bdd);
?>
<body class="displays-task-page bg-light">
    <div class="container d-flex flex-column align-items-center p-4">
        <h1>Liste des tâches</h1>
        <ul class="list-group w-50 p-2">
            <?php
            while ($row = mysqli_fetch_assoc($result_request_task)) {
                echo("<li class=\"list-group-item\">" . $row['tasks_name'] . "</li>\n");
            }
            ?>
        </ul>

        <div class="d-flex justify-content-between p-1 w-50">
            <button type="button" class="btn btn-outline-primary m-1"><a href="./my_account.php" class="link-light">Retour</a></button>
            <button type="submit" class="btn btn-outline-primary m-1"><a href="./delete_task.php" class="link-light">Supprimer</a></button>
            <button type="submit" class="btn btn-outline-primary m-1"><a href="./add_task.php" class="link-light">Ajouter</a></button>
        </div>
    </div>
</body>