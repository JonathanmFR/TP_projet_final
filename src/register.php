<?php 

include_once('../utils.php'); 


    // 1-  Traiter les champs de formulaire
    if( empty($_POST['email']) || empty($_POST['password']) || empty($_POST['conf_password'])) {
        // Informer que les champs sont vides 
        redirect_with_error("../registerpage.php", "empty");
    } 
    $email = htmlspecialchars($_POST['email']); 
    $password = $_POST['password'];
    $conf = $_POST['conf_password']; 

    // 1.1 - Valider la conformité de l'email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL )) {
        redirect_with_error("../registerpage.php","email");
    }
    // 1.2 - vérifier le que la confirmation est correcte
    if($_POST["password"] !== $_POST["conf_password"]) {
        redirect_with_error("../registerpage.php","password"); 
    }
    if(!isValidPassword($password)) {
        redirect_with_error("../registerpage.php","invalid_pass"); 
    }
    // 1.3 - hasher le mot de passe 
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // 2- Si erreur, informer l'utilisateur de l'erreur 


    // Connexion à la base de donnée : 
    require_once('bdd.php');
    try {
        $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
        $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

    } catch(Exception $e) {
        echo $e->getMessage();
        exit;
    }


    // Préparation de la requête d'insertion dans la base de données

    $rqt = "INSERT INTO utilisateur(email, password) VALUES (:email, :password);"; 

    try {
        $requete_preparee = $bdd->prepare($rqt); 
    
        // Associer les paramètres : 
        $requete_preparee->bindParam(":email", $email); 
        $requete_preparee->bindParam(':password', $hash); 
        $requete_preparee->execute();

        require_once("./session.php");

        createSession($email);
        
    } catch (Exception $e) {
        
        if($e->getCode() == 23000 ) {
            redirect_with_error("../registerpage.php","duplicate");
        }

    }

    header('Location: ../loginpage.php?status=registered');

    