<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Tests\Services;

use OpenClassrooms\Bundle\TranslationBundle\Services\FileSystem;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class FileSystemStub implements FileSystem
{

    /**
     * @var array
     */
    public static $files;

    function __construct()
    {
        self::$files = array();
    }

    public function dump($filePath, $content)
    {
        if (is_array($content)) {
            $content = implode("\n", explode("\n", implode("", $content)));
        }

        self::$files[$filePath] = $content;
    }

    /**
     * @return string
     */
    public function getContent($filePath)
    {
        if (!isset(self::$files[$filePath])) {
            self::$files[$filePath] = file_get_contents($filePath);
        }

        return self::$files[$filePath];
    }

    /**
     * @return array
     */
    public function getContentToArray($filePath)
    {
        if (!isset(self::$files[$filePath])) {
            self::$files[$filePath] = file_get_contents($filePath);
        }

        $file = explode("\n", self::$files[$filePath]);

        $newFile = '';
        foreach ($file as $line) {
            $newFile[] = $line . "\n";
        }

        return $newFile;
    }
}
