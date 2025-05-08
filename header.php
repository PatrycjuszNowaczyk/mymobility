<?php
/**
 * @package WordPress
 * @subpackage Template
 * @since W 1.0
 */
$favicon = get_bloginfo('template_directory') . '/img';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta content="Dominik Sobel / Patrycjusz Nowaczyk" name="author">
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php wp_title(); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>?v=24-11-2022" type="text/css">
<link rel="apple-touch-icon" sizes="57x57" href="<?= $favicon; ?>/apple-icon-57x57.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
<?php wp_head(); ?> 
</head>
<body id="<?php if ( is_home()) { echo "homepage"; } else { echo "subpage"; } ?>" <?php body_class(); ?>>
<div class="conteiner"> 
	<?php include get_template_directory() . '/nav.php';	?>