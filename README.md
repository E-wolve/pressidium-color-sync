# Pressidium Color Sync

Synchroniseer [Core Framework](https://core-framework.com/) kleuren automatisch naar [Pressidium Cookie Consent](https://pressidium.com/cookie-consent/) — zonder database-wijzigingen, puur via CSS.

[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

## Hoe werkt het?

De plugin injecteert een `<style>` tag op de frontend (na CC's eigen stijlen) die de `--cc-*` CSS-variabelen van de banner overschrijft met verwijzingen naar CF-variabelen:

```css
.pressidium-cc-theme {
  --cc-bg:            var(--bg-body);
  --cc-text:          var(--text-body);
  --cc-btn-primary-bg: var(--primary);
  --cc-toggle-bg-on:  var(--primary);
  /* ... alle 27 CC-eigenschappen */
}
```

Zodra CF zijn CSS-variabelen bijwerkt (bijv. via de CF admin of dark-mode toggle), volgt de CC-banner automatisch — zonder dat je iets hoeft te doen.

**Geen database-wijzigingen.** CC-instellingen worden nooit aangeraakt.

## Kleur-mapping

De plugin matcht CF-kleurnamen op keywords:

| CF kleur-naam bevat | Gebruikt voor |
|---|---|
| `bg-body`, `bg`, `white` | Banner achtergrond, knopteksten, toggle knop |
| `text-body`, `text`, `dark` | Bannertekst, hover-achtergronden |
| `primary`, `brand`, `accent` | Primaire knop, toggle aan, zwevende knop |
| `secondary` | Secondaire knoppen |
| `border-primary`, `border`, `muted` | Randen, scrollbar |
| `light`, `surface` | Cookie categorie-blokken |

Voor **toggle-uit** en **categorie-blokken** gebruikt de plugin automatisch de afgeleide CF-tintvariabelen (`--bg-body-l-1`, `--bg-body-l-2`) die CF voor elke basiskleur genereert. Dit zorgt voor zichtbare toggles op zowel lichte als donkere thema's.

De overlay gebruikt `color-mix()` zodat hij automatisch donker of licht is afhankelijk van het thema.

## Vereisten

- WordPress 6.0+
- PHP 7.4+
- [Core Framework](https://core-framework.com/) — actief
- [Pressidium Cookie Consent](https://pressidium.com/cookie-consent/) — actief

## Installatie

1. Download de ZIP van de [laatste release](https://github.com/E-wolve/pressidium-color-sync/releases)
2. WordPress admin → Plugins → Plugin uploaden
3. Activeer — klaar

Geen configuratie nodig.

## Admin indicator

Op de Cookie Consent instellingenpagina verschijnt een groene notice die bevestigt hoeveel kleuren actief worden overgenomen. In de kleurenpalet-dropdown staat **"🎨 Core Framework (actief)"** als informatieve optie.

## 📦 Release workflow

### Nieuwe versie uitbrengen

**1. Versienummer verhogen** op 2 plekken in [pressidium-color-sync.php](pressidium-color-sync.php):

```php
 * Version: 1.1.0
define('PCS_VERSION', '1.1.0');
```

**2. Changelog bijwerken** in [readme.txt](readme.txt)

**3. Committen en pushen:**

```bash
git add .
git commit -m "Release v1.1.0"
git push origin main
```

**4. GitHub Release aanmaken:**

Ga naar [Releases](https://github.com/E-wolve/pressidium-color-sync/releases) → "Create a new release" → tag `1.1.0` → publiceer.

WordPress-installaties die de plugin hebben detecteren de nieuwe versie automatisch en bieden de update aan.

### Periodiek onderhoud

```bash
# Dependencies updaten
composer update

# Veiligheidscheck
composer audit
```

## Technische details

| Gegeven | Waarde |
|---------|--------|
| Namespace | `PressidiumColorSync` |
| Constante prefix | `PCS_` |
| Plugin slug | `pressidium-color-sync` |
| Update bron | [GitHub Releases](https://github.com/E-wolve/pressidium-color-sync/releases) |
| CF kleuren optie | `core_framework_colors` |
| Frontend CSS hook | `wp_head` priority 99 |

## Mappenstructuur

```
pressidium-color-sync/
├── pressidium-color-sync.php    (hoofdbestand)
├── uninstall.php
├── readme.txt
├── README.md
├── composer.json
├── composer.lock
├── includes/
│   ├── class-plugin.php         (singleton, dependency check)
│   ├── class-core-framework.php (CF integratie + smart mapping)
│   └── class-admin.php          (CSS injectie + admin notice)
└── vendor/                      (plugin-update-checker)
```
