<?php 

// Include the content of the page (header.php)
require_once("./header.php") ;

if($_SERVER['REQUEST_METHOD'] === "POST"){

    if(isset($_POST['user_email']) && !empty($_POST['user_email'])){

        if(isset($_POST['user_password']) && !empty($_POST['user_password'])){
            $user_email    = htmlspecialchars($_POST["user_email"]) ;
            $user_password = htmlspecialchars($_POST["user_password"]) ;
            $error_lenght_caracacters_input = "doit contenir moins de 50 caractères." ;

            if(strlen($user_email) > 50){
                die("Votre adresse email : ".$user_email." ".$error_lenght_caracacters_input) ;
            }
            if(strlen($user_password) > 50){
                die("Votre mot de passe : ".$user_password." ".$error_lenght_caracacters_input) ;
            }
            // Include the content of the page (connection.php)
            // Database connection
            require_once("./connection.php") ;

            // Check if the user has an account
            $sql = "SELECT users_email,users_password FROM users WHERE users_email = ? AND users_password = ?" ;
            $request_prepared = mysqli_prepare($connection_bdd, $sql) ;
            mysqli_stmt_bind_param($request_prepared, 'ss', $user_email, $user_password) ;
            mysqli_stmt_execute($request_prepared) ;

            // Save the requesst prepared on the memory for check If users_email or user_password are not exist on the database
            mysqli_stmt_store_result($request_prepared) ;
             
            if(mysqli_stmt_num_rows($request_prepared) === 0){
                die("Vous ne posséder pas de compte, <a href=\"sign_up.php\">Créer un compte</a>") ;
            }
            
            mysqli_stmt_close($request_prepared) ;
            mysqli_close($connection_bdd) ;
        }
    }
}


?>

<div class="sign-up-page">
    <h1>Se connecter</h1>
    <form action="log_in.php" method="post">
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