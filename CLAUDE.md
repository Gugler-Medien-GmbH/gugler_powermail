# gugler_powermail

Accessibility-Erweiterung (A11y) für die Drittanbieter-Formular-Extension `in2code/powermail` — ersetzt powermail nicht, sondern ergänzt es um zwei Features. Kein eigenständiges Formular-System.

Extension-Key/Composer: `gugler/gugler-powermail` · PSR-4: `Gugler\GuglerPowermail\` → `Classes/`

## Funktionen

### a11y-autocomplete
Fügt powermail-Formularfeldern ein HTML-`autocomplete`-Attribut hinzu, damit Browser Formulare korrekt vorausfüllen können.

- **Extbase-Model-Override** (`Configuration/Extbase/Persistence/Classes.php` + `Configuration/Services.yaml`): mappt `In2code\Powermail\Domain\Model\Field` auf lokale Subklasse `Gugler\GuglerPowermail\Domain\Model\Field` (`Classes/Domain/Model/Field.php`, gleiche Tabelle `tx_powermail_domain_model_field`), zusätzliche Property/Getter/Setter `autocomplete`
- **TCA-Override** (`Configuration/TCA/Overrides/tx_powermail_domain_model_field.php`): neue Spalte `autocomplete` (varchar(30), via `ext_tables.sql`) als `selectSingle`-Feld mit großer Liste an HTML-`autocomplete`-Werten (name, given-name, email, tel, address-line1, bday, organization, url, …, deutsch gelabelt), neuer Backend-Tab "Barrierefreiheit" nach `own_marker_select`
- **Fluid-Partial-Override** (`Resources/Private/Partials/Form/Field/Input.html`): überschreibt powermails Standard-Text-Input-Partial, gibt das `autocomplete`-Attribut über `additionalAttributes` aus

### a11y-captcha
Ersetzt powermails Standard-Captcha (Bild/Mathe-Captcha) durch ein lokalisiertes, barrierefreies Rechen-Captcha.

- **ViewHelper** `Classes/ViewHelpers/GuglerCaptchaViewHelper.php` (Tag `<g:GuglerCaptcha>`): generiert eine zufällige Einstellige-Zahl-`+`/`x`-Aufgabe, Zahlen ausgeschrieben über `locallang.xlf`/`de.locallang.xlf` (z.B. "Fünf plus Drei ="), erwartetes Ergebnis wird über `SessionUtility::setCaptchaSession()` in der Session gespeichert
- **Fluid-Partial-Override** (`Resources/Private/Partials/Form/Field/Captcha.html`): rendert `<g:GuglerCaptcha>` statt powermails Standard-Captcha
- **Sprachdateien**: `Resources/Private/Language/de.locallang_db.xlf` / `locallang_db.xlf` (Zahlen 1–9, "plus"/"mal"); Stub-Übersetzungen für `cs`, `hu`, `sk` vorhanden, aber nicht befüllt

## Einbindung

Partial-Root-Path-Override in `Configuration/TypoScript/setup.typoscript`:
```
plugin.tx_powermail {
  view {
    partialRootPaths {
      10 = EXT:gugler_powermail/Resources/Private/Partials/
    }
  }
}
```
Statisches TypoScript-Template **"gugler* powermail a11y improvements"** muss im Projekt manuell eingebunden werden (kein `ext_localconf.php`, keine automatische Einbindung).

## Abhängigkeiten

Aktuell (`main`, 4.x-Linie, `ext_emconf.php` Version 4.0.0):
- `typo3/cms-core`: `^14.3`
- `in2code/powermail`: `^14.0` (Early Access — powermail hat zum Zeitpunkt dieses Updates noch keinen öffentlichen TYPO3-14-Release, siehe in2code-de/powermail#1336)

TYPO3 14 / Fluid 5 verlangt explizite Return-Types in ViewHelpern (`initializeArguments(): void`, `render(): <Type>`) — in `GuglerCaptchaViewHelper` bereits umgesetzt. Die überschriebenen Fluid-Partials (`Input.html`, `Captcha.html`) und die TCA-`showitem`-str_replace-Hacks in `tx_powermail_domain_model_field.php` sind 1:1 aus einer älteren powermail-Version kopiert/gepatcht — bei Powermail-14-Vendor-Update gegen die dortigen Original-Partials/TCA gegenprüfen, da hier nichts automatisiert validiert wurde (kein lokaler Vendor-Zugriff beim Update auf 4.0.0).

## Versionshistorie

Mehrere parallele Branches/Major-Linien für unterschiedliche TYPO3-Versionen — beim Anpassen von Dependencies immer prüfen, welche Linie betroffen ist:

| Linie | Tags | typo3/cms-core | in2code/powermail |
|---|---|---|---|
| 1.x (Branch `TYPO3-11`) | 1.0.0 – 1.0.2 | `^11.5 \|\| ^12.4` | nur ext_emconf `"*"`, kein Composer-Requirement |
| 2.x | 2.0.0 – 2.0.2 | `^12.4` | `^12.4` (Composer-Requirement) |
| 3.x (Branch `TYPO3-13`) | 3.0.0 – 3.2.1 | `^12.4 \|\| ^13.4` | `^12.4 \|\| ^13.0 \|\| dev-master` |
| 4.x (`main`) | ab 4.0.0 | `^14.3` | `^14.0` (Early Access) |

Hinweis: 2.x wird als Backport-Zweig parallel zu 3.x gepflegt (2.0.2 chronologisch nach den 3.x-Tags released). Der TYPO3-12/13-kompatible Stand (3.x) ist als Branch `TYPO3-13` eingefroren.

## Verwandte Extensions

Teil der `gugler/*`-Extension-Familie in `TYPO3Extensions/`. Übersicht siehe Obsidian-Hub-Note `TYPO3Extensions/TYPO3Extensions.md` (Skill `typo3-init-extensions`).
