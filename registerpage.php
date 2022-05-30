<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>s'inscrire</title>
</head>
<body>
    <h1>S'inscrire</h1>
    <?php session_start(); ?>
    <form action="src/register.php" method="post">
        <p><label for="email">Email</label><input type="email" name="email"  required></p>

        <p><label for="password">Password</label><input type="password" name="password"  required></p>

        <p><label for="conf_password">Confirmation de mot de passe</label><input type="password" name="conf_password" required/></p>

        <button type="submit">S'inscrire</button>
        <a href="loginpage.php">Vous avez déjà un compte ?</a>
    </form>
</body>
</html>