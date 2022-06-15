<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
</head>
<body>
    <?php // Ajouter un bouton modifier l'article sur chaque article avec le titre de l'article

    include_once('src/session.php');
    sessionExist();

    if(!empty($_SESSION['email'])) {

        echo '<a href="blog.php">Retour au Blog</a>';
        echo "<a href='src/logout.php'>Se déconnecter</a>";
    
            try {
                // Connexion à la base de donnée : 
                require_once('src/bdd.php');
    
                $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
                $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

                $author_mail = $_SESSION['email'];

                $target_list_avis = "SELECT * FROM avis WHERE titre = '$author_mail'";

                $requete_preparee3 = $bdd->prepare($target_list_avis); 
    
                $requete_preparee3->execute();
                
                $data3 = $requete_preparee3->fetchAll();

                echo "<h1>Mes avis :</h1>";

                $count = 0;
        
                while($count < count($data3)){

                    $author = $data3[$count]['titre'];

                    $content = $data3[$count]['corps'];
    
                    $id_article = $data3[$count]['id_article'];
        
                    echo "<div style='border-radius: 24px; border: 3px solid rgb(255 0 0)'>";
                    echo "<h2>Auteur : $author</h2>";
                    echo "<h4>Avis : $content</h4>";
                    echo "<a href='afficheArticle.php?id=$id_article'>Aller sur l'article</a>";
                    echo "</div><br>";

                    $count += 1;
                }
    
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