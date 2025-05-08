<?php
/* Template name: Partnerzy */
get_header(); 
while ( have_posts()) : the_post();
?>
<div id="page-partnerzy">
    <?php get_template_part('include/home/partnerzy'); ?>
</div>
<?php 
endwhile;
get_template_part('include/module/about-project');
get_template_part('include/module/wez-udzial');
get_footer();
?>