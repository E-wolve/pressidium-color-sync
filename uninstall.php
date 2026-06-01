<?php
/**
 * Uninstall Script
 *
 * @package PressidiumColorSync
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// De plugin slaat geen eigen opties op.
// De kleuren in pressidium_cookie_consent_settings laten we staan —
// die zijn eigendom van de Cookie Consent plugin en de gebruiker.
