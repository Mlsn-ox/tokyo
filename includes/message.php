<?php
$messages = [
    'article_added' => 'Article ajouté avec succès.',
    'article_error' => `Erreur lors de l'ajout de l'article, veuillez réessayer plus tard.`,
    'server_error' => 'Erreur serveur, veuillez réessayer.',
    'form_error' => 'Veuillez remplir le formulaire correctement.',
    'login_error' => 'Identifiant ou mot de passe incorrect.',
    'signup_error' => `Ce nom d'utilisateur est déjà pris.`,
    'connection_require' => `Ce nom d'utilisateur est déjà pris.`,
    'img_error' => `Erreur lors du chargement de l'image.`,
    'map_error' => `Erreur lors de l'enregistrement des coordonnées.`,
    'generic_error' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
    'article_not_find' => `Erreur : article introuvable`,
];

// Fonction pour récupérer le message correspondant au code
function getMessage($code) {
    global $messages;
    return isset($messages[$code]) ? $messages[$code] : $messages['generic_error'];  // Message générique si le code n'existe pas
}
?>