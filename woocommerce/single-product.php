<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php 
			// Hero Section
			global $product;
			$image_id = $product->get_image_id();
			$image_url = wp_get_attachment_image_url( $image_id, 'full' );
			?>
			
			<div class="product-hero" style="
				min-height: 30vh;
				  <?php if ( $image_url ) : ?>
        background-image: url('<?php echo esc_url( $image_url ); ?>');
        <?php endif; ?>
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
					"><?php echo esc_html( $product->get_name() ); ?></h1>
					<div style="
						font-size: 1rem;
						font-weight: bold;
						text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
					">
						<p style="margin: 0; padding: 0;">
    <?php echo strip_tags( $product->get_short_description() ); ?> 
</p>
					</div>
				</div>
			</div>

	<div class="woocommerce-single-content">

		<?php
			/**
			 * woocommerce_before_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
		?>
			<?php wc_get_template_part( 'content', 'single-product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php
			/**
			 * woocommerce_after_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		?>

	</div>

<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */