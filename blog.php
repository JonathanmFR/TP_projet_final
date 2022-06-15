<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
</head>
<body>
    <a href='blog.php'>Blog</a>

    <h1>Bienvenue sur mon blog</h1>
    <br>
    <a href="createArticle.php">Ajouter un article</a>
    <a href="viewAvis.php">Tous mes avis</a>
    <a href="dashboard.php">Panel d'administration des avis</a>

    <?php // Ajouter un bouton modifier l'article sur chaque article avec le titre de l'article
    session_start(); 

    if(!empty($_GET["status"])) {
        if($_GET["status"] == "created") {
            echo "<div class='created' style='color: red'>Votre article a bien été créé</div>";
        }else if($_GET["status"] == "edited") {
            echo "<div class='created' style='color: red'>Votre article a bien été modifié</div>";
        }
    }

    function communityCreation($bdd, $id){
        echo "<h2>Community :</h2>";

        $requeteAll;

        $requeteAll = "SELECT * FROM article WHERE id_utilisateur != '$id'";

        if(!$id) $requeteAll = "SELECT * FROM article";

        $reqPrepa2 = $bdd->prepare($requeteAll); 
        $reqPrepa2->execute();
        $data2 = $reqPrepa2->fetchAll();

        $count2 = 0;

        if(count($data2) === 0) echo("<h3>Aucun autre article</h3>"); 

        while($count2 < count($data2)){
            
            $author_id = $data2[$count2]['id_utilisateur'];
            $target_user = "SELECT email FROM utilisateur WHERE id = '$author_id' LIMIT 1";
            $requete_preparee3 = $bdd->prepare($target_user); 
            $requete_preparee3->execute();
            $data3 = $requete_preparee3->fetchAll();

            $author = $data3[0]['email'];

            $update = $data2[$count2]['updated_at'];

            if($update === NULL) $update = "Jamais";

            $title2 = $data2[$count2]['titre'];
            $createdDate2 = $data2[$count2]['created_at'];
            $article_id2 = $data2[$count2]['id'];
            echo "<div style='border-radius: 24px; border: 3px solid rgb(255 0 0)'><a href='afficheArticle.php?id=$article_id2'><h3>$title2</h3><h5>Créé le : $createdDate2 par $author, Dernière modification : $update</h5></a></div><br>";
            $count2 += 1;
        }
    }

    if(!empty($_SESSION) && !empty($_SESSION["email"])) { // Faire une requête qui récupère tous les articles qu'a fait l'utilisateur et afficher en dessous les articles de tout le monde du moment que author n'est pas égal à lui
        echo "<a href='src/logout.php'>Se déconnecter</a>";
        echo "<h2>Vos articles :</h2>";
        try {
            // Connexion à la base de donnée : 
            require_once('src/bdd.php');

            $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
            $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

            $email = $_SESSION['email'];

            $rqt_user = "SELECT * FROM utilisateur WHERE email='$email';";

            $exec_user = $bdd->prepare($rqt_user);

            $exec_user->execute();

            $data = $exec_user->fetchAll();

            $id = $data[0]["id"];

            $rqt = "SELECT * FROM article WHERE id_utilisateur = '$id';"; 

            $requete_preparee = $bdd->prepare($rqt); 
    
            $requete_preparee->execute();
            
            $data = $requete_preparee->fetchAll();

            $row = 0;

            while($row < count($data)){
                
                $title = $data[$row]['titre'];
                $createdDate = $data[$row]['created_at'];
                $article_id = $data[$row]['id'];
                $update = $data[$row]['updated_at'];

                if($update === NULL) $update = "Aucune modification";

                echo "<div style='border-radius: 24px; border: 5px solid rgb(255 0 0)'><a href='afficheArticle.php?id=$article_id'><h3>$title</h3><h5>Créé le : $createdDate par Vous, Dernière modification : $update</h5></a></div>";
                echo "<a href='editArticle.php?id=$article_id'>Modifier l'article</a><br>";
                $row += 1;
            }

            communityCreation($bdd, $id);

        } catch (Exception $e) {
            
            if($e->getCode() == 23000 ) { // Le code 23000 correspond à une entrée dupliquée :cela signifie que l'adresse mail est déjà en bdd
                redirect_with_error("../registerpage.php","duplicate");
            }
        }
    }else{
        require_once('src/bdd.php');

        echo "<a href='loginpage.php'>Se connecter</a>";

        $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
        $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options);

        communityCreation($bdd, NULL);
    }

    include_once('utils.php'); 
    check_and_display_error();
    ?>
</body>
</html>