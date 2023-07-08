<?php   

// Include the content of the page (header.php)
require_once("./header.php") ;


if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['user_last_name']) && !empty($_POST['user_last_name'])){

        if(isset($_POST['user_first_name']) && !empty($_POST['user_first_name'])){

            if(($_POST['user_civility']) !== 'choisir'){
                
                if(isset($_POST['user_email']) && !empty($_POST['user_email'])){

                    if(isset($_POST['user_password']) && !empty($_POST['user_password'])){
                        // If all inputs content value execute this code 
                        if(strlen($_POST['user_last_name']) > 50 || strlen($_POST['user_first_name']) > 50 || strlen($_POST['user_civility']) > 50 || strlen($_POST['user_email']) > 50 || strlen($_POST['user_password']) > 50 ){
                            die("<p>Chaque valeur doit contenir moins de 50 caractères.<br> <a href=\"./sign_up.php\">Retour</a></p>\n") ;
                        }

                        $user_last_name  = htmlspecialchars($_POST['user_last_name']) ;
                        $user_first_name = htmlspecialchars($_POST['user_first_name']) ;
                        $user_civility   = intval(htmlspecialchars($_POST['user_civility'])) ; // Convert this value to an integer
                        $user_email      = htmlspecialchars($_POST['user_email']) ;
                        $user_password   = htmlspecialchars($_POST['user_password']) ;
                        // security of password
                        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT) ;

                        // Database connection function
                        require_once("./connection.php") ;

                        // Retrieve the number of table columns (users)
                        // $users_number_columns = SELECT COUNT(*) as num_columns FROM information_schema.columns WHERE table_schema = 'todo_list_php'AND table_name = 'users' ;

                        $sql = "INSERT INTO users (users_last_name, users_first_name, id_civility, users_email, users_password) VALUES (?, ?, ?, ?, ?)" ;
                        $request_prepared = mysqli_prepare($connection_bdd, $sql) ;
                        mysqli_stmt_bind_param($request_prepared, 'ssiss', $user_last_name, $user_first_name, $user_civility, $user_email, $hashed_password) ;
                        mysqli_stmt_execute($request_prepared) ;

                        mysqli_stmt_store_result($request_prepared) ;
                        
                        if (mysqli_stmt_errno($request_prepared) !== 0) {
                            die("Erreur MySQL : " . mysqli_stmt_error($request_prepared));
                        }
                        
                        $civility = ($user_civility === 1)? "M": "Mme" ;

                        mysqli_close($connection_bdd) ;
                        mysqli_stmt_close($request_prepared) ;

                        // Login to save user data
                        session_start();
                        $_SESSION['user_email'] = $user_email ;

                        header("location: my_account.php") ;
                        exit() ;
                    }
                }
            }
        }
    }

    else{
        die("<p>Remplisser tous les champs.<br> <a href=\"./sign_up.php\">Retour</a></p>\n") ;
    }
}

?>

<div class="sign-up-page">
    <h1>Créer un compte</h1>
    <form action="sign_up.php" method="post">
        <div>
            <label for="user_last_name">Nom</label>
            <input type="text" id="user_last_name" name="user_last_name" required="required">
        </div>
        <div>
            <label for="user_first_name">Prénom</label>
            <input type="text" id="user_email" name="user_first_name" required="required">
        </div>
        <div>
            <select name="user_civility">
                <option value="choisir" selected disabled>Choisir</option>
                <?php 
                    require_once("./connection.php") ;
                    $sql = "SELECT id_civility, civility_name FROM civility" ;
                    $request = mysqli_query($connection_bdd, $sql) ;
                    
                    // If the request is not valid
                    if(!$request){
                        die("<p>Problème lié à la requete sql permettant de communiquer avec la colonne (users_civility) dans la base de donée.<br> <a href=\"./sign_up.php\">Retour</a></p>\n") ;
                    }

                    while($row = mysqli_fetch_assoc($request)){
                        echo("<option value=\"{$row['id_civility']}\">". $row['civility_name'] ."</option>\n") ;
                    }

                    mysqli_close($connection_bdd) ;
                ?>
            </select>
        </div>
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