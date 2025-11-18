<?php
/**
 * AA Theme Functions
 * 
 * Här hanteras funktionaliteten för temat.
 */

// Funktion för att ladda huvudtemats CSS och JavaScript
function hudvard_ecom_enqueue_scripts() {
    wp_enqueue_style('AA-style', get_stylesheet_uri());    
wp_enqueue_script('AA-script', get_template_directory_uri() . '/js/main.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'hudvard_ecom_enqueue_scripts');

// Funktion för att ladda Font Awesome
function hudvard_ecom_enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'hudvard_ecom_enqueue_font_awesome');

// Funktion för att ladda Google Fonts
function hudvard_ecom_enqueue_google_fonts() {
    $font_families = [
        'Inter:wght@300;400;500;600;700;800',
        'Space+Grotesk:wght@400;500;600;700',
        'Poppins:wght@400;500;600;700;800',
        'Quicksand:wght@400;500;600;700',
        'Comfortaa:wght@400;500;600;700',
        'JetBrains+Mono:wght@400;500;600;700',
    ];

    $family_query = implode('&family=', $font_families);
    $font_url = 'https://fonts.googleapis.com/css2?family=' . $family_query . '&display=swap';

    wp_enqueue_style('hudvard-google-fonts', $font_url, [], null);
}
add_action('wp_enqueue_scripts', 'hudvard_ecom_enqueue_google_fonts');

// Registrera menyer
function hudvard_ecom_register_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'hudvard-ecom'),
    ));
}
add_action('after_setup_theme', 'hudvard_ecom_register_menus');


// Stöd för WooCommerce
add_theme_support( 'woocommerce' );

function hudvard_ecom_setup() {
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'hudvard_ecom_setup');

add_filter('woocommerce_product_single_add_to_cart_text', function($text){
    return 'Boka';
});
add_filter('woocommerce_product_add_to_cart_text', function($text){
    return 'Boka';
});
?>

<?php
// Custom global colors för Kadence Blocks / Design Library
function mytheme_kadence_global_colors( $global_colors ) {
    $global_colors = array(
        '--global-palette1' => '#E9FF41', // Accent / Highlight
        '--global-palette2' => '#074ef3',
        '--global-palette3' => '#1b202c', // Dark
        '--global-palette4' => '#2f3749',
        '--global-palette5' => '#4a5566',
        '--global-palette6' => '#717f98',
        '--global-palette7' => '#eef2f5',
        '--global-palette8' => '#f8f9fd',
        '--global-palette9' => '#ffffff', // Light
    );

    return $global_colors;
}
add_filter( 'kadence_blocks_pattern_global_colors', 'mytheme_kadence_global_colors' );