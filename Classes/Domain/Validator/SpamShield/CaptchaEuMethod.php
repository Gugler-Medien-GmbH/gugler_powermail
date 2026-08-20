<?php

declare(strict_types=1);

namespace Gugler\GuglerPowermail\Domain\Validator\SpamShield;

use Gugler\GuglerPowermail\Captcha\CaptchaEuService;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * powermail SpamShield adapter for captcha.eu - the actual widget rendering
 * and REST verification live in CaptchaEuService/CaptchaEuFieldViewHelper so
 * other extensions can reuse them outside of powermail.
 *
 * Registered via Configuration/TypoScript/setup.typoscript
 * (plugin.tx_powermail.settings.setup.spamshield.methods).
 */
class CaptchaEuMethod extends AbstractMethod
{
    public function spamCheck(): bool
    {
        if (!$this->isFormWithCaptchaEuField() || $this->isCaptchaCheckToSkip()) {
            return false;
        }

        // captcha_at_solution is submitted as a top-level POST field (see
        // CaptchaEuFieldViewHelper), not namespaced under tx_powermail_pi1,
        // so it isn't part of $this->arguments.
        $solution = (string)($_POST[CaptchaEuService::SOLUTION_FIELD_NAME] ?? '');
        $service = GeneralUtility::makeInstance(CaptchaEuService::class);

        return !$service->verify($solution);
    }

    protected function isFormWithCaptchaEuField(): bool
    {
        foreach ($this->mail->getForm()->getPages() as $page) {
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'captchaeu') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Captcha check should be skipped on createAction if there was a
     * confirmationAction where the captcha was already checked before.
     */
    protected function isCaptchaCheckToSkip(): bool
    {
        $confirmationActive = ($this->flexForm['settings']['flexform']['main']['confirmation'] ?? '') === '1';
        return ($this->arguments['action'] ?? '') === 'create' && $confirmationActive;
    }
}
