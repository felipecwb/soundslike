<?php

namespace Felipecwb\SoundsLike;

use JsonSerializable;

class Match implements JsonSerializable
{
    /**
     * @var string
     */
    private $phrase;
    /**
     * @var string
     */
    private $against;
    /**
     * @var float
     */
    private $similarity;

    /**
     * @param string $phare
     * @param string $against
     * @param float $similarity
     */
    public function __construct($phrase, $against, $similarity)
    {
        $this->phrase = $phrase;
        $this->against = $against;
        $this->similarity = $similarity;
    }

    /**
     * @return string
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @return string
     */
    public function getAgainst()
    {
        return $this->against;
    }

    /**
     * @return float
     */
    public function getSimilarity()
    {
        return $this->similarity;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->phrase;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'phrase'     => $this->getPhrase(),
            'against'    => $this->getAgainst(),
            'similarity' => $this->getSimilarity()
        ];
    }
}
