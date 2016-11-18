# Sounds Like
PHP Sounds Like Text Search

`composer require "felipecwb/soundslike"`

It's looks like that:
```php

use Felipecwb\SoundsLike\TextSearch;

$search = new TextSearch([
    "São Paulo - SP",
    "Osasco - SP",
    "Curitiba - PR",
    "Curitibanos - SC",
    "Vitória - ES",
    "Rio de Janeiro - RJ",
    "Natal - RN"
]);

// find for the best matching text
$result = $search->findBestMatch('Sao paulo');

$result->getPhrase(); //São Paulo - SP
$result->getAgainst(); //Sao paulo
$result->getSimilarity(); //7
```
