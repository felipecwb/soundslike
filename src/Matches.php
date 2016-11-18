<?php

namespace Felipecwb\SoundsLike;

use Iterator;
use JsonSerializable;

class Matches implements Iterator, JsonSerializable
{
    /**
     * @var array
     */
    private $matches = [];

    /**
     * @param array $matches
     */
    public function __construct(array $matches = array())
    {
        foreach ($matches as $match) {
            $this->add($match['phrase'], $match['against'], $match['similarity']);
        }
    }

    /**
     * @param string $phare
     * @param string $against
     * @param float $similarity
     */
    public function add($phrase, $against, $similarity)
    {
        $this->matches[] = new Match($phrase, $against, $similarity);
        return $this;
    }

    /**
     * reverse order
     * @return Matches
     */
    public function reverse()
    {
        $this->matches = array_reverse($this->matches);
        return $this;
    }

    /**
     * sort by closest string
     * @return Matches
     */
    public function sort()
    {
        usort($this->matches, function ($a, $b) {
            return ($a->getSimilarity() > $b->getSimilarity())
                 - ($a->getSimilarity() < $b->getSimilarity());
        });

        return $this;
    }

    /**
     * @return array
     */
    public function current()
    {
        return current($this->matches);
    }

    /**
     * @return int
     */
    public function key()
    {
        return key($this->matches);
    }

    public function next()
    {
        return next($this->matches);
    }

    public function rewind()
    {
        return reset($this->matches);
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return (bool) $this->current();
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->matches;
    }
}
