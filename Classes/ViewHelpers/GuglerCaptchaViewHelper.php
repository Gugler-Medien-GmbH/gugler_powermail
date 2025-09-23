<?php

namespace Gugler\GuglerPowermail\ViewHelpers;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Utility\MathematicUtility;
use In2code\Powermail\Utility\SessionUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class CaptchaViewHelper
 */
class GuglerCaptchaViewHelper extends AbstractTagBasedViewHelper {

    private $operators = array(
      '+' => "plus",
      'x' => "mal"
    );

    public function initializeArguments()
    {
        $this->registerArgument('field', Field::class, 'powermail field', true);
        parent::initializeArguments();
    }

    public function render()
    {
        $field = $this->arguments["field"];

        $operator = array_keys($this->operators)[
            mt_rand(0, count($this->operators) - 1)
        ];
        $number1 = mt_rand(1, 9);
        $number2 = mt_rand(1, 9);
        $result = MathematicUtility::mathematicOperation(
            $number1,
            $number2,
            $operator
        );
        SessionUtility::setCaptchaSession((string) $result, $field->getUid());

        $number1Label = LocalizationUtility::translate(
            "captcha.number." . $number1,
            "gugler_powermail"
        );
        $number2Label = LocalizationUtility::translate(
            "captcha.number." . $number2,
            "gugler_powermail"
        );
        $operatorLabel = LocalizationUtility::translate(
            "captcha.operator." . $this->operators[$operator],
            "gugler_powermail"
        );

        return $number1Label .
            " " .
            $operatorLabel .
            " " .
            $number2Label .
            " = ";
    }

}
