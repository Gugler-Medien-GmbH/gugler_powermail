<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tempColumns = [
    'autocomplete' => [
        'exclude' => 0,
        'label'   => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete',
        'config'  => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.none',
                    'value' => 'none'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.on',
                    'value' => 'on'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.off',
                    'value' => 'off'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.name',
                    'value' => 'name'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.given-name',
                    'value' => 'given-name'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.family-name',
                    'value' => 'family-name'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.email',
                    'value' => 'email'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.tel',
                    'value' => 'tel'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.address-line1',
                    'value' => 'address-line1'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.country-name',
                    'value' => 'country-name'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.postal-code',
                    'value' => 'postal-code'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.sex',
                    'value' => 'sex'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.additional-name',
                    'value' => 'additional-name'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.honorific-prefix',
                    'value' => 'honorific-prefix'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.honorific-suffix',
                    'value' => 'honorific-suffix'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.language',
                    'value' => 'language'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.bday',
                    'value' => 'bday'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.bday-day',
                    'value' => 'bday-day'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.bday-month',
                    'value' => 'bday-month'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.bday-year',
                    'value' => 'bday-year'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.organization',
                    'value' => 'organization'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.organization-title',
                    'value' => 'organization-title'
                ],
                [
                    'label' => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete.url',
                    'value' => 'url'
                ]
            ]
        ]
    ]
];

ExtensionManagementUtility::addTCAcolumns('tx_powermail_domain_model_field', $tempColumns);
ExtensionManagementUtility::addToAllTCAtypes(
    'tx_powermail_domain_model_field',
    '--div--;LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:powermail.tab.accessibility, autocomplete',
    '',
    'after:own_marker_select'
);
