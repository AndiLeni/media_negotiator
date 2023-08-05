    public function getCacheFilename()
    {
        assert(null !== $this->cachePath);
        assert(null !== $this->type);

        $set_effects = $this->effectsFromType($this->getMediaType());
        $set_effects = array_column($set_effects, 'effect');

        if (!in_array('negotiator', $set_effects)) {
            // do not change cache path
            return $this->cachePath . $this->type . '/' . $this->originalFilename;
        } else {
            // change cache path for negotiator
            $possible_types = rex_server('HTTP_ACCEPT', 'string', '');
            $types = explode(',', $possible_types);
            $possibleFormat = media_negotiator\Helper::getOutputFormat($types);

            return $this->cachePath . $possibleFormat . "-" . $this->type . '/' . $this->originalFilename;
        }
    }