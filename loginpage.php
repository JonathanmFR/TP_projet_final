<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>connexion</title>
    </head>
<body>
    <?php session_start();

        if(!empty($_GET["status"])) {
            if($_GET["status"] == "registered") {
                echo "<div>Connexion réussie</div>";
            }
        }

    ?>
        <form action="src/login.php" method="post">
            <p><label for="email">Email</label><input type="email" name="email" required></p>
            <p><label for="password">Password</label><input type="password" name="password" required></p>
            <button type="submit">Connexion</button>
            <a href="registerpage.php">S'inscrire</a>
        </form>
    </body>
</html>