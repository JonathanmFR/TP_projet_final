<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
</head>
<body>
    <a href='blog.php'>Blog</a>

    <h1>Dashboard</h1>
    <br>
    <a href="viewAvis.php">Tous mes avis</a>

    <?php // Ajouter un bouton modifier l'article sur chaque article avec le titre de l'article
    session_start(); 

    if(!empty($_SESSION) && !empty($_SESSION["email"])) { // Faire une requête qui récupère tous les articles qu'a fait l'utilisateur et afficher en dessous les articles de tout le monde du moment que author n'est pas égal à lui
        echo "<a href='src/logout.php'>Se déconnecter</a>";
        echo "<h2>Les avis de votre communauté :</h2>";
        try {
            // Connexion à la base de donnée : 
            require_once('src/bdd.php');

            $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
            $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

            $email = $_SESSION['email'];

            $rqt_user = "SELECT id FROM utilisateur WHERE email ='$email';";

            $exec_user = $bdd->prepare($rqt_user);

            $exec_user->execute();

            $data = $exec_user->fetchAll();

            $id = $data[0]["id"];

            $rqt = "SELECT * FROM article WHERE id_utilisateur = '$id';"; 

            $requete_preparee = $bdd->prepare($rqt); 
    
            $requete_preparee->execute();
            
            $data = $requete_preparee->fetchAll();

            $row = 0;

            $row2 = 0;

            $rqt2 = "SELECT * FROM avis;"; 

            $requete_preparee2 = $bdd->prepare($rqt2); 
    
            $requete_preparee2->execute();
            
            $data3 = $requete_preparee2->fetchAll();

            function invocWhile($idArticle, $data3, $row2){
                while($row2 < count($data3)){ // Avis

                    $idAvisArticle = $data3[$row2]['id_article'];

                    $target_id = $data3[$row2]['id_auteur'];

                    if($idArticle === $idAvisArticle){

                        $createdDate = $data3[$row2]['created_at'];

                        $titre = $data3[$row2]['titre'];
    
                        $corps = $data3[$row2]['corps'];

                        $update = $data3[$row2]['updated_at'];
            
                        if($update === NULL) $update = "Jamais";

                        echo "<div style='border-radius: 24px; border: 5px solid rgb(255 0 0)'><a href='afficheArticle.php?id=$idAvisArticle'><h3>$titre</h3><p>Avis : $corps</p><h5>Créé le : $createdDate par $titre, Dernière modification : $update</h5></a></div>";
                    }
                    $row2 += 1;
                }
            }

            while($row < count($data)){ // Article

                $idArticle = $data[$row]['id'];

                invocWhile($idArticle, $data3, $row2);
                
                $row += 1;
            }

        } catch (Exception $e) {
            
            if($e->getCode() == 23000 ) { // Le code 23000 correspond à une entrée dupliquée :cela signifie que l'adresse mail est déjà en bdd
                redirect_with_error("../registerpage.php","duplicate");
            }
        }
    }else header("Location: blog.php");

    include_once('utils.php'); 
    check_and_display_error();
    ?>
</body>
</html>