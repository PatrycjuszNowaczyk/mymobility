<?php
/** ADMIN CSS ***/
function admin_wlasny_css() { ?>
<style type="text/css">

	body.modal-open {overflow:auto !important;}
	textarea.cctm_textarea { height: 150px !important; width: 300px !important; } 
	#cpt_info_box,
	#qtranxs-meta-box-lsb,
	#post-body-content * + .qtranxs-lang-switch-wrap,
	#welcome-panel,
	#welcome-panel + #dashboard-widgets-wrap #postbox-container-2,
	#welcome-panel + #dashboard-widgets-wrap #postbox-container-3,
	#welcome-panel + #dashboard-widgets-wrap #postbox-container-4,
	#welcome-panel + #dashboard-widgets-wrap #dashboard_activity,
	#welcome-panel + #dashboard-widgets-wrap #wpseo-dashboard-overview { display: none; }
	#wpadminbar,
	#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap { background: #1fa692; }
	#adminmenu a,
	#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before,
	#adminmenu div.wp-menu-image:before { color: #fff; }

	#adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu { background: #a7a7a7; }
	#adminmenu li.menu-top:hover, #adminmenu li.opensub > a.menu-top, #adminmenu li > a.menu-top:focus,
	#adminmenu a:hover { background: #000 !important; color: #fff !important; }

	#adminmenu li .dashicons-before::before { color: #fff !important; }
	#adminmenu li:hover .dashicons-before::before,
	#adminmenu li.wp-has-current-submenu .dashicons-before::before { color: #fff !important; }


	#adminmenu li a:hover, #adminmenu li:hover a .wp-menu-name, #collapse-menu, #collapse-button div::after { color: #fff !important; }



	#wpadminbar a:hover #adminbarsearch:before, #wpadminbar a:hover .ab-icon:before, #wpadminbar a:hover .ab-item:before,
	#adminmenu a:hover, #adminmenu li.wp-has-submenu:hover > a { color: #000 !important; font-weight: 600; }
	#collapse-menu,
	#collapse-button div::after,
	#adminmenu div.wp-menu-image::before,
	#adminmenu .wp-submenu a { color: #fff; }
	#adminmenu .wp-menu-image img { opacity: 1.0; }
	#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu {  background: #f1574b; }
	#wpadminbar .ab-empty-item, #wpadminbar a.ab-item, #wpadminbar > #wp-toolbar span.ab-label, #wpadminbar > #wp-toolbar span.noticon { color: #fff; }
</style>
<?php
}
add_action('admin_head', 'admin_wlasny_css');
 
 


/** WYGLAD LOGOWANIA **/
function wyglad_logowania() { ?>
<style type="text/css">
  .login #login_error, .login .message { color: #29364b; }
  body.login { background: #fff; color: #1fa692; } 
  body.login #backtoblog a, body.login #nav a { color: #1fa692; }
  body.login .message { border-color: #008645; color: #000; }
  body.login .message,
  body.login form{ background: #1fa692; border: 0; }
  body.login label { font-size: 18px; color: #fff; }
  body.login .button-primary { background: #f1574b; border-color: #f1574b; color: #fff; box-shadow: none; text-shadow: none; border-radius: 20px; }
  body.login .button-primary:hover { background: #0e5e9a !important; box-shadow: none; border-color: #0e5e9a; }
  body.login  input:focus { box-shadow: 0 0 0 1px #000, 0 0 2px 1px rgba(154, 24, 28, 0.8); }
  body.login div#login h1 a { width: 100%; height: 80px; padding-bottom: 10px; background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/img/logo-moja-migracja.svg'); background-size: auto 100%; }
</style>
<?php }
add_action( 'login_enqueue_scripts', 'wyglad_logowania' );