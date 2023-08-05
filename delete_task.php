<?php
// Start the session
session_start();
// Get the user's email from the session
$user_email = $_SESSION["user_email"];

// Include the content of the page (header.php)
require_once("./header.php");

// Include the content of the page (connection.php)
// Database connection
require_once("./connection.php");
require_once("./functions.php");
$user_id = getUsersId($user_email); // This variable holds the user ID

// Check if the user has submitted the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["send-form"])) {
        $user_delete = htmlspecialchars($_POST['user_option']);

        if ($user_delete != 'choose') {
            // Query to delete tasks based on user ID
            $sql_delete = "DELETE FROM tasks WHERE id_users = ?";
            $request_prepared_delete = mysqli_prepare($connection_bdd, $sql_delete);
            if (!$request_prepared_delete) {
                die("Erreur dans la préparation de la requête de suppression de tâches: " . mysqli_error($connection_bdd));
            }

            mysqli_stmt_bind_param($request_prepared_delete, 'i', $user_id);
            mysqli_stmt_execute($request_prepared_delete);

            // Close prepared statement
            mysqli_stmt_close($request_prepared_delete);
        }
    } else {
        die("Une erreur s'est produite lors de la soumission du formulaire. Veuillez réessayer plus tard.");
    }
}

// Query to display the tasks for the user's ID
$sql_display = "SELECT tasks_name FROM tasks WHERE id_users = ?";
$request_prepared_display = mysqli_prepare($connection_bdd, $sql_display);
if (!$request_prepared_display) {
    die("Erreur dans la préparation de la requête pour l'affichage des tâches: " . mysqli_error($connection_bdd));
}

mysqli_stmt_bind_param($request_prepared_display, 'i', $user_id);
mysqli_stmt_execute($request_prepared_display);
$result_display = mysqli_stmt_get_result($request_prepared_display);

?>
<body class="delete_task bg-light">
    <div class="container d-flex flex-column align-items-center p-4">
        <h1>Supprimer une tache</h1>

        <form method="post" action="./delete_task.php" class="p-2 w-50">
            <select name="user_option" class="form-select w-100" aria-label="Disabled select example">
                <option value="choose" selected disabled>Choisir</option>
                <?php
                if (mysqli_num_rows($result_display) > 0) {
                    while ($row = mysqli_fetch_assoc($result_display)) {
                        echo("<option value=\"{$row['tasks_name']}\">" . $row['tasks_name'] . "</option>\n");
                    }
                }
                else{
                    die("Aucune tache n'a été trouvé en base de donnée. <a href=\"./add_task.php\" target=\"_blank\">Ajouter des taches</a> " );
                }

                // Close prepared statement
                mysqli_stmt_close($request_prepared_display);
                ?>
            </select>
            <div class="d-flex justify-content-between p-1">
                <button type="button" class="btn btn-outline-primary m-1"><a href="./my_account.php">Retour</a></button>
                <button type="submit" name="send-form" class="btn btn-outline-primary m-1">Envoyer</button>
            </div>
        </form>
    </div>
</body>
<?php
// Close database connection
mysqli_close($connection_bdd);
?>
