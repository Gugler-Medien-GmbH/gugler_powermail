<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tempColumns = [
    'autocomplete'    => [
        'exclude' => 0,
        'label'   => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:autocomplete',
        'config'  => [
            'type'       => 'select',
            'renderType' => 'selectSingle',
            'items'      => [
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
    ],
    "items_per_row"   => [
        "exclude"     => 0,
        "label"       => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:itemsPerRow',
        "description" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:itemsPerRow.description',
        "config"      => [
            "type"       => 'select',
            "renderType" => 'selectSingle',
            "items"      => [
                [
                    "label" => "LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:select.empty",
                    "value" => ""
                ],
                [
                    "label" => '2 xl',
                    "value" => "col-12 col-sm-6"
                ],
                [
                    "label" => '2 sm',
                    "value" => "col-12 col-sm-5"
                ],
                [
                    "label" => '3',
                    "value" => "col-12 col-sm-4"
                ],
                [
                    "label" => '4',
                    "value" => "col-12 col-sm-3"
                ],
                [
                    "label" => '6',
                    "value" => "col-12 col-sm-2"
                ],
            ],
        ]
    ],
    "justify_content" => [
        "exclude" => 0,
        "label"   => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content',
        "config"  => [
            "type"       => 'select',
            "renderType" => 'selectSingle',
            "items"      => [
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:select.empty',
                    "value" => ""
                ],
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content.left',
                    "value" => "justify-content-start"
                ],
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content.center',
                    "value" => "justify-content-center"
                ],
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content.right',
                    "value" => "justify-content-end"
                ],
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content.space-between',
                    "value" => "justify-content-between"
                ],
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content.space-around',
                    "value" => "justify-content-around"
                ],
                [
                    "label" => 'LLL:EXT:gugler_powermail/Resources/Private/Language/locallang_db.xlf:justify_content.space-evenly',
                    "value" => "justify-content-evenly"
                ],
            ],
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

$GLOBALS['TCA']['tx_powermail_domain_model_field']['palettes']["inlineOptions"] = [
    'showitem' => 'items_per_row,justify_content'
];
$showItemCheck = $GLOBALS['TCA']['tx_powermail_domain_model_field']['types']['check']['showitem'];
$showItemRadio = $GLOBALS['TCA']['tx_powermail_domain_model_field']['types']['radio']['showitem'];
$GLOBALS['TCA']['tx_powermail_domain_model_field']['types']['check']['showitem'] = str_replace("--palette--;Layout;43", "--palette--;Layout;43, --palette--;Inline;inlineOptions", $showItemCheck);
$GLOBALS['TCA']['tx_powermail_domain_model_field']['types']['radio']['showitem'] = str_replace("--palette--;Layout;43", "--palette--;Layout;43, --palette--;Inline;inlineOptions", $showItemRadio);

