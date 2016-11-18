<?php

namespace Felipecwb\SoundsLike;

class TextSearch
{
    /**
     * Options
     * @var int
     */
    const OPTION_METAPHONE     = 0b001;
    const OPTION_CONVERT_ASCII = 0b010;
    const OPTION_INSENSITIVE   = 0b100;

    /**
     * List of strings to match against the $this->input
     * @var array
     */
    private $searchAgainst = array();

    /**
     * @var integer
     */
    private $options;

    /**
     * @param array $searchAgainst An array of strings
     * @param int $options
     */
    public function __construct(array $searchAgainst = array(), $options = self::OPTION_METAPHONE)
    {
        $this->searchAgainst = $searchAgainst;
        $this->setOptions($options);
    }

    /**
    * @param string $phrase [description]
    */
    public function addSearch($phrase)
    {
        $this->searchAgainst = $phrase;
    }

    /**
     * @param int $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Reset the options
     */
    public function resetOptions()
    {
        $this->options = 0;
        return $this;
    }

    /**
     * Set Options to use metaphone of strings
     * @return TextSearch
     */
    public function useMetaphone()
    {
        $this->options |= self::OPTION_METAPHONE;
        return $this;
    }

    /**
     * Set Options to use metaphone of strings
     * @return TextSearch
     */
    public function useConvertToASCII()
    {
        $this->options |= self::OPTION_CONVERT_ASCII;
        return $this;
    }

    /**
     * Set Options to use metaphone of strings
     * @return TextSearch
     */
    public function useInsensitive()
    {
        $this->options |= self::OPTION_INSENSITIVE;
        return $this;
    }

    /**
     * find the closest matching string in $this->searchAgainst when compared to $input
     * @param string $input The input to compare
     * @return Match The closest matching string.
     */
    public function findBestMatch($input)
    {
        $closest = null;
        $foundbestmatch = -1;
        //get the metaphone equivalent for the input
        $metaInput = $this->getMetaPhone($input);

        foreach ($this->searchAgainst as $phrase) {
            $similarity = $this->calculateSimilarity($input, $phrase, $metaInput);

            // we found an exact match
            if ($similarity == 0) {
                return new Match($phrase, $input, $similarity);
            }

            if ($similarity <= $foundbestmatch || $foundbestmatch < 0) {
                $closest = new Match($phrase, $input, $similarity);
                $foundbestmatch = $similarity;
            }
        }

        return $closest;
    }

    /**
     * find the closest matching string in $this->searchAgainst when compared to $this->input
     * @param string $input The input to compare
     * @param float $lessThan Brings the results less than this value
     * @return Matches
     */
    public function findMatches($input, $lessThan = null)
    {
        $matches = new Matches();

        //get the metaphone equivalent for the input phrase
        $metaInput = $this->getMetaPhone($input);

        foreach ($this->searchAgainst as $phrase) {
            $similarity = $this->calculateSimilarity($input, $phrase, $metaInput);

            if (null === $lessThan || $similarity <= $lessThan) {
                $matches->add($phrase, $input, $similarity);
            }
        }

        return $matches->sort();
    }

    /**
     * @param string $phrase
     * @return array Metaphones for each word in a string
     */
    private function getMetaPhone($phrase)
    {
        $metaphones = array();
        $words = str_word_count($phrase, 1);

        foreach ($words as $word) {
            $metaphones[] = metaphone($word);
        }

        return implode(' ', $metaphones);
    }

    /**
     * Claculate the similarity in the strings
     * @param  string $input     [description]
     * @param  string $against   [description]
     * @param  string $metaInput [description]
     * @return float            [description]
     */
    private function calculateSimilarity($input, $against, $metaInput = null)
    {
        $divisor = 1;
        $similarity = 0;

        if ($this->options & self::OPTION_METAPHONE) {
            $metaInput = $metaInput ?: $this->getMetaPhone($input);
            $metaAgainst = $this->getMetaPhone($against);

            $similarity += levenshtein($metaInput, $metaAgainst);
            $divisor++;
        }

        if ($this->options & self::OPTION_INSENSITIVE) {
            $input = mb_strtolower($input);
            $against = mb_strtolower($against);
        }

        if ($this->options & self::OPTION_CONVERT_ASCII) {
            $input = mb_convert_encoding($input, 'ASCII');
            $against = mb_convert_encoding($against, 'ASCII');
        }

        $similarity += levenshtein($input, $against);

        return $similarity / $divisor;
    }
}
