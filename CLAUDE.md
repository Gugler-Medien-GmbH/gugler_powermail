# gugler_powermail

Accessibility-Erweiterung (A11y) für die Drittanbieter-Formular-Extension `in2code/powermail` — ersetzt powermail nicht, sondern ergänzt es um mehrere Features. Kein eigenständiges Formular-System. Enthält seit 4.1.0 zusätzlich eine eigenständig nutzbare captcha.eu-Integration (siehe unten), unabhängig von powermail einsetzbar.

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

### captcha.eu (eigenständig nutzbar, nicht auf powermail beschränkt)
Ersetzt die extern gepflegte, nicht TYPO3-14-fähige `captcha-eu/typo3-powermail`-Extension durch eine hauseigene, entkoppelte Integration (der Third-Party-Maintainer pflegt die Extension nicht aktiv genug für zeitnahe Major-Upgrades).

- **Service** `Classes/Captcha/CaptchaEuService.php`: reine PHP-Klasse, kein powermail-Bezug — `verify(string $solution): bool` prüft über die captcha.eu-REST-API (`https://www.captcha.eu/validate`, `RequestFactory`). Von jeder Extension per `GeneralUtility::makeInstance(CaptchaEuService::class)` nutzbar, sofern `gugler/gugler-powermail` als Composer-Dependency vorhanden ist.
- **ViewHelper** `Classes/ViewHelpers/CaptchaEuFieldViewHelper.php` (Tag `<gp:captchaEuField/>`): rendert die Challenge (invisible oder widget mode) in ein beliebiges `<form>` einer beliebigen Extension, lädt das captcha.eu-SDK selbst nach (kein TypoScript nötig). Solution kommt als POST-Feld `CaptchaEuService::SOLUTION_FIELD_NAME` ("captcha_at_solution") zurück, serverseitig mit `CaptchaEuService::verify()` prüfen.
  - **Wichtig — Frontend-Validierung bleibt intakt:** die eigentliche captcha.eu-SDK-Funktion `KROT.interceptForm(form)` (invisible mode) hängt einen eigenen `submit`-Listener an den Form, der IMMER `preventDefault()` aufruft und nach dem Lösen per `HTMLFormElement.prototype.submit.call(form)` (rohes DOM-`submit()`, feuert kein `submit`-Event) fertig-submitted — komplett unabhängig davon, ob ein anderer Validator (powermail, Parsley, `required`-Attribute) die Eingabe vorher als ungültig zurückgewiesen hat. **Diese SDK-Funktion wird hier bewusst NICHT verwendet.** Stattdessen: `Resources/Public/JavaScript/CaptchaEuInvisible.js`, ein einziger `document`-weiter (nicht Form-weiter!) `submit`-Listener in der Bubble-Phase. Da Bubble-Listener auf dem Zielelement (dem Form) IMMER vor Bubble-Listenern auf `document` laufen — unabhängig davon wer zuerst registriert wurde — sieht dieser Listener zuverlässig, ob `event.defaultPrevented` bereits `true` ist, und greift nur bei tatsächlich validen Submits ein. Funktioniert dadurch generisch mit powermails eigener JS-Validierung, Parsley oder gar keinem Validator — solange dieser sauber nur `preventDefault()` statt `stopPropagation()` nutzt (Standardverhalten).
  - Widget mode fasst den Submit-Flow gar nicht an (der sichtbare Widget füllt sein Zielfeld selbst via `CPT_OK`/`CPT_FAILED`/`CPT_EXPIRED`-Events) — daher unkritisch. Wichtig ist nur `data-field-selector` auf dem `.cpt_widget`-Div zu setzen (via ViewHelper automatisch, eindeutige `id` pro Rendering), sonst legt `WidgetV2.autoInit()` selbst ein Hidden-Field namens `captcha_at_hidden_field` an, das NICHT dem serverseitig erwarteten `captcha_at_solution` entspricht.
- **powermail-Anbindung** `Classes/Domain/Validator/SpamShield/CaptchaEuMethod.php`: dünner Adapter, registriert als `spamshield.methods` — delegiert komplett an `CaptchaEuService`.
- **Formularfeld-Typ** "Captcha.eu" im powermail-Formbuilder: `Configuration/CaptchaEu.tsconfig` (`tx_powermail.flexForm.type.addFieldOptions.captchaeu`), automatisch geladen über `Configuration/page.tsconfig`. Rendering-Partial `Resources/Private/Partials/Form/Field/Captchaeu.html` (nutzt intern den ViewHelper).
- **`partialRootPaths`/SpamShield-Registrierung liegen doppelt vor:** `Configuration/TypoScript/setup.typoscript` (klassisches statisches Template, für TYPO3-11/12/13-Branches ohne Site-Sets — **muss im Projekt manuell eingebunden werden**, siehe unten) UND `Configuration/Sets/CaptchaEu/setup.typoscript` (Site Set, für TYPO3-14-Projekte die die Set-Dependency deklarieren — keine manuelle Einbindung nötig). Beide Dateien synchron halten.
- **Zwei echte Stolpersteine beim Erstaufsetzen in `raumordnung-noe-typo3` gefunden:**
  1. **Shadowing durch ein anderes `Captchaeu.html`-Partial im Projekt.** Powermail durchsucht ALLE registrierten `partialRootPaths` (aus mehreren Extensions gleichzeitig, nach Prioritätsnummer sortiert) und nimmt das erste Match. Ein altes, verwaistes `Captchaeu.html` im Projekt selbst (`theme_gugler_bootstrap`, Priorität 12 vs. unsere 10 — höhere Nummer gewinnt) hat unser Feld komplett überschattet, ohne Fehler — es hat einfach lautlos die falsche (kaputte, weil auf inzwischen entferntes TypoScript verweisende) Version gerendert. **Bei "Feld rendert nicht / falscher Inhalt"-Problemen zuerst `grep -rn "Captchaeu.html"` über das GANZE Projekt laufen lassen, nicht nur in `gugler_powermail` suchen.**
  2. **`CaptchaEuService` muss `public: true` in `Services.yaml` sein.** Da der Service nur über `GeneralUtility::makeInstance()` geholt wird (aus einem Fluid-ViewHelper und einer powermail-SpamShield-Methode — beide nicht Constructor-Injection-fähig) und NIRGENDS per Constructor-Injection referenziert wird, entfernt der Symfony-Container-Compiler ihn sonst als "unused" — `makeInstance()` fällt dann lautlos auf ein rohes `new CaptchaEuService()` ohne Autowiring zurück → `Too few arguments to function ...::__construct(), 0 passed ... exactly 1 expected`. Jede Klasse, die nur via `makeInstance()` (nicht Constructor-Injection) geholt wird, braucht explizites `public: true`.
- **Konfiguration: Site Set, nicht Extension Configuration** — `Configuration/Sets/CaptchaEu/` (`config.yaml` + `settings.definitions.yaml`) definiert `captchaeu.publicKey`, `captchaeu.restKey`, `captchaeu.mode`, `captchaeu.theme`. Kein `plugin.tx_*`-Präfix nötig (das ist nur für Extbase-Plugin-`$this->settings`-Injection relevant, hier wird direkt per PHP gelesen). Kein `ext_conf_template.txt` mehr (Extension Configuration ist global-instanzweit — für ein Multi-Site-Projekt mit potenziell unterschiedlichen captcha.eu-Accounts pro Domain die falsche Ebene).
  - **Aktivierung pro Site:** `dependencies: [gugler/gugler-powermail]` in `config/sites/<site>/config.yaml`.
  - **Werte eintragen:** TYPO3-Backend → Site Management → Sites → `<Site>` → Tab "Settings" (bzw. "Bearbeiten" → Einstellungen) — TYPO3 generiert das Formular automatisch aus `settings.definitions.yaml`. Landet in `config/sites/<site>/settings.yaml` (Datei, nicht DB-Tabelle — bei Secrets-Deployment per Skript/CI entsprechend beachten).
  - `CaptchaEuService` liest über `$GLOBALS['TYPO3_REQUEST']->getAttribute('site')->getSettings()->get('captchaeu.publicKey', '')` — funktioniert bereits ab der Site-Auflösung (sehr früh in der Middleware-Kette), robuster als ein Zugriff auf das volle geparste TypoScript-Setup.
  - **Reverse-Engineering-Hinweis:** captcha.eu liefert keine öffentliche API-Doku für `sdk.js` — das SDK-Verhalten oben wurde aus dem minifizierten Skript (`https://www.captcha.eu/sdk.js`) extrahiert. Bei SDK-Updates gegenprüfen, ob sich `interceptForm`/`getSolution`/`WidgetV2.autoInit` geändert haben.

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
