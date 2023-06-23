<?php
/**
 * Plugin Name: ACF Produ custom fields
 * Plugin URI: https://produ.com/
 * Description: Plugin to add custom fields to ACF.
 * Version: 1.0.0
 *
 * @package WordPress
 */

namespace Produ\ACF;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( __NAMESPACE__ . '\PATH' ) ) {
	define( __NAMESPACE__ . '\PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( __NAMESPACE__ . '\URL' ) ) {
	define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ ) );
}

require_once namespace\PATH . 'includes/vk_libraries/class-template.php';

/**
 * This is the main class of the plugin.
 */
class Fields {

	/**
	 * Has the class been instantiated?
	 *
	 * @var bool
	 */
	private static $instance = false;

	/**
	 * Add hooks here
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'include_custom_field_types' ) );
		add_action( 'rest_api_init', array( $this, 'add_endpoints' ) );
		add_filter( 'acf/fields/taxonomy/query', array( $this, 'customize_args_post_query' ), 10, 3 );
	}

	/**
	 * Instantiate the class
	 */
	public static function get_instance(): self {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add Produ custom field types.
	 */
	public function include_custom_field_types() {
		if ( ! function_exists( 'acf_register_field_type' ) ) {
			return;
		}

		require_once namespace\PATH . 'includes/class-produ-acf-field-taxonomies.php';

		acf_register_field_type( 'produ_acf_field_taxonomies' );
	}

	/**
	 * Customize the taxonomy query so it just returns
	 * parent sections.
	 *
	 * @param array      $args The query args. See WP_Term_Query for available args.
	 * @param array      $field The field array containing all settings.
	 * @param int|string $post_id The current post ID being edited.
	 */
	public function customize_args_post_query( $args, $field, $post_id ) {
		if ( 'produCustomTaxonomyField' !== $field['type'] ) {
			return $args;
		}

		$args['parent'] = 0;
		return $args;
	}

	/**
	 * Add custom endpoints to dispatch
	 * ajax queries.
	 */
	public function add_endpoints() {
		register_rest_route(
			'produ/v1',
			'/taxonomy/(?P<id>\d+)',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_taxonomies' ),
			),
		);
	}

	/**
	 * Returns the sub taxonomies of the
	 * parent taxnomoy identified by id
	 * as a json string.
	 *
	 * @param WP_REST_Request $request The rest rout request.
	 */
	public function get_taxonomies( $request ) {
		$id = $request->get_param( 'id' );
		$id = filter_var( $id, FILTER_VALIDATE_INT );

		$parent_category = get_categories(
			array(
				'include'    => $id,
				'fields'     => 'id=>name',
				'hide_empty' => false,
			)
		);

		$child_categories = get_categories(
			array(
				'parent'     => $id,
				'fields'     => 'id=>name',
				'hide_empty' => false,
			)
		);

		$result = array(
			'id'       => key( $parent_category ),
			'text'     => current( $parent_category ),
			'children' => array(),
		);

		foreach ( $child_categories as $id => $name ) {

			$child = array(
				'id'   => $id,
				'text' => $name,
			);

			array_push( $result['children'], $child );

		}

		$json_result = wp_json_encode( $result );

		//phpcs:ignore
		echo $json_result;
	}
}

$produ_acf_fields = Fields::get_instance();
