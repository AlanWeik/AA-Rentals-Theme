<?php
get_header(); ?>


<div id="primary" class="row-fluid">
    <div id="content" role="main" class="span8 offset2">
        <?php
        // Hämta innehållet från din statiska sida
        while ( have_posts() ) :
            the_post();
            the_content(); // Visa innehållet från din statiska startsida
        endwhile;
        ?>
    </div><!-- #content -->
     
</div><!-- #primary -->

<?php get_footer(); ?>
