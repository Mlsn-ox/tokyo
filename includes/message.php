<?php
$messages = [
    'form_error' => 'Veuillez renseigner toutes les informations demandées.',
    'article_added' => 'Spot ajouté avec succès.',
    'article_updated' => 'Spot mis à jour.',
    'article_error' => "Erreur lors de l'ajout du spot, veuillez réessayer plus tard.",
    'user_added' => 'Inscription terminée, connectez-vous !',
    'signup_error' => "Cet email est déjà lié à un compte.",
    'mail_error' => 'Email incorrect.',
    'login_error' => 'Mot de passe incorrect.',
    'img_error' => "Erreur lors du chargement de l'image.",
    'map_error' => "Erreur lors de l'enregistrement des coordonnées.",
    'connect_error' => "Veuillez vous connecter.",
    'article_not_find' => "Erreur : spot introuvable",
    'user_not_find' => "Erreur : utilisateur inconnu.",
    'param_not_find' => "Erreur lors de la récupération des données.",
    'server_error' => 'Erreur serveur, veuillez réessayer.',
    'generic_error' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
];

// Fonction pour récupérer le message correspondant au code
function getMessage($code) {
    global $messages; // Permet d'accéder au tableau défini hors de la fonction
    return isset($messages[$code]) ? $messages[$code] : $messages['generic_error'];  // Message générique si le code n'existe pas
}
?>