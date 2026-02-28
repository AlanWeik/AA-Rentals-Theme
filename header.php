<?php
/**
 * Theme header template - Laddar header från Page
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
?>

<?php
// Ladda header-sidan med slug "header"
$header_page = get_page_by_path( 'header' );

// Rendera sidan
if ( $header_page ) {
    $content = $header_page->post_content;
    
    // Rendera block-innehållet
    if ( function_exists( 'do_blocks' ) ) {
        echo do_blocks( $content );
    } else {
        echo apply_filters( 'the_content', $content );
    }
} else {
    // Fallback om sidan inte hittas
    echo '<!-- Header page not found -->';
}
?>

<!-- Sidans innehåll fortsätter här -->