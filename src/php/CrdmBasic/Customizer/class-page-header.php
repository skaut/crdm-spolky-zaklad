<?php
/**
 * Contains the Page_Header class
 *
 * @package crdm-basic
 */

declare( strict_types=1 );

namespace CrdmBasic\Customizer;

use Kirki;

/**
 * Header configuration
 *
 * This class sets up all the customizer options for configuring the header of the webpage.
 */
class Page_Header {

	/**
	 * The ID of the configuration set ("crdm-basic").
	 *
	 * @var string $config_id
	 */
	protected $config_id = '';
	/**
	 * The ID of the panel in which this option is displayed.
	 *
	 * @var string $panel_id
	 */
	protected $panel_id = '';
	/**
	 * The ID of the section in which this option is displayed.
	 *
	 * @var string $section_id
	 */
	protected $section_id = '';

	/**
	 * Page_Header class constructor
	 *
	 * Adds the section and its controls to the customizer.
	 *
	 * @param string $config_id The ID of the configuration set ("crdm-basic").
	 * @param string $panel_id The ID of the panel in which this option is displayed.
	 */
	public function __construct( string $config_id, string $panel_id ) {
		$this->config_id  = $config_id;
		$this->panel_id   = $panel_id;
		$this->section_id = $panel_id . '_pageHeader';

		$this->init_section();
		$this->init_controls();
	}

	/**
	 * Initializes the section
	 *
	 * Adds the section to the customizer.
	 */
	protected function init_section() {
		Kirki::add_section(
			$this->section_id,
			array(
				'title'       => esc_attr__( 'Page header', 'crdm-basic' ),
				'description' => esc_attr__( 'Header with featured image and heading', 'crdm-basic' ),
				'panel'       => $this->panel_id,
			)
		);
	}

	/**
	 * Initializes the controls
	 *
	 * Adds all the controls to the section
	 */
	protected function init_controls() {
		Kirki::add_field(
			$this->config_id,
			array(
				'type'      => 'background',
				'settings'  => 'pageHeaderBg',
				'label'     => esc_attr__( 'Textbox background color', 'crdm-basic' ),
				'section'   => $this->section_id,
				'default'   => array(
					'background-color'      => 'rgba(196, 219, 122, 0.79)',
					'background-image'      => '',
					'background-repeat'     => 'no-repeat',
					'background-position'   => 'left top',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'output'    => array(
					array(
						'element' => '.crdm_page-header_captions',
					),
				),
				'transport' => 'auto',
			)
		);

		Kirki::add_field(
			$this->config_id,
			array(
				'type'        => 'spacing',
				'settings'    => 'pageHeaderPosition',
				'label'       => esc_attr__( 'Textbox position', 'crdm-basic' ),
				'description' => esc_attr__( 'Including units, e. g. "10px"', 'crdm-basic' ),
				'section'     => $this->section_id,
				'default'     => array(
					'left'   => '0',
					'right'  => 'auto',
					'top'    => 'auto',
					'bottom' => '1.5em',
				),
				'output'      => array(
					array(
						'element' => '.crdm_page-header_captions',
					),
				),
				'transport'   => 'auto',
			)
		);

		Kirki::add_field(
			$this->config_id,
			array(
				'type'      => 'typography',
				'settings'  => 'pageHeaderH1Font',
				'label'     => esc_attr__( 'Heading', 'crdm-basic' ),
				'section'   => $this->section_id,
				'default'   => array(
					'font-family'    => 'PT Sans',
					'variant'        => '700',
					'font-size'      => '1.8rem',
					'line-height'    => '1.125',
					'letter-spacing' => 'inherit',
					'color'          => '#3c2314',
					'text-align'     => 'left',
					'text-transform' => 'none',
				),
				'output'    => array(
					array(
						'element' => '.crdm_page-header_captions h1',
					),
				),
				'transport' => 'auto',
			)
		);
	}

}
