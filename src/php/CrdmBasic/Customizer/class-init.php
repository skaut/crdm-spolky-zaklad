<?php
/**
 * Contains the Init class.
 *
 * @package crdm-basic
 */

declare( strict_types=1 );

namespace CrdmBasic\Customizer;

use Kirki;

/**
 * Initializes the customizer
 *
 * Adds the panel, all the sections and controls to the customizer.
 */
class Init {

	const CONFIG_ID = 'crdm_basic';

	/**
	 * Init class constructor
	 *
	 * Initializes the Kirki framework and adds the panel, all the sections and controls.
	 */
	public function __construct() {
		$this->init_kirki();
		$this->init_panel();
		$this->init_sections_and_controls();
	}

	/**
	 * Initializes Kirki
	 *
	 * Initializes the Kirki framework used to manage the customizer.
	 */
	protected function init_kirki() {
		Kirki::add_config(
			self::CONFIG_ID,
			[
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			]
		);
	}

	/**
	 * Initializes the panel
	 *
	 * Adds the theme panel to the customizer.
	 */
	protected function init_panel() {
		Kirki::add_panel(
			self::CONFIG_ID . '_theme',
			[
				'title' => esc_attr__( 'Child theme options', 'crdm-basic' ),
			]
		);
	}

	/**
	 * Initializes panel content
	 *
	 * Adds all the sections and their controls to the panel.
	 */
	protected function init_sections_and_controls() {
		( new Preset() );
		( new Color_Variant( self::CONFIG_ID, self::CONFIG_ID . '_theme' ) );
		( new Colors() );
		( new Background() );
		( new Border_Radius() );
		( new Menu( self::CONFIG_ID, self::CONFIG_ID . '_theme' ) );
		( new Sidebar( self::CONFIG_ID, self::CONFIG_ID . '_theme' ) );
		( new Page_Header( self::CONFIG_ID, self::CONFIG_ID . '_theme' ) );
		( new Content( self::CONFIG_ID, self::CONFIG_ID . '_theme' ) );
		( new Footer( self::CONFIG_ID, self::CONFIG_ID . '_theme' ) );
	}

	/**
	 * Checks for GeneratePress module
	 *
	 * Checks whether GeneratePress premium is installed and a module is enabled.
	 *
	 * @param string $module The name of the module.
	 *
	 * $return bool Whether the module is enabled.
	 */
	public static function generatepress_module_enabled( $module ) {
		if ( ! function_exists( 'generatepress_is_module_active' ) ) {
			return false;
		}
		switch ( $module ) {
			case 'generate_package_backgrounds':
				$definition = 'GENERATE_BACKGROUNDS';
				break;
			default:
				return false;
		}
		return generatepress_is_module_active( $module, $definition );
	}
}
