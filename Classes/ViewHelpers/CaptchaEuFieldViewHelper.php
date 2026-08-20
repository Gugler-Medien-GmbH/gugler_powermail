<?php

declare(strict_types=1);

namespace Gugler\GuglerPowermail\ViewHelpers;

use Gugler\GuglerPowermail\Captcha\CaptchaEuService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Renders a captcha.eu challenge for ANY form in ANY extension - not powermail
 * specific. Place it anywhere inside the <form> you want protected. Handles
 * both "invisible" mode (silently intercepts the enclosing form) and "widget"
 * mode (visible "I am human" widget), and loads the captcha.eu SDK itself, so
 * no TypoScript wiring is needed in the consuming extension.
 *
 * Usage: <gp:captchaEuField />
 *
 * On submit, verify the solution server-side with
 * CaptchaEuService::verify($request->getParsedBody()[CaptchaEuService::SOLUTION_FIELD_NAME] ?? '').
 * Renders nothing (and loads no script) if captcha.eu isn't configured yet
 * (Extension Configuration > gugler_powermail > publickey/restkey).
 *
 * Invisible mode does NOT use captcha.eu's own KROT.interceptForm() auto-submit
 * listener - that listener unconditionally preventDefault()s every submit and,
 * once solved, force-submits via the raw HTMLFormElement.prototype.submit(),
 * which never re-fires the "submit" event. That silently bypasses ANY other
 * validator on the form (powermail's own JS validation, Parsley, plain HTML5
 * required attributes, ...) - an invalid submission gets waved through as soon
 * as the captcha solves. Instead, Resources/Public/JavaScript/CaptchaEuInvisible.js
 * listens for "submit" on document (bubble phase) and only intervenes if the
 * event reached it with defaultPrevented still false, i.e. every validator
 * that already ran on/under the form let it through. See that file for details.
 */
class CaptchaEuFieldViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function render(): string
    {
        $service = GeneralUtility::makeInstance(CaptchaEuService::class);
        if (!$service->isConfigured()) {
            return '';
        }

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addJsFooterFile('https://www.captcha.eu/sdk.js');

        $publicKey = htmlspecialchars($service->getPublicKey(), ENT_QUOTES);

        if ($service->getMode() === 'widget') {
            // Widget mode never touches the submit flow: the visible widget
            // fills the target field itself once the user completes it, then
            // the form's normal submit button/validation takes over unchanged
            // - no JS conflict with other validators here. The SDK's
            // WidgetV2.autoInit() otherwise creates its OWN hidden field named
            // "captcha_at_hidden_field" (not matching SOLUTION_FIELD_NAME) -
            // data-field-selector points it at our field instead.
            $theme = htmlspecialchars($service->getTheme(), ENT_QUOTES);
            $fieldId = 'captcha-eu-solution-' . substr(md5(uniqid('', true)), 0, 8);
            return '<div class="cpt_widget" data-key="' . $publicKey . '" data-theme="' . $theme . '" data-field-selector="#' . $fieldId . '"></div>'
                . '<input type="hidden" id="' . $fieldId . '" class="captcha_at_hidden_field" name="' . CaptchaEuService::SOLUTION_FIELD_NAME . '" value="" />';
        }

        // Invisible mode: our own JS (loaded, not inlined, so it works under a
        // strict CSP) does the interception - see the class docblock above.
        $pageRenderer->addJsFooterFile('EXT:gugler_powermail/Resources/Public/JavaScript/CaptchaEuInvisible.js');

        return '<input type="hidden" class="captcha_at_hidden_field" data-public-key="' . $publicKey . '" name="' . CaptchaEuService::SOLUTION_FIELD_NAME . '" value="" />';
    }
}
