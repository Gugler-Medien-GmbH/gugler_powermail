<?php

//xclass for Field
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Domain\Model\Field::class] = [
    'className' => \Gugler\GuglerPowermail\Domain\Model\Field::class,
];

// xclasses for validators that crash on a missing mail argument
foreach (['InputValidator', 'PasswordValidator', 'SpamShieldValidator'] as $validator) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['In2code\\Powermail\\Domain\\Validator\\' . $validator] = [
        'className' => 'Gugler\\GuglerPowermail\\Xclass\\Validator\\' . $validator,
    ];
}
