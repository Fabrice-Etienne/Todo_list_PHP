<?php 

// This function Get user ID
function getUsersId($userEmail){
    include("./connection.php");  // Include database connection details
    $sql = "SELECT id_users FROM users WHERE users_email = ?";
    $request_prepared_I = mysqli_prepare($connection_bdd, $sql);  // Prepare the SQL query
    
    // Check if query preparation failed
    if (!$request_prepared_I) {
        echo("Problème de requete : " . mysqli_error($connection_bdd));
        header("location: ./index.php");
        exit();
    }
    
    mysqli_stmt_bind_param($request_prepared_I, 's', $userEmail);  // Bind parameter
    mysqli_stmt_execute($request_prepared_I);  // Execute the query
    $result = mysqli_stmt_get_result($request_prepared_I);  // Get the query result
    
    // Check if query execution failed
    if (!$result) {
        echo("Problème d'execution de la requete : " . mysqli_error($connection_bdd));
        header("location: ./index.php");
        exit();
    }
    
    $row = mysqli_fetch_assoc($result);  // Fetch the result row
    
    // Check if user was found
    if (!$row) {
        echo("L'id de l'utilisateur est introuvable.");
        header("location: ./index.php");
        exit();
    }
    
    $user_id = $row['id_users'];  // Get the user ID
    
    return $user_id;  // Return the user ID
}



?>