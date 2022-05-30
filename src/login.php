<?php
include_once('../utils.php');

// Étape 1 : récupérer les données de formulaires : $_POST
$email = htmlspecialchars($_POST["email"]); 
$password = $_POST["password"];

if(!filter_var($email, FILTER_VALIDATE_EMAIL )) {
    redirect_with_error("../loginpage.php","email");
}

// Étape 2 : connexion à la base 

try {
    include_once('bdd.php');
    $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
    $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 
} catch(Exception $e) {

    http_response_code(500);
    exit; 
}

// Étape 3 récupération de l'utilisateur (*) à partir de son email 

$rqt = "SELECT * FROM utilisateur WHERE email=:email"; 
try {

    $requete_preparee = $bdd->prepare($rqt); 
    $requete_preparee->bindParam(':email', $email); 
    $requete_preparee->execute(); 
    $user = $requete_preparee->fetch(PDO::FETCH_ASSOC);
} catch(Exception $e) {

    http_response_code(500);
    exit; 
}

if(empty($user)) {
    redirect_with_error("../loginpage.php", "invalid");
}

// Étape 4 : vérification du mot de passe 
$hash = $user["password"]; 

if(!password_verify($password, $hash)) {
    // 4.1 : si erreur on lui indique 
    redirect_with_error("../loginpage.php", "invalid");
}

require_once("./session.php");

createSession($email);

