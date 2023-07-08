<?php 

// Include the content of the page (header.php)
require_once("./header.php") ;

session_start();
if(! isset($_SESSION['user_email']) ) {
    header("location: index.php") ;
    exit() ;
}

// Database connection
require_once("./connection.php") ;
$sql = "SELECT users_first_name FROM users WHERE users_email = ?" ;

$request_prepared = mysqli_prepare($connection_bdd, $sql) ;
mysqli_stmt_bind_param($request_prepared, 's', $_SESSION['user_email']);
mysqli_stmt_execute($request_prepared);
$result = mysqli_stmt_get_result($request_prepared); // result of the request prepared

$time = date("H") ;
$good_morning_evening = ($time >= 17 ? "Bonsoir": "Bonjour") ;

while($row = mysqli_fetch_assoc($result)){
    echo("<p>".$good_morning_evening." ".$row['users_first_name']."</p>") ;
}

?>

<div class="menu-page d-flex justify-content-center align-items-center" >
    <ul class="bg-primary">
        <li><a href="Add_task.php">Add task</a></li>
        <li><a href="displays_task.php">Displays tasks</a></li>
        <li><a href="delete_task.php">Delete task</a></li>
    </ul>
</div>