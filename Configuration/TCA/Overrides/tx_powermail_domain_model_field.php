<?php
defined('TYPO3') or die();



$tempColumns = [
    'autocomplete' => [
        'exclude' => 0,
        'label' => 'Autocomplete Wert',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Kein autocomplete Attribut', 'value' => 'none'],
                ['label' => 'On', 'value' => 'on'],
                ['label' => 'Off', 'value' => 'off'],
                ['label' => 'Ganzer Name', 'value' => 'name'],
                ['label' => 'Vorname', 'value' => 'given-name'],
                ['label' => 'Nachname', 'value' => 'family-name'],
                ['label' => 'E-Mail', 'value' => 'email'],
                ['label' => 'Telefonnummer', 'value' => 'tel'],
                ['label' => 'Straße', 'value' => 'address-line1'],
                ['label' => 'Land', 'value' => 'country-name'],
                ['label' => 'PLZ (nur möglich, wenn keine Validierung nach Nummern)', 'value' => 'postal-code'],
                ['label' => 'Geschlecht', 'value' => 'sex'],
                ['label' => 'Zweiter Name', 'value' => 'additional-name'],
                ['label' => 'Titel vorangestellt', 'value' => 'honorific-prefix'],
                ['label' => 'Titel nachgestellt', 'value' => 'honorific-suffix'],
                ['label' => 'Sprache', 'value' => 'language'],
                ['label' => 'Geburtstag', 'value' => 'bday'],
                ['label' => 'Tag des Geburtsdatum', 'value' => 'bday-day'],
                ['label' => 'Monat des Geburtsdatum', 'value' => 'bday-month'],
                ['label' => 'Jahr des Geburtsdatum', 'value' => 'bday-year'],
                ['label' => 'Unternehmen/Organisation', 'value' => 'organization'],
                ['label' => 'Position (Job Titel)', 'value' => 'organization-title'],
                ['label' => 'Website (URL)', 'value' => 'url']
            ]
        ]
    ]
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_powermail_domain_model_field', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_powermail_domain_model_field',
    '--div--;Barrierefreiheit, autocomplete',
    '',
    'after:own_marker_select'
);
