<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

$timber = new Timber\Timber();

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_filter( 'script_loader_tag', array( $this, 'defer_js' ), 10 );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'disable_gutenberg_editor' ) );
		add_action( 'after_setup_theme', array( $this, 'crb_load' ) );
		add_action( 'carbon_fields_register_fields', array( $this, 'crb_attach_theme_options' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	public function disable_gutenberg_editor() {
		return false;
	}

	function crb_attach_theme_options() {
		Container::make( 'post_meta', __( 'Additional' ) )
			->where('post_id', '=', get_option( 'page_on_front' ) )
			->add_fields( array(
				Field::make( 'complex', 'rcp_slider', __( 'Main Slider' ) )
					->add_fields( 'slide', array(
						Field::make( 'image', 'image' )->set_value_type( 'url' ),
						Field::make( 'text', 'caption' ),
						Field::make( 'select', 'alignx', __( 'Align X' ) )
							->add_options( array(
								'left' => __( 'Left' ),
								'center' => __( 'Center' ),
								'right' => __( 'Right' ),
							) ),
						Field::make( 'select', 'aligny', __( 'Align Y' ) )
							->add_options( array(
								'top' => __( 'Top' ),
								'center' => __( 'Center' ),
								'bottom' => __( 'Bottom' ),
							) )
					) )
			) );
	}

	function crb_load() {
		\Carbon_Fields\Carbon_Fields::boot();
	}

	public function add_scripts() {
		wp_enqueue_style( 'rc_splide_css', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/css/splide.min.css', null );
		wp_enqueue_script( 'rc_splide_js', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/js/splide.min.js', null );
		wp_enqueue_script( 'rc_site_js', get_template_directory_uri() . '/static\/site.js', array( 'jquery', 'rc_splide_js' ) );
	}

	function defer_js( $url ) {
		if ( is_user_logged_in() ) return $url; //don't break WP Admin
		if ( FALSE === strpos( $url, '.js' ) ) return $url;
		if ( strpos( $url, 'jquery.js' ) ) return $url;
		return str_replace( ' src', ' defer src', $url );
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['is_front_page'] = is_front_page();
		$context['menu']  = new Timber\Menu();
		$logo_id = get_theme_mod( 'custom_logo' );
		if (isset($logo_id)) {
			$image = wp_get_attachment_image_src( $logo_id , 'full' );
			$context['logo'] = $image[0];
		}
		$context['site']  = $this;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );

		add_theme_support( 'custom-logo' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}

new StarterSite();

function rc_send_email_to_admin() {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$message = $_POST['message'];
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$email_message = 'Nuevo mensaje de contacto de '.$name.':<br/><br/>'.$message.'<br/><br/>Puedes contactarte con la persona al correo: '.$email;
	wp_mail( 'atencionalcliente@printcorsac.com', 'Nuevo mensaje de contacto', $email_message, $headers );
	wp_redirect( home_url(  ) );
	exit();
}
add_action( 'admin_post_nopriv_contact_form', 'rc_send_email_to_admin' );
add_action( 'admin_post_contact_form', 'rc_send_email_to_admin' );