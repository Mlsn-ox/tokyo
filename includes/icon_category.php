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
?>