<?php 

// Include the content of the page (header.php)
require_once("./header.php") ;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['user_email']) && !empty($_POST['user_email'])) {

        if (isset($_POST['user_password']) && !empty($_POST['user_password'])) {
            $user_email    = htmlspecialchars($_POST["user_email"]) ;
            $user_password = htmlspecialchars($_POST["user_password"]) ;

            if (strlen($user_email) > 50 || strlen($user_password) > 50) {
                die("Tous les champs doivent contenir moins de 51 caractères.") ;
            }

            // Include the content of the page (connection.php)
            // Database connection
            require_once("./connection.php") ;

            // First request
            $sql_email = "SELECT users_email FROM users WHERE users_email = ? LIMIT 1";
            $request_email = mysqli_prepare($connection_bdd, $sql_email);
            mysqli_stmt_bind_param($request_email, 's', $user_email);
            mysqli_stmt_execute($request_email);
            $result_email = mysqli_stmt_get_result($request_email);

            // Second request
            $sql_password = "SELECT users_password FROM users WHERE users_email = ? LIMIT 1";
            $request_password = mysqli_prepare($connection_bdd, $sql_password);
            mysqli_stmt_bind_param($request_password, 's', $user_email);
            mysqli_stmt_execute($request_password);
            $result_password = mysqli_stmt_get_result($request_password);

            // Verification of values entered by the user
            if (mysqli_num_rows($result_email) === 0 && mysqli_num_rows($result_password) === 0) {
                die("Le mot de passe ou l'adresse e-mail n'est pas dans la base de données.");
            }
            
            while (($row_email = mysqli_fetch_assoc($result_email)) && ($row_password = mysqli_fetch_assoc($result_password))) {
                if ($user_email === $row_email['users_email']) {
                    // If password does not exist in database
                    if (!password_verify($user_password, $row_password['users_password'])) {
                        die("Votre mot de passe est incorrect. <br> <a href=\"index.php\" target=\"_blank\">Se connecter</a>");
                    }
                    
                    // Start a session to save user data
                    session_start();
                    $_SESSION["user_email"] = $user_email;

                    // Redirect to the page (my_account.php)
                    header("Location: ./my_account.php");
                    exit();
                }
            }
            
            // Close the first prepared request
            mysqli_stmt_close($request_email);
            // Close the second prepared request
            mysqli_stmt_close($request_password);

            // Close the database connection
            mysqli_close($connection_bdd);

            // If the user does not have an account or if login details are incorrect
            die("Vous ne possédez pas de compte. <br> <a href=\"sign_up.php\" target=\"_blank\">Créer un compte</a>");
        }
    }
}
?>

<body class="sign-up-page bg-light">
    <div class="container d-flex flex-column align-items-center p-4">
        <h1>Se connecter</h1>

        <form action="index.php" method="post" class="w-50 p-2">
            <div class="d-flex justify-content-center p-1">
                <a href="sign_up.php" target="_blank" class="text-primary">Créer un compte</a>
            </div>
            <div class="form-group">
                <label for="user_email">Adresse e-mail</label>
                <input type="email" class="form-control" id="user_email" name="user_email" required="required">
            </div>
            <div class= "form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="user_password" required="required">
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-primary" name="send-form">Envoyer</button>
            </div>
        </form>
    </div>
</body>