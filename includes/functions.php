<?php
/**
 * Retourne un émoji en fonction de la catégorie
 * @param string catégorie
 * @return string émoji
 */
function getEmojiCategory($cat){
    switch ($cat) {
        case 'gastronomie':
            return "🍜";
        case 'loisir':
            return "🎳";
        case 'shopping':
            return "🛍";
        case 'panorama':
            return "📷";
        default:
            return "🎎";
    }
}

function getAge($birthdate)
{
    $now = new DateTime();
    $birth = new DateTime($birthdate);
    $age = $now->format('Y') - $birth->format('Y');
    // Si l'anniversaire n'est pas encore passé cette année, on retire 1
    if (
        $now->format('m') < $birth->format('m') ||
        ($now->format('m') == $birth->format('m') && $now->format('d') < $birth->format('d'))
    ) {
        $age--;
    }
    return $age;
}

function getMessage($code) {
    $messages = [
        'form_error' => 'Veuillez renseigner toutes les informations demandées.',
        'article_added' => 'Spot ajouté avec succès.',
        'name_invalid' => "Nom d'utilisateur invalide.",
        'name_taken' => "Nom d'utilisateur déjà utilisé.",
        'identity_invalid' => "Prénom ou nom invalide.",
        'captcha_invalid' => "Erreur de captcha.",
        'birthdate_error' => 'Date de naissance invalide.',
        'psw_invalid' => 'Mot de passe invalide.',
        'comment_added' => 'Commentaire ajouté avec succès.',
        'article_updated' => 'Spot mis à jour.',
        'user_updated' => 'Informations mises à jour.',
        'comment_updated' => 'Commentaire mis à jour.',
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
        'unauthorized' => 'Accès non autorisé.',
        'deleted' => 'Élément supprimé',
        'reject_first' => 'Élément en ligne, suppression impossible',
        'generic_error' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
    ];    
    return isset($messages[$code]) ? $messages[$code] : $messages['generic_error'];  
}
?>
