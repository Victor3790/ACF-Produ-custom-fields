<?php
/**
 * Defines the custom field type class.
 *
 * @package WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use vk_libraries\Template;

/**
 * The produ_acf_field_taxonomies class.
 */
class Produ_ACF_Field_Taxonomies extends \acf_field {
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct() {
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'produCustomTaxonomyField';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Taxonomy tree', 'produ' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'Produ'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array();

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("FIELD_NAME", "error");
		 * ```
		 */
		$this->l10n = array(
			'error' => __( 'Error! Please enter a higher value', 'TEXTDOMAIN' ),
		);

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-FIELD-NAME directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field The field to render.
	 * @return void
	 */
	public function render_field_settings( $field ) {

		// default_value.
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Taxonomy', 'acf' ),
				'instructions' => __( 'Select the taxonomy to be displayed', 'acf' ),
				'type'         => 'select',
				'name'         => 'taxonomy',
				'choices'      => acf_get_taxonomy_labels(),
			)
		);
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {

			$field['type']     = 'select';
			$field['multiple'] = 1;
			$field['ui']       = 1;
			$field['ajax']     = 1;
			$field['choices']  = array();

			$div = array(
				'class'           => 'acf-taxonomy-field',
				'data-save'       => 1,
				'data-ftype'      => 'multi-select',
				'data-taxonomy'   => 'category',
				'data-allow_null' => 0,
			);

			$params = array(
				'attributes' => acf_esc_attrs( $div ),
				'field'      => $field,
			);

			$file     = Produ\ACF\PATH . 'templates/taxonomy-field.php';
			$template = new Template();
			$html     = $template->load( $file, $params );

			//phpcs:ignore
			echo $html;
	}


	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts() {
		$url     = trailingslashit( $this->env['url'] );
		$version = $this->env['version'];

		wp_enqueue_script(
			'produCustomTaxonomyField_general',
			Produ\ACF\URL . 'assets/taxonomy.js',
			array( 'acf-input' ),
			$version,
			true
		);

		wp_enqueue_script(
			'produCustomTaxonomyField_jsTree_script',
			Produ\ACF\URL . 'assets/jsTree/jstree.min.js',
			array( 'jquery' ),
			$version,
			true
		);

		wp_enqueue_style(
			'produCustomTaxonomyField_jsTree_style',
			Produ\ACF\URL . 'assets/jsTree/themes/default/style.min.css',
			array(),
			$version
		);

		wp_add_inline_script(
			'produCustomTaxonomyField_general',
			'const PRODU_DATA = ' .
				wp_json_encode(
					array( 'tax_endpoint' => get_rest_url( null, '/produ/v1/taxonomy/' ) )
				),
			'before'
		);
	}
}
