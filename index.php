<?php 

// Include the content of the page (header.php)
require_once("./header.php") ;

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['user_email']) && !empty($_POST['user_email'])){

        if(isset($_POST['user_password']) && !empty($_POST['user_password'])){
            $user_email    = htmlspecialchars($_POST["user_email"]) ;
            $user_password = htmlspecialchars($_POST["user_password"]) ;

            if(strlen($user_email) > 50 || strlen($user_password) > 50){
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
            $result_email = mysqli_stmt_get_result($request_email); // Save the result of the request prepared in a object

            // second request
            $sql_password = "SELECT users_password FROM users WHERE users_email = ? LIMIT 1";
            $request_password = mysqli_prepare($connection_bdd, $sql_password);
            mysqli_stmt_bind_param($request_password, 's', $user_email);
            mysqli_stmt_execute($request_password);
            $result_password = mysqli_stmt_get_result($request_password); // Save the result of the request prepared in a object

            // Verification of values entered by the user
            while (($row_email = mysqli_fetch_assoc($result_email)) && ($row_password = mysqli_fetch_assoc($result_password))) {
                if ($user_email === $row_email['users_email']) {

                    // if password does not exist in database
                    if(! password_verify($user_password, $row_password['users_password'])){
                        die("Votre mot de passe est incorrect. <br> <a href=\"index.php\">S'inscrire</a>");
                    }
                    // Login to save user data
                    session_start() ;
                    $_SESSION["user_email"] = $user_email;

                    // Redirects to the page (my_account.php)
                    header("Location: my_account.php");
                    exit();
                }
            }

            mysqli_close();

            // if the user does not have an account, or if the login details are incorrect
            die("Vous ne possédez pas de compte. <br> <a href=\"sign_up.php\">Créer un compte</a>");
        }
    }
}

?>

<div class="sign-up-page">
    <h1>Se connecter</h1>
    <div>
        <a href="log_in.php">Créer un compte</a>
    </div>
    <form action="index.php" method="post">
        <div>
            <label for="user_email">Adresse e-mail</label>
            <input type="email" id="user_email" name="user_email" required="required">
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="user_password" required="required">
        </div>
        <button type="submit" name="send-form">Envoyer</button>
    </form>
</div>