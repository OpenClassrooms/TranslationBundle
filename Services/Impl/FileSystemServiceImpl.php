<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Services\Impl;

use OpenClassrooms\Bundle\TranslationBundle\Services\FileSystemService;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class FileSystemServiceImpl implements FileSystemService
{
    public function dump($filePath, $content)
    {
        file_put_contents($filePath, $content);
    }

    /**
     * @return string
     */
    public function getContent($filePath)
    {
        return file_get_contents($filePath);
    }

    /**
     * @return array
     */
    public function getContentToArray($filePath)
    {
        return file($filePath);
    }

}
