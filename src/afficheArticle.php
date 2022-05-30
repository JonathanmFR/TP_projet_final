<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
</head>
<body>
    <?php // Ajouter un bouton modifier l'article sur chaque article avec le titre de l'article
    session_start(); 
    if(!empty($_GET["id"])) {

        echo '<a href="blog.php">Retour au Blog</a>';
        echo "<a href='src/logout.php'>Se déconnecter</a>";
    
            try {
                // Connexion à la base de donnée : 
                require_once('src/bdd.php');
    
                $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
                $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 
    
                $id = $_GET["id"];

                $rqt = "SELECT * FROM article WHERE id = '$id';"; 
                $requete_preparee = $bdd->prepare($rqt); 
                $requete_preparee->execute();
                $data = $requete_preparee->fetchAll();
                    
                $title = $data[0]['titre'];
                $content = $data[0]['corps'];
                $createdDate = $data[0]['created_at'];
                $authorID = $data[0]['id_utilisateur'];

                $target_user = "SELECT email FROM utilisateur WHERE id = '$authorID'";

                $requete_preparee3 = $bdd->prepare($target_user); 
    
                $requete_preparee3->execute();
                
                $data3 = $requete_preparee3->fetchAll();
    
                $author = $data3[0]['email'];

                echo "<br><h1>$title</h1>";
                echo "<h3>Créé le $createdDate par $author</h3>";
                echo "<p>$content</p>";

    
            } catch (Exception $e) {
                
                if($e->getCode() == 23000 ) { // Le code 23000 correspond à une entrée dupliquée :cela signifie que l'adresse mail est déjà en bdd
                    redirect_with_error("../register_form.php","duplicate");
                }
            }
    
        include_once('utils.php'); 
        check_and_display_error();
    }else header("Location: blog.php");
    ?>
</body>
</html>