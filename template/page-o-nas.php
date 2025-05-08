<?php
/* Template name: O nas */
get_header(); 
while ( have_posts()) : the_post();
$wspolpraca = get_field('wspolpraca');
$praktyki = get_field('praktyki');
?>
<main class="section" id="page-o-nas">
    <?php
    $o_nas = get_field('o_nas');
    get_template_part('include/about-us/o-crash');
    get_template_part('include/about-us/o-projekcie');
    get_template_part('include/about-us/team');

	get_template_part('include/home/projekty');

    get_template_part('include/about-us/publikacje');
    ?>
</main>
<?php 
endwhile;
get_template_part('include/module/about-project');
get_template_part('include/module/wez-udzial');
get_footer();
?>