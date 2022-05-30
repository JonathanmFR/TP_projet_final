<?php

    function createSession($email){
        
        session_start();
        
        if(empty($email)) {
            header('Location: .login_form.php');
            exit;
        }
        
        $email = htmlspecialchars($email);
        $_SESSION["email"] = $email;

        echo "\nBienvenue $email. Je t'ai créé la session : " . session_id() . " <a href='../blog.php'>Direction le blog</a>";
    }

    function sessionExist(){
        session_start(); 
        if(empty($_SESSION["email"])) {
            header('Location: ./loginpage.php');
            exit;
        }
    }

    function deconnexion(){
        session_start();
        session_unset();
        session_destroy(); 

        setcookie(session_name(), '', strtotime("-1 day"));
        header('Location: ../loginpage.php');
        exit;
    }
?>
