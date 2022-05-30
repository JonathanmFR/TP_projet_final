<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>connexion</title>
</head>
<body>
    <?php
    include_once('utils.php'); 
    include_once('src/session.php');
    sessionExist();

    if(empty($_GET["id"])) return header("Location: blog.php");
    check_and_display_error();
    ?>
    <?php $id = $_GET["id"]; echo "<form action='src/edit.php?id=$id' method='post'>"; ?>
        <p>
            <label for="title">Titre</label>            
            <?php
                $id = $_GET["id"];

                require_once('src/bdd.php');
    
                try {
                    $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
                    $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 
            
                } catch(Exception $e) {
                    echo $e->getMessage();
                    exit;
                }

                $rqt = "SELECT * FROM article WHERE id = '$id' LIMIT 1;"; 

                $requete_preparee = $bdd->prepare($rqt); 
        
                $requete_preparee->execute();
                
                $data = $requete_preparee->fetchAll();
                    
                $title = $data[0]['titre'];
                $content = $data[0]['corps'];

                echo "<input type='text' name='title' value='$title' required></p>";

                echo "<p><label for='content'>Contenu</label><textarea id='content' name='content' placeholder='Votre texte'>$content</textarea></p>";
            ?>
        <button type="submit">Modifiez article</button>
    </form>
</body>
</html>