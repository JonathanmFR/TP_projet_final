<?php 

include_once('../utils.php');
include_once('session.php');

    sessionExist();
    // 1-  Traiter les champs de formulaire
    if(empty($_POST['content'])) {
        // Informer que les champs sont vides 
        redirect_with_error("../afficheArticle.php", "empty"); // EDIT L'ERROR
    }

    $content = htmlspecialchars($_POST['content']);

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

    $rqt = "INSERT INTO avis(titre, corps, created_at, id_article, id_auteur) VALUES (:titre, :corps, :created_at, :id_article, :id_auteur);"; 

    $email = $_SESSION['email'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL )) {
        redirect_with_error("blog.php","email");
    }

    $id_article = $_GET["id"];

    $userid = $_GET["userid"];

    $rqt_user = "SELECT * FROM article WHERE id='$id_article';";

    try {
        $exec_user = $bdd->prepare($rqt_user);

        $exec_user->execute();

        $data = $exec_user->fetchAll();

        $requete_preparee = $bdd->prepare($rqt); 
    
        $now = new DateTime();
        $format = $now->format('Y/m/d');

        $id = $data[0]["id"];

        // Associer les paramètres : 
        $requete_preparee->bindParam(":titre", $email); 
        $requete_preparee->bindParam(':corps', $content); 
        $requete_preparee->bindParam(':created_at', $format);
        $requete_preparee->bindParam(':id_article', $id);
        $requete_preparee->bindParam(':id_auteur', $userid);
        $requete_preparee->execute();
        
    } catch (Exception $e) {
        
        if($e->getCode() == 23000 ) {
            redirect_with_error("../createArticle.php","duplicate");
        }
    }

    header("Location: ../afficheArticle.php?id=$id_article");

?>