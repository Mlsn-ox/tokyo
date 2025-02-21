<?php
$messages = [
    'article_added' => 'Article ajouté avec succès.',
    'user_added' => 'Inscription terminée, connectez-vous !',
    'article_error' => `Erreur lors de l'ajout de l'article, veuillez réessayer plus tard.`,
    'server_error' => 'Erreur serveur, veuillez réessayer.',
    'form_error' => 'Veuillez renseigner toutes les informations demandées.',
    'login_error' => 'Mot de passe incorrect.',
    'mail_error' => 'Email incorrect.',
    'img_error' => `Erreur lors du chargement de l'image.`,
    'map_error' => `Erreur lors de l'enregistrement des coordonnées.`,
    'signup_error' => `Cet email est déjà lié à un compte.`,
    'connect_error' => `Veuillez vous connecter.`,
    'article_not_find' => `Erreur : article introuvable`,
    'user_not_find' => `Erreur : utilisateur inconnu.`,
    'generic_error' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
];

// Fonction pour récupérer le message correspondant au code
function getMessage($code) {
    global $messages;
    return isset($messages[$code]) ? $messages[$code] : $messages['generic_error'];  // Message générique si le code n'existe pas
}
?>