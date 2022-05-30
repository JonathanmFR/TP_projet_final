<?php 

include_once('../utils.php');
include_once('session.php');

    sessionExist();
    // 1-  Traiter les champs de formulaire
    if( empty($_POST['title']) || empty($_POST['content'])) {
        // Informer que les champs sont vides 
    redirect_with_error("../editArticle.php", "empty"); // EDIT L'ERROR
    }

    $title = str_replace("'", "\\'", htmlspecialchars($_POST['title'])); 
    $content = str_replace("'", "\\'", htmlspecialchars($_POST['content']));

    // Connexion à la base de donnée : 
    require_once('bdd.php');
    
    try {
        $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
        $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

    } catch(Exception $e) {
        http_response_code(500);
        exit; 
    }

    // Préparation de la requête d'insertion dans la base de données

    $id = htmlspecialchars($_GET["id"]);

    $now = new DateTime();
    $format = $now->format('Y/m/d');

    try {
        $rqt = "UPDATE article SET titre='$title', corps='$content', updated_at='$format' WHERE id='$id'"; 

        $exec = $bdd->prepare($rqt);

        $exec->execute();

    } catch (Exception $e) {
        echo $e;
    http_response_code(500);
    exit; 
    }

header('Location: ../blog.php?status=edited');
    