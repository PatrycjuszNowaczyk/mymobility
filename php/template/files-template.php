<?php
function my_template_page( $template ){

   if( is_post_type_archive( 'specjalizacje' ) ){
       if( $_template = locate_template( 'template/archive/archive-specializations.php' ) ){
            $template = $_template;
       }
   } 
   if( is_singular( 'specjalizacje' ) ){
       if( $_template = locate_template( 'template/single/single-specializations.php' ) ){
            $template = $_template;
       }
   } 
   if( is_post_type_archive( 'blog' ) ){
       if( $_template = locate_template( 'template/archive/archive-blog.php' ) ){
            $template = $_template;
       }
   } 
   if( is_singular( 'blog' ) ){
       if( $_template = locate_template( 'template/single/single-blog.php' ) ){
            $template = $_template;
       }
   } 

   if( is_tax( 'kat_blog' )  ){
       if( $_template = locate_template( 'template/taxonomy/taxonomy-kat_blog.php' ) ){
            $template = $_template;
       }
   }    

   return $template;
}
add_filter( 'template_include', 'my_template_page' );