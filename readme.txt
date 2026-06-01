=== Pressidium Color Sync ===
Contributors: ewolve
Tags: core framework, pressidium, cookie consent, colors, sync
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Synchroniseer Core Framework kleuren automatisch naar Pressidium Cookie Consent.

== Description ==

Pressidium Color Sync voegt "Core Framework" toe als kleurenpalet-optie in de Pressidium Cookie Consent admin. Selecteer het en de banner kleuren worden automatisch afgestemd op jouw merkidentiteit uit Core Framework.

= Features =

* "Core Framework" optie in de bestaande kleurenpalet dropdown van Cookie Consent
* Smart mapping op basis van kleur-namen (primary, bg, text, secondary, border)
* Auto-sync: kleuren worden automatisch bijgewerkt zodra Core Framework kleuren wijzigen
* Geen aparte configuratiepagina nodig

= Requirements =

* WordPress 6.0 of hoger
* PHP 7.4 of hoger
* Core Framework plugin (actief)
* Pressidium Cookie Consent plugin (actief)

== Installation ==

1. Upload de plugin map naar `/wp-content/plugins/pressidium-color-sync/`
2. Activeer de plugin via het 'Plugins' scherm in WordPress
3. Zorg dat Core Framework en Pressidium Cookie Consent actief zijn
4. Ga naar Cookie Consent instellingen en kies "Core Framework" in de kleurenpalet dropdown

== Frequently Asked Questions ==

= Welke kleuren worden automatisch herkend? =

De plugin matcht op kleur-namen in Core Framework. Kleuren met "primary" in de naam gaan naar de primaire knoppen, "bg" of "white" naar de banner achtergrond, "text" of "dark" naar de tekstkleur, "secondary" naar de secondaire knoppen, en "border" naar randen en dividers.

= Wat als mijn CF kleuren andere namen hebben? =

De plugin matcht partieel (case-insensitief), dus "brand-primary" matcht ook op "primary". Als de namen erg afwijken, pas je de kleuren na het toepassen handmatig bij via de individuele kleur-pickers in de Cookie Consent admin.

= Worden de Cookie Consent kleuren overschreven bij elke CF wijziging? =

Alleen als de auto-sync actief is. De auto-sync werkt zodra de `core_framework_colors` optie wordt bijgewerkt (via de CF admin of REST API).

== Changelog ==

= 1.0.0 =
* Eerste release
* Smart mapping op basis van CF kleur-namen
* "Core Framework" optie in CC palette dropdown via JavaScript injectie
* Auto-sync bij CF kleur-updates

== Upgrade Notice ==

= 1.0.0 =
Eerste release van Pressidium Color Sync.
