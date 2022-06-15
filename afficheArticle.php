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
                    
                $article_id = $data[0]['id'];

                $title = $data[0]['titre'];
                $content = $data[0]['corps'];
                $createdDate = $data[0]['created_at'];
                $authorID = $data[0]['id_utilisateur'];

                $target_user = "SELECT id, email FROM utilisateur WHERE id = '$authorID'";

                $requete_preparee3 = $bdd->prepare($target_user); 
    
                $requete_preparee3->execute();
                
                $data3 = $requete_preparee3->fetchAll();
    
                $author = $data3[0]['email'];

                echo "<br><h1>$title</h1>";
                echo "<h3>Créé le $createdDate par $author</h3>";
                echo "<p>$content</p>";

                $listAvis = "SELECT * FROM avis WHERE id_article = '$article_id';"; 

                $requete_preparee5 = $bdd->prepare($listAvis); 
    
                $requete_preparee5->execute();
                
                $data5 = $requete_preparee5->fetchAll();

                $count = 0;
        
                if(count($data5) === 0) echo("<h3>Aucun Avis</h3>"); 
        
                while($count < count($data5)){
                    
                    $id_avis = $data5[$count]['id'];

                    $author = $data5[$count]['titre'];
        
                    $content = $data5[$count]['corps'];
        
                    $date = $data5[$count]['created_at'];

                    $update = $data5[$count]['updated_at'];
        
                    if($update === NULL) $update = "Jamais";
        
                    echo "<div style='border-radius: 24px; border: 3px solid rgb(255 0 0)'><h3>$author</h3><p>$content</p><h5>Créé le : $date, Dernière modification : $update</h5>";
                    if(!empty($_SESSION['email']) && $author == $_SESSION['email']) echo "<a href='editPageAvis.php?id=$id_avis'>Modifier l'avis</a>";
                    echo "</div><br>";
                    $count += 1;
                }

                if(!empty($_SESSION['email'])){
                    $emailSession = $_SESSION['email'];

                    $mainUser = "SELECT id FROM utilisateur WHERE email = '$emailSession'";

                    $requete_preparee4 = $bdd->prepare($mainUser); 
        
                    $requete_preparee4->execute();
                    
                    $mainUserData = $requete_preparee4->fetchAll();
        
                    $mainUser = $mainUserData[0]['id'];
    
                    if($authorID !== $mainUser){
                        echo "<form action='src/createAvis.php?id=$article_id&userid=$mainUser' method='post'>";
                        echo "<p><label for='content'>Contenu</label><textarea id='content' name='content' placeholder='Votre avis'></textarea></p>";
                        echo "<button type='submit'>Ajouter un avis</button>";
                        echo "</form>";
                    }
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