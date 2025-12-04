<?php
/**
 * AA Theme Functions
 * REN OCH STÄDAD VERSION
 */

// 1. SETUP & THEME SUPPORT
function aa_theme_setup() {
    add_theme_support('custom-logo');
    add_theme_support( 'woocommerce' );
    
    // Viktigt för Gutenberg
    add_theme_support( 'editor-styles' ); 
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
}
add_action('after_setup_theme', 'aa_theme_setup');


// 2. LADDA SKRIPT & STYLES
function aa_theme_enqueue_scripts() {
    // Style.css
    wp_enqueue_style('aa-style', get_stylesheet_uri());    

    // Main.js - Ladda INTE i admin/editor (Detta stoppar frysningen)
    if ( ! is_admin() ) {
        wp_enqueue_script('aa-script', get_template_directory_uri() . '/js/main.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'aa_theme_enqueue_scripts');

// Fonter
function aa_theme_enqueue_fonts() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    
    $font_families = [
        'Inter:wght@300;400;500;600;700;800',
        'Space+Grotesk:wght@400;500;600;700',
        'Poppins:wght@400;500;600;700;800',
        'Quicksand:wght@400;500;600;700',
        'Comfortaa:wght@400;500;600;700',
        'JetBrains+Mono:wght@400;500;600;700',
        'Manrope:wght@300;400;500;600;700;800',
    ];
    $family_query = implode('&family=', $font_families);
    $font_url = 'https://fonts.googleapis.com/css2?family=' . $family_query . '&display=swap';

    wp_enqueue_style('aa-google-fonts', $font_url, [], null);
}
add_action('wp_enqueue_scripts', 'aa_theme_enqueue_fonts');


// 3. MENYER
function aa_theme_register_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'aa-theme'),
    ));
}
add_action('after_setup_theme', 'aa_theme_register_menus');


// 4. WOOCOMMERCE
add_filter('woocommerce_product_single_add_to_cart_text', function($text){ return 'Boka'; });
add_filter('woocommerce_product_add_to_cart_text', function($text){ return 'Boka'; });

function aa_remove_short_description() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
}
add_action( 'template_redirect', 'aa_remove_short_description' );

// Custom statusar
add_action('init', function () {
    register_post_status('wc-confirmed', [
        'label' => 'Bekräftad', 'public' => true, 'show_in_admin_all_list' => true, 'show_in_admin_status_list' => true, 'label_count' => _n_noop('Bekräftad (%s)', 'Bekräftad (%s)')
    ]);
    register_post_status('wc-awaiting-payment', [
        'label' => 'Inväntar betalning', 'public' => true, 'show_in_admin_all_list' => true, 'show_in_admin_status_list' => true, 'label_count' => _n_noop('Inväntar betalning (%s)', 'Inväntar betalning (%s)')
    ]);
    register_post_status('wc-on-rent', [
        'label' => 'Ute hos kund', 'public' => true, 'show_in_admin_all_list' => true, 'show_in_admin_status_list' => true, 'label_count' => _n_noop('Ute hos kund (%s)', 'Ute hos kund (%s)')
    ]);
});

add_filter('wc_order_statuses', function ($st) {
    if (isset($st['wc-on-hold']))    $st['wc-on-hold']    = 'Inväntar bekräftelse';
    if (isset($st['wc-processing'])) $st['wc-processing'] = 'Betald';
    if (isset($st['wc-completed']))  $st['wc-completed']  = 'Återlämnad / Avslutad';

    $ordered = [];
    foreach ($st as $key => $label) {
        $ordered[$key] = $label;
        if ($key === 'wc-on-hold') {
            $ordered['wc-confirmed'] = 'Bekräftad';
            $ordered['wc-awaiting-payment'] = 'Inväntar betalning';
        }
        if ($key === 'wc-processing') {
            $ordered['wc-on-rent'] = 'Ute hos kund';
        }
    }
    $ordered += array_diff_key(['wc-confirmed'=>'Bekräftad','wc-awaiting-payment'=>'Inväntar betalning','wc-on-rent'=>'Ute hos kund'], $ordered);
    return $ordered;
});

function aa_add_customer_note($order, $msg){
    if ($order instanceof WC_Order) {
        $order->add_order_note(wp_kses_post($msg), true);
    }
}

add_action('woocommerce_order_status_changed', function($order_id, $from, $to, $order){
    if (!$order) return;
    switch ($to) {
        case 'confirmed': aa_add_customer_note($order, "Din bokning är <strong>bekräftad</strong> 🎉<br>Vi återkommer om leverans/upphämtning. Spara detta mail."); break;
        case 'awaiting-payment': aa_add_customer_note($order, "Din bokning är bekräftad. <strong>Inväntar betalning</strong>.<br>Betala via Swish 123-456 78 90 (märk ordernr), eller enligt överenskommelse."); break;
        case 'on-rent': aa_add_customer_note($order, "Utrustningen är nu <strong>ute hos er</strong>. Vid frågor/support – svara på detta mail eller ring oss."); break;
    }
}, 10, 4);

add_action('woocommerce_thankyou_cod', function($order_id){
    if ($order = wc_get_order($order_id)) {
        $order->update_status('on-hold','Bokningsförfrågan mottagen – inväntar bekräftelse.');
    }
});