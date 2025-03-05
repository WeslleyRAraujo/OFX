<?php

namespace WeslleyRAraujo\OFX\Core;

class OFXContentFormatter
{
    /**
     * Header infos of OFX
     *
     * @var array
     */
    private $headers = [];

    /**
     * SimpleXMLElement object
     *
     * @var \SimpleXMLElement
     */
    private $SimpleXMLElement;

    /**
     * Set the OFX file content
     *
     * @param string $content OFX file content
     * @return void
     */
    public function __construct(private string $content)
    {
        $this->content = trim($this->content);
        if (empty($this->content)) {
            throw new OFXException('OFX content is empty.');
        }
        $this->readHeader();
    }

    /**
     * Format the file into a readable XML
     *
     * @return OfxContentFormatter
     */
    public function format() :self
    {
        $XMLContentFormatted = $this->formatter();
        $this->SimpleXMLElement = new \SimpleXMLElement($XMLContentFormatted);
        return $this;
    }

    /**
     * Returns a SimpleXMLElement representation of OFX body
     *
     * @return \SimpleXMLElement
     */
    public function getBody() :\SimpleXMLElement
    {
        return $this->SimpleXMLElement;
    }

    /**
     * Returns a array of OFX headers
     *
     * @return array
     */
    public function getHeader() :array
    {
        return $this->headers;
    }

    /**
     * Returns the formatted content
     *
     * @return string
     */
    private function formatter() :string
    {
        return $this->closeTags();
    }

    /**
     * Close all unfinished tags
     *
     * @return string
     */
    private function closeTags() :string
    {
        $OFXBody = substr($this->content, strpos($this->content, '<OFX>'));
        $OFXBodyLines = explode("\n", $OFXBody);

        // Close all unfisinhed tags
        $OFXBodyLines = array_map( function($lineContent) {
            $lineContent = preg_replace("/(\r\n|\r|\n)/", '', $lineContent);
            $emptyLine = empty(trim($lineContent));
            if ($emptyLine) {
                return '';
            }

            // Some tags don't have closing tags
            // In this case we close the XML tag
            $tagNameParts = explode('>', $lineContent);
            $tagName = preg_replace("/[^A-Za-z0-9]/", '', reset($tagNameParts));
            $tagClosed = "</{$tagName}>";
            $hasClosedTag = strpos($this->content, $tagClosed) !== false;
            if (!$hasClosedTag) {
               return "{$lineContent}{$tagClosed}";
            }
            return $lineContent;
        }, $OFXBodyLines);

        return implode('', $OFXBodyLines);
    }

    /**
     * Read the file header and feed $this->headers
     *
     * @return void
     */
    private function readHeader() :void
    {
        $ofxHeadersRaw = substr($this->content, 0, strpos($this->content, '<OFX>'));
        $ofxHeaderLines = explode("\n", $ofxHeadersRaw);
        $ofxHeaderLines = array_filter($ofxHeaderLines, fn ($line) => !empty(trim($line)) );
        foreach ($ofxHeaderLines as $lineContent) {
            $lineContentSplit = explode(':', $lineContent);
            $headerName = reset($lineContentSplit);
            $headerValue = end($lineContentSplit);
            $this->headers[$headerName] = $headerValue;
        }
    }

}