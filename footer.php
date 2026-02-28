<?php
/*-----------------------------------------------------------------------------------*/
/* This template will be called by all other template files to finish 
/* rendering the page and display the footer area/content
/*-----------------------------------------------------------------------------------*/
?>

</main><!-- / end page container, begun in the header -->

<!-- Din manuella footer -->
<footer class="site-footer">
    <?php
    // Använd WP_Query för att hämta sidan med titeln "Footer"
    $footer_query = new WP_Query(array(
        'post_type' => 'page',
        'title'     => 'Footer', // Byt ut 'Footer' om sidan har ett annat namn
        'posts_per_page' => 1,
    ));

    if ($footer_query->have_posts()) {
        while ($footer_query->have_posts()) {
            $footer_query->the_post();
            the_content(); // Visa innehållet på sidan
        }
        wp_reset_postdata(); // Återställ global $post-data
    } else {
        echo '<p>Footer-sidan är inte definierad eller hittad.</p>';
    }
    ?>
</footer>

<?php wp_footer();
// This fxn allows plugins to insert themselves/scripts/css/files (right here) into the footer of your website. 
// Removing this fxn call will disable all kinds of plugins. 
// Move it if you like, but keep it around.
?>

</body>
</html>
