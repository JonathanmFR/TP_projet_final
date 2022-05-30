<?php 

$errors =  [
    "empty" => "Tous les champs doivent être renseignés",
    "password" => "Les mots de passes doivent être identiques", 
    "email" => "L'email est mal formé", 
    "duplicate" => "Cette adresse email est déjà enregistrée", 
    "invalid" => "Adresse mail ou mot de passe invalide", 
    "invalid_pass" => "Le mot de passe doit contenir au moins 8 caractères, une lettre min et maj, un chiffre, un caractère spécial"
    
];

/**
 * Redirige vers la page indiquée en paramètre, en donnant en paramètre (GET) un status=error et le type d'erreur 
 * 
 * Les types d'erreurs sont : 
 *  - empty : un des champs est vide
 *  - email : l'email est mal formée
 *  - password : le mot de passe ne correspond pas à sa confirmation
 *  - ... 
 * Attention, l'appel à cette fonction quitte le script en cours 
 * 
 * @param string $location l'url de redirection 
 * @param string $error La clé de l'erreur que l'on renvoie
 * @return void
 */
function redirect_with_error(string $location, string $error ) {
    header("Location: $location?status=error&error=$error");
    exit; //

}

/**
 * Vérifie s'il existe une erreur passée en paramètre (GET) du script et affiche un élément HTML (div) contenant l'erreur
 *
 * @return void
 */
function check_and_display_error() {
    // Pas très beau, mais fonctionnel ! Challenge : trouvez comment faire mieux !!!
    global $errors; // On rappelle la variable globale $errors définie plus haut
    if(!empty($_GET["error"])) {
        $err = $_GET["error"]; 
        echo "<div class='error'>";
        echo $errors[$err];
        echo "</div>";
    }
}

/**
 * Indique si le mot de passe respecte les règles suivantes : 
 *  - au moins huit caractères
 *  - au moins une lettre majuscule
 *  - au moins une lettre miniscule 
 *  - au moins chiffre
 *  - au moins un caractère spécial
 *
 * @param string $password
 * @return boolean true si le mot passe de respecte les règles
 */
function isValidPassword(string $password) {
    $hasNum = false; 
    $hasMaj = false; 
    $hasMin = false; 
    $hasSpec = false; 
    $isLongEnought = strlen($password) >= 8; 
    $spec_char = ",;:!ù*=)_-('\"é&² ~#{[|`\\^@]}¤?./§%µ+°";

    for($i = 0; $i < strlen($password); $i++) {
        $char = $password[$i];
        if($char >= 'A' && $char <= 'Z') {
            $hasMaj = true;
        }
        if($char >= 'a' && $char <= 'z') {
            $hasMin = true;
        }
        if($char >= '0' && $char <= '9') {
            $hasNum = true;
        }
        if(strstr($spec_char, $char)) {
            $hasSpec = true; 
        }
    } 

    return $hasMaj && $hasMin && $hasNum && $hasSpec && $isLongEnought; 
}