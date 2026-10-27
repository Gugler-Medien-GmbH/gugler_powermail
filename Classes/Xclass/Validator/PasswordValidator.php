<?php

declare(strict_types=1);

namespace Gugler\GuglerPowermail\Xclass\Validator;

use In2code\Powermail\Domain\Model\Mail;
use TYPO3\CMS\Extbase\Error\Result;

/**
 * powermail 14 runs its validators before the action's own isMailParamEmpty() forward, and this one
 * dereferences $mail unguarded: a request without mail params (GET on create/confirmation, POST body
 * dropped by post_max_size) ends in a fatal "Call to a member function ... on null".
 */
class PasswordValidator extends \In2code\Powermail\Domain\Validator\PasswordValidator
{
    public function validate($mail): Result
    {
        return $mail instanceof Mail ? parent::validate($mail) : new Result();
    }
}
