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

    $id = $_GET["id"];

    require_once('src/bdd.php');

    try {
        $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
        $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

    } catch(Exception $e) {
        echo $e->getMessage();
        exit;
    }

    $rqt = "SELECT id_article, corps FROM avis WHERE id = '$id' LIMIT 1;";

    $requete_preparee = $bdd->prepare($rqt); 

    $requete_preparee->execute();
    
    $data = $requete_preparee->fetchAll();
    
    $content = $data[0]['corps'];

    $ArticleId = $data[0]['id_article'];

    $id = $_GET["id"]; 

    echo "<form action='src/editAvis.php?id=$id&id_article=$ArticleId' method='post'>"; 
    echo "<p><label for='content'>Contenu</label><textarea id='content' name='content' placeholder='Votre texte'>$content</textarea></p>";
    echo "<button type='submit'>Modifiez votre avis</button></form>";
?>
</body>
</html>