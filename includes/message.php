<?php
$messages = [
    'form_error' => 'Veuillez renseigner toutes les informations demandées.',
    'article_added' => 'Spot ajouté avec succès.',
    'comment_added' => 'Commentaire ajouté avec succès.',
    'article_updated' => 'Spot mis à jour.',
    'article_error' => "Erreur lors de l'ajout du spot, veuillez réessayer plus tard.",
    'user_added' => 'Inscription terminée, connectez-vous !',
    'signup_error' => "Cet email est déjà lié à un compte.",
    'mail_error' => 'Email incorrect.',
    'login_error' => 'Mot de passe incorrect.',
    'img_error' => "Erreur lors du chargement de l'image.",
    'img_too_big' => "Image trop volumineuse.",
    'img_wrong_ext' => "Le format de l'image n'est pas valide.",
    'map_error' => "Erreur lors de l'enregistrement des coordonnées.",
    'connect_error' => "Veuillez vous connecter.",
    'article_not_found' => "Erreur : spot introuvable",
    'user_not_found' => "Erreur : utilisateur inconnu.",
    'param_not_found' => "Erreur lors de la récupération des données.",
    'server_error' => 'Erreur serveur, veuillez réessayer.',
    'redirect_error' => 'Déconnexion requise pour accéder à cette page.',
    'deconnected' => 'Vous avez été déconnecté.',
    'generic_error' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
];

// Fonction pour récupérer le message correspondant au code
function getMessage($code) {
    global $messages; // Permet d'accéder au tableau défini hors de la fonction
    return isset($messages[$code]) ? $messages[$code] : $messages['generic_error'];  // Message générique si le code n'existe pas
}

?>