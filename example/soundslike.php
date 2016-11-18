<?php

require __DIR__ . '/../vendor/autoload.php';

use Felipecwb\SoundsLike\TextSearch;

$search = new TextSearch([
    "The quick brown cat jumped over the lazy dog",
    "Thors hammer jumped over the lazy dog",
    "The quick brown fax jumped over the lazy dog"
]);

$input = 'The quick brown fox jumped over the lazy dog';

$result = $search->findBestMatch($input);
var_dump($results); // Best Match with similarity result

$results = $search->findMatches($input);
var_dump($results); // Matches with similarity results


$search = new TextSearch([
    "São Paulo - SP",
    "Osasco - SP",
    "Curitiba - PR",
    "Curitibanos - SC",
    "Vitória - ES",
    "Rio de Janeiro - RJ",
    "Natal - RN"
]);

$result = $search->findBestMatch('Sao paulo');
var_dump($result);
