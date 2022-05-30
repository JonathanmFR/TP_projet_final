<!DOCTYPE html>
<?php 
    include_once('src/session.php');
    sessionExist() 
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Article</title>
</head>
<body>
    <a href='blog.php'>Blog</a>
    <a href='src/logout.php'>Se déconnecter</a>
    <h1>Créer un article</h1>
    <?php
    include_once('utils.php');
    check_and_display_error();
    ?>
    <form action="src/create.php" method="post">
        <p><label for="title">Titre</label><input type="text" name="title" required></p>
        <p><label for="content">Contenu</label><textarea id="content" name="content" placeholder="Votre texte"></textarea></p> 
        <button type="submit">Créer l'article</button>
    </form>
</body>
</html>