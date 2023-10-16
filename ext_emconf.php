<?php
$EM_CONF[$_EXTKEY] = [
  "title" => "gugler* powermail",
  "description" => "A11y improvements for powermail extension",
  "category" => "plugin",
  "author" => "Ing. Markus Ketterer",
  "author_email" => "webdeveloper@gugler.at",
  "state" => "alpha",
  "version" => "1.0.0",
  "constraints" => [
    "depends" => [
      "typo3" => "11.5.0-12.4.99",
      "powermail" => "*",
    ],
    "conflicts" => [],
    "suggests" => [],
  ],
];
