# gugler_powermail
A11y improvements for powermail extension

## captcha.eu

Spam check for powermail forms via [captcha.eu](https://www.captcha.eu) (invisible or widget mode).

1. Include the static template "gugler* powermail a11y improvements" (or, without a `sys_template` that clears setup, add `gugler/gugler-powermail` to the site's `dependencies`).
2. Add the site set `gugler/gugler-powermail` to the site and fill in the keys per site, e.g. in `config/sites/<site>/settings.yaml`:
   ```yaml
   captchaeu:
     publicKey: '...'
     restKey: '...'
   ```
3. Add a field of type "Captcha.eu" to the powermail form.

captcha.eu keys are bound to a domain. For a local/dev domain, override them for all sites, e.g. in `config/system/additional.php` (DDEV only):

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['gugler_powermail']['captchaeu'] = [
    'publicKey' => '...',
    'restKey' => '...',
];
```
