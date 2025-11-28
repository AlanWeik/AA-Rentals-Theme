<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Hero för produktkategorier – samma styling som single product hero
if ( is_product_category() ) :

    $term         = get_queried_object();
    $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
    $image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'full' ) : '';

    ?>
    <div class="product-hero" style="
        min-height: 40vh;
        /* <?php if ( $image_url ) : ?>
        background-image: url('<?php echo esc_url( $image_url ); ?>');
        <?php endif; ?> */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    ">
        <div style="
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        "></div>
        <div style="
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            padding: 2rem;
            width: 60%;
        ">
            <h1 style="
			    text-transform: uppercase;
                margin: 0;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            ">
                <?php single_term_title(); ?>
            </h1>
            <div style="
                font-size: 1rem;
                font-weight: bold;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            ">
                <?php
                $description = term_description();
                if ( ! empty( $description ) ) {
                    echo wp_kses_post( wpautop( $description ) );
                }
                ?>
            </div>
        </div>
    </div>
    <?php
endif;	


/**
 * Visa innehållet från butikssidan ("Shop") innan produktloopen,
 * men bara på själva butiksarkivet – inte på kategorier, etiketter osv.
 */
if ( is_shop() ) {
	$shop_page_id = wc_get_page_id( 'shop' );

	if ( $shop_page_id && $shop_page_id > 0 ) {
		$shop_page = get_post( $shop_page_id );

		if ( $shop_page && ! is_wp_error( $shop_page ) ) {
			global $post;

			// Gör så att the_content() funkar som på en vanlig sida.
			$post = $shop_page;
			setup_postdata( $post );

			echo '<div class="shop-page-content">';
				the_content();
			echo '</div>';

			wp_reset_postdata();
		}
	}
}

?>

<div class="woocommerce-archive-content">

<?php
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
 do_action( 'woocommerce_before_main_content' );

/**
 * Visa innehållet från butikssidan ("Shop") innan produktloopen,
 * men bara på själva butiksarkivet – inte på kategorier, etiketter osv.
 */
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
 do_action( 'woocommerce_after_main_content' );
?>

</div><!-- .woocommerce-archive-content -->

<?php
get_footer( 'shop' );