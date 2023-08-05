<?php 

// Include the content of the page (header.php)
require_once("./header.php") ;

session_start();
$User_email = $_SESSION['user_email'] ;
if(! isset($User_email)) {
    header("location: index.php") ;
    exit() ;
}

// Database connection
require_once("./connection.php") ;
$sql = "SELECT users_first_name FROM users WHERE users_email = ?" ;

$request_prepared = mysqli_prepare($connection_bdd, $sql) ;
mysqli_stmt_bind_param($request_prepared, 's', $User_email);
mysqli_stmt_execute($request_prepared);
$result = mysqli_stmt_get_result($request_prepared); // result of the request prepared

$time = date("H") ;
$good_morning_evening = ($time >= 17 ? "Bonsoir": "Bonjour") ;

?>

<body class="menu-page bg-light">
    <div class="welcome d-flex justify-content-end p-2">
        <?php 
            while($row = mysqli_fetch_assoc($result)){
                echo ("<h5>". $good_morning_evening." ". $row['users_first_name']."</h5>");
            }
        ?>
    </div>
    <div class="container">
        <h1 class="d-flex justify-content-center">Menu de contrôle</h1>
        <div class="d-flex justify-content-center">
            <ul class="list-group w-50 p-2" style="list-style-type: none;">
                <li><a href="Add_task.php" class="list-group-item list-group-item-action active">Ajouter une tâche</a></li>
                <li><a href="displays_task.php" class="list-group-item list-group-item-action">Afficher les tâches</a></li>
                <li><a href="delete_task.php" class="list-group-item list-group-item-action">Supprimer une tâche</a></li>
            </ul>
        </div>
    </div>
</body>