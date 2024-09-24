<?php

namespace Gugler\GuglerPowermail\ViewHelpers;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Utility\MathematicUtility;
use In2code\Powermail\Utility\SessionUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Class CaptchaViewHelper
 */
class GuglerCaptchaViewHelper extends AbstractTagBasedViewHelper {

    private $operators = array(
      '+' => "plus",
      'x' => "mal"
    );

    private $numbers = array(
      "1" => "Eins",
      "2" => "Zwei",
      "3" => "Drei",
      "4" => "Vier",
      "5" => "Fünf",
      "6" => "Sechs",
      "7" => "Sieben",
      "8" => "Acht",
      "9" => "Neun",
    );

    public function initializeArguments()
    {
        $this->registerArgument('field', Field::class, 'powermail field', true);
        parent::initializeArguments();
    }

    public function render()
    {
        $field = $this->arguments['field'];

        $operator = array_keys($this->operators)[mt_rand(0, count($this->operators) - 1)];
        $number1 = mt_rand(1, count($this->numbers));
        $number2 = mt_rand(1, count($this->numbers));
        $result = MathematicUtility::mathematicOperation($number1, $number2, $operator);
        SessionUtility::setCaptchaSession((string)$result, $field->getUid());

        return $this->numbers[$number1] . " " . $this->operators[$operator] . " " . $this->numbers[$number2] . " = ";
    }

}
