<?php
/**
 * Admin Class
 *
 * @package PressidiumColorSync
 */

namespace PressidiumColorSync;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	const CC_PAGE_SLUG = 'pressidium-cookie-consent';

	private Core_Framework $cf;

	public function __construct( Core_Framework $cf ) {
		$this->cf = $cf;
		$this->init_hooks();
	}

	private function init_hooks(): void {
		add_action( 'wp_head', [ $this, 'inject_frontend_css' ], 99 );
		add_action( 'admin_notices', [ $this, 'render_sync_notice' ] );
		add_action( 'admin_footer', [ $this, 'render_dropdown_script' ] );
	}

	private function is_cc_admin_page(): bool {
		return is_admin()
			&& isset( $_GET['page'] ) // phpcs:ignore WordPress.Security.NonceVerification
			&& $_GET['page'] === self::CC_PAGE_SLUG; // phpcs:ignore WordPress.Security.NonceVerification
	}

	/**
	 * Injecteert CSS op de frontend die CC's --cc-* variabelen overschrijft met CF kleuren.
	 */
	public function inject_frontend_css(): void {
		$mapping = $this->cf->get_smart_mapping();

		if ( empty( $mapping ) ) {
			return;
		}

		$vars = '';
		foreach ( $mapping as $property => $value ) {
			$vars .= '--cc-' . esc_attr( $property ) . ':' . esc_attr( $value ) . ';';
		}

		echo '<style id="pcs-cf-sync">' . "\n";
		// CC-variabelen overschrijven (.pressidium-cc-theme staat op <body>)
		echo '.pressidium-cc-theme{' . $vars . '}' . "\n";
		// Bricks/theme-kleuren overschrijven voor CC-knoppen.
		// Alle .c-bn knoppen krijgen secondaire kleuren als basis;
		// de eerste knop in elke button-container krijgt de primaire kleur.
		echo '#cc--main .c-bn.has-background{background-color:var(--cc-btn-secondary-bg)!important}' . "\n";
		echo '#cc--main .c-bn.has-text-color{color:var(--cc-btn-secondary-text)!important}' . "\n";
		echo '#cc--main #c-bns button:first-child.has-background,#cc--main #s-bns button:first-child.has-background{background-color:var(--cc-btn-primary-bg)!important}' . "\n";
		echo '#cc--main #c-bns button:first-child.has-text-color,#cc--main #s-bns button:first-child.has-text-color{color:var(--cc-btn-primary-text)!important}' . "\n";
		echo '</style>' . "\n";
	}

	/**
	 * Status notice op de CC admin pagina.
	 */
	public function render_sync_notice(): void {
		if ( ! $this->is_cc_admin_page() ) {
			return;
		}

		$count = count( $this->cf->get_smart_mapping() );

		if ( $count === 0 ) {
			echo '<div class="notice notice-warning"><p>'
				. '<strong>Pressidium Color Sync:</strong> '
				. esc_html__( 'Geen kleuren gevonden in Core Framework.', 'pressidium-color-sync' )
				. '</p></div>';
			return;
		}

		echo '<div class="notice notice-success"><p>'
			. '🎨 <strong>Core Framework Color Sync actief</strong> — '
			. sprintf(
				esc_html__( '%d kleuren worden automatisch overgenomen op de frontend.', 'pressidium-color-sync' ),
				$count
			)
			. '</p></div>';
	}

	/**
	 * Injecteert "Core Framework (actief)" als informatieve optie in de CC palette dropdown.
	 */
	public function render_dropdown_script(): void {
		if ( ! $this->is_cc_admin_page() ) {
			return;
		}

		if ( empty( $this->cf->get_colors() ) ) {
			return;
		}
		?>
		<script>
		( function () {
			var injected = false;

			function inject() {
				if ( injected ) return;
				var selects = document.querySelectorAll( 'select.components-select-control__input' );
				for ( var i = 0; i < selects.length; i++ ) {
					var opts = selects[ i ].options;
					for ( var j = 0; j < opts.length; j++ ) {
						if ( /^(Light|Dark)\s/i.test( opts[ j ].text ) ) {
							if ( ! selects[ i ].querySelector( 'option[value="core-framework"]' ) ) {
								var opt = document.createElement( 'option' );
								opt.value = 'core-framework';
								opt.text  = '🎨 Core Framework (actief)';
								selects[ i ].insertBefore( opt, selects[ i ].firstChild );
							}
							injected = true;
							return;
						}
					}
				}
			}

			var observer = new MutationObserver( inject );
			observer.observe( document.body, { childList: true, subtree: true } );
			setTimeout( function () { observer.disconnect(); }, 30000 );
			inject();
		} )();
		</script>
		<?php
	}
}
