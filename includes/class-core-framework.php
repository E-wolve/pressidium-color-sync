<?php
/**
 * Core Framework Integration
 *
 * @package PressidiumColorSync
 */

namespace PressidiumColorSync;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Core_Framework {

	public function is_active(): bool {
		return function_exists( 'CoreFramework' );
	}

	/**
	 * Geeft alle lichte CF kleuren terug als [name, raw].
	 */
	public function get_colors(): array {
		$stored = get_option( 'core_framework_colors', [] );

		if ( ! is_array( $stored ) ) {
			return [];
		}

		$colors = [];
		foreach ( $stored as $color ) {
			if ( empty( $color['name'] ) || ! empty( $color['dark'] ) ) {
				continue;
			}
			$colors[] = [
				'name' => $color['name'],
				'raw'  => $color['raw'] ?? $color['value'] ?? $color['color'] ?? '',
			];
		}

		return $colors;
	}

	/**
	 * Berekent de smart mapping van CF kleuren naar CC kleur-eigenschappen.
	 *
	 * Kerngedachte:
	 * - CF genereert naast basis-kleuren ook automatisch afgeleide tint-variabelen:
	 *   --bg-body-l-1 t/m -l-4 (lichter) en -d-1 t/m -d-4 (donkerder).
	 * - Voor toggles en vlakken zijn deze afgeleiden beter geschikt dan de basis.
	 * - border-primary (rgba 8%) is als toggle-kleur bijna onzichtbaar → gebruik bg-l-2.
	 * - overlay-bg gebruikt color-mix() zodat hij donker is op donkere thema's.
	 *
	 * @return array { 'cc-property' => 'CSS-waarde' }
	 */
	public function get_smart_mapping(): array {
		$colors = $this->get_colors();

		if ( empty( $colors ) ) {
			return [];
		}

		// Zoek de eerste CF kleur waarvan de naam een keyword bevat.
		$find = function ( array $keywords ) use ( $colors ): ?string {
			foreach ( $keywords as $kw ) {
				foreach ( $colors as $color ) {
					if ( strpos( strtolower( $color['name'] ), strtolower( $kw ) ) !== false ) {
						return 'var(--' . $color['name'] . ')';
					}
				}
			}
			return null;
		};

		$bg        = $find( [ 'bg-body', 'background', 'bg', 'white', 'licht' ] );
		$text      = $find( [ 'text-body', 'text', 'foreground', 'body', 'dark', 'donker' ] );
		$primary   = $find( [ 'primary', 'brand', 'accent', 'main', 'primair' ] );
		$secondary = $find( [ 'secondary', 'secondair' ] );
		$border    = $find( [ 'border-primary', 'border', 'muted', 'divider', 'stroke' ] );
		$light     = $find( [ 'light', 'licht', 'surface', 'card' ] ) ?? $bg;

		// Extraheer de variabelenaam uit var(--name) zodat we afgeleide tints kunnen bouwen.
		// CF genereert automatisch --{name}-l-1 t/m -l-4 (lichter) voor elke basiskleur.
		$bg_name = '';
		if ( $bg && preg_match( '/^var\(--([^,)]+)/', $bg, $m ) ) {
			$bg_name = $m[1]; // bijv. 'bg-body'
		}

		// Afgeleide tints van de achtergrondkleur (altijd beschikbaar als CF actief is)
		$bg_l1 = $bg_name ? 'var(--' . $bg_name . '-l-1)' : null;
		$bg_l2 = $bg_name ? 'var(--' . $bg_name . '-l-2)' : null;

		$mapping = [];
		$sec      = $secondary ?? $light;

		// ── Achtergrond en lichte elementen ──
		if ( $bg ) {
			$mapping['bg']                      = $bg;
			$mapping['btn-primary-text']        = $bg;
			$mapping['btn-primary-hover-text']  = $bg;
			$mapping['btn-floating-icon']       = $bg;
			$mapping['btn-floating-hover-icon'] = $bg;
		}

		// De toggle-knob is donker (bg-body kleur). Het icoon erin moet licht zijn.
		if ( $text ) {
			$mapping['toggle-knob-bg']       = $bg ?? 'var(--cc-bg)';
			$mapping['toggle-knob-icon-color'] = $text;
		}

		// ── Tekst en donkere elementen ──
		if ( $text ) {
			$mapping['text']                     = $text;
			$mapping['btn-secondary-text']       = $text;
			$mapping['btn-secondary-hover-text'] = $text;
			$mapping['block-text']               = $text;
			$mapping['btn-primary-hover-bg']     = $text;
			$mapping['btn-floating-hover-bg']    = $text;
		}

		// ── Primaire kleur ──
		if ( $primary ) {
			$mapping['btn-primary-bg']            = $primary;
			$mapping['toggle-bg-on']              = $primary;
			$mapping['webkit-scrollbar-bg-hover'] = $primary;
			$mapping['btn-floating-bg']           = $primary;
		}

		// ── Secondaire kleur ──
		if ( $sec ) {
			$mapping['btn-secondary-bg']       = $sec;
			$mapping['btn-secondary-hover-bg'] = $sec;
		}

		// ── Toggles (off/readonly): border-primary is vaak rgba met lage alpha.
		//    Gebruik de lichtere achtergrond-tint zodat de toggle zichtbaar blijft. ──
		$toggle_off      = $bg_l2 ?: $border ?: '#8fa8d6';
		$toggle_readonly = $bg_l1 ?: $border ?: '#cbd8f1';

		$mapping['toggle-bg-off']      = $toggle_off;
		$mapping['toggle-bg-readonly'] = $toggle_readonly;

		// ── Randen: border-primary is prima (subtiele lijn in donker thema) ──
		if ( $border ) {
			$mapping['section-border']      = $border;
			$mapping['cookie-table-border'] = $border;
			$mapping['webkit-scrollbar-bg'] = $border;
		}

		// ── Cookie categorie-blokken: gebruik lichtere achtergrond-tint, niet wit. ──
		$mapping['cookie-category-block-bg']       = $bg_l1 ?: $light ?: '#ebeff9';
		$mapping['cookie-category-block-bg-hover'] = $bg_l2 ?: $light ?: '#dbe5f9';

		// ── Overlay: color-mix() maakt automatisch een donkere overlay op donkere thema's. ──
		$mapping['overlay-bg'] = $bg
			? 'color-mix(in srgb, ' . $bg . ' 85%, transparent)'
			: 'rgba(230, 235, 255, .85)';

		return $mapping;
	}
}
