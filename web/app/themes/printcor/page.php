<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
if (is_front_page()) {
    $context['slider'] = carbon_get_post_meta( get_the_ID(), 'rcp_slider' );
    $context['clientes'] = carbon_get_post_meta( get_the_ID(), 'rcp_clientes' );
    $context['cert_image'] = carbon_get_post_meta( get_the_ID(), 'rcp_certification_image' );
    $context['cert_text'] = wpautop( carbon_get_post_meta( get_the_ID(), 'rcp_certification_text' ) );
}
Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
