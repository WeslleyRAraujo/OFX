<?php

namespace WeslleyRAraujo\OFX\Core;

class OFXStream
{
    /**
     * Set the OFX file path
     *
     * @param string $filePath OFX file path
     * @return OFXStream
     */
    public function __construct(private string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new OFXException("File {$filePath} not found.");
        }
        return $this;
    }

    /**
     * Read the file stream and returns the content
     *
     * @return string File content string
     */
    public function read() :string
    {
        $stream = fopen($this->filePath, 'rb');
        $content = '';

        while (!feof($stream)) {
            $content .= fgets($stream);
        }

        return $content;
    }
}