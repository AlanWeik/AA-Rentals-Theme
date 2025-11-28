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

function remove_woocommerce_short_description() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
}
add_action( 'template_redirect', 'remove_woocommerce_short_description' );


// ==== BOKNINGSSTATUSAR FÖR WOOCOMMERCE ====
// 1) Registrera custom-statusar
add_action('init', function () {
    register_post_status('wc-confirmed', [
        'label'                     => 'Bekräftad',
        'public'                    => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Bekräftad (%s)', 'Bekräftad (%s)')
    ]);
    register_post_status('wc-awaiting-payment', [
        'label'                     => 'Inväntar betalning',
        'public'                    => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Inväntar betalning (%s)', 'Inväntar betalning (%s)')
    ]);
    register_post_status('wc-on-rent', [
        'label'                     => 'Ute hos kund',
        'public'                    => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Ute hos kund (%s)', 'Ute hos kund (%s)')
    ]);
});

// 2) Visa dem i listan + bestäm ordning och byt etiketter på standardstatusar
add_filter('wc_order_statuses', function ($st) {
    // Döp om befintliga
    if (isset($st['wc-on-hold']))    $st['wc-on-hold']    = 'Inväntar bekräftelse';
    if (isset($st['wc-processing'])) $st['wc-processing'] = 'Betald';
    if (isset($st['wc-completed']))  $st['wc-completed']  = 'Återlämnad / Avslutad';

    // Bygg ny ordning: on-hold -> confirmed -> awaiting-payment -> processing -> on-rent -> completed
    $ordered = [];
    foreach ($st as $key => $label) {
        $ordered[$key] = $label;
        if ($key === 'wc-on-hold') {
            $ordered['wc-confirmed']        = 'Bekräftad';
            $ordered['wc-awaiting-payment'] = 'Inväntar betalning';
        }
        if ($key === 'wc-processing') {
            $ordered['wc-on-rent']          = 'Ute hos kund';
        }
    }
    // Fallback om nycklar saknas
    $ordered += array_diff_key(
        ['wc-confirmed'=>'Bekräftad','wc-awaiting-payment'=>'Inväntar betalning','wc-on-rent'=>'Ute hos kund'],
        $ordered
    );
    return $ordered;
});

// Kortare helper
function aa_add_customer_note($order, $msg){
    if ($order instanceof WC_Order) {
        $order->add_order_note(wp_kses_post($msg), true); // true = skicka till kund
    }
}

// Skicka automatiska meddelanden vid statusbyte
add_action('woocommerce_order_status_changed', function($order_id, $from, $to, $order){
    if (!$order) return;

    switch ($to) {
        case 'confirmed': // Bekräftad
            aa_add_customer_note($order,
                "Din bokning är <strong>bekräftad</strong> 🎉<br>Vi återkommer om leverans/upphämtning. Spara detta mail.");
            break;

        case 'awaiting-payment': // Inväntar betalning
            aa_add_customer_note($order,
                "Din bokning är bekräftad. <strong>Inväntar betalning</strong>.<br>Betala via Swish 123-456 78 90 (märk ordernr), eller enligt överenskommelse.");
            break;

        case 'on-rent': // Ute hos kund
            aa_add_customer_note($order,
                "Utrustningen är nu <strong>ute hos er</strong>. Vid frågor/support – svara på detta mail eller ring oss.");
            break;
    }
}, 10, 4);

add_action('woocommerce_thankyou_cod', function($order_id){
    if ($order = wc_get_order($order_id)) {
        $order->update_status('on-hold','Bokningsförfrågan mottagen – inväntar bekräftelse.');
    }
});

