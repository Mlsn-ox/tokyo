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
?>