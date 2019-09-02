<?php declare( strict_types=1 );

namespace Crdm\Front;

final class Init {

	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'load_all_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_all_scripts' ] );

		add_action( 'generate_before_header', [ $this, 'before_header' ] );
		add_action( 'generate_after_header', [ $this, 'after_header' ] );

		add_action(
			'generate_after_header',
			function () {
				// remove default page header image
				remove_action( 'generate_after_header', 'generate_featured_page_header', 10 );
			},
			1
		);
		add_action( 'generate_after_header', [ $this, 'custom_page_header_image' ], 25 );

		add_filter(
			'generate_show_title',
			function ( $show ) {
				// hide default main title, if page has featured image
				if ( is_page() && has_post_thumbnail() ) {
					return false;
				}

				return $show;
			},
			20
		);

		add_action(
			'generate_footer',
			function () {
				// hide copyright
				remove_action( 'generate_footer', 'generate_construct_footer' );
			},
			1
		);
	}

	public function load_all_styles() {
		if ( is_rtl() ) {
			wp_enqueue_style( 'generatepress-rtl', CRDM_BASIC_PARENT_TEMPLATE_URL . 'rtl.css', [], CRDM_BASIC_APP_VERSION );
		}

		wp_enqueue_style(
			'crdm-main',
			CRDM_BASIC_TEMPLATE_URL . 'frontend/index.css',
			[ 'generate-style' ],
			CRDM_BASIC_APP_VERSION
		);
	}

	public function load_all_scripts() {
		wp_enqueue_script(
			'crdm-main',
			CRDM_BASIC_TEMPLATE_URL . 'frontend/index.js',
			[ 'jquery' ],
			CRDM_BASIC_APP_VERSION,
			true
		);
	}

	public function before_header() {
		?>
		<div class="crdm_header">
		<?php
	}

	public function after_header() {
		?>
		<div class="crdm_header__bg_1 grid-container grid-parent"></div>
		<div class="crdm_header__bg_2-container grid-container grid-parent">
			<div class="crdm_header__bg_2-container-content"></div>
		</div>
		<div class="crdm_header__bg_3 grid-container grid-parent"></div>
		</div>
		<?php
	}

	public function custom_page_header_image() {
		if ( function_exists( 'generate_page_header' ) ) {
			return;
		}

		if ( ! is_page() ) {
			return;
		}

		if ( ! has_post_thumbnail() ) {
			return;
		}

		?>
		<div class="page-header-image crdm_page-header grid-container grid-parent">
			<?php
			the_post_thumbnail(
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				apply_filters( 'generate_page_header_default_size', 'full' ),
				[
					'itemprop' => 'image',
					'alt'      => the_title_attribute( 'echo=0' ),
				]
			);
			?>
			<div class="crdm_page-header_captions">
				<?php
				do_action( 'crdm_basic_before_page_header_title' );
				?>
				<h1><?php the_title(); ?></h1>
				<?php
				do_action( 'crdm_basic_after_page_header_title' );
				?>
			</div>
		</div>
		<?php
	}

}
