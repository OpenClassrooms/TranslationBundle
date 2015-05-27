<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Services;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
interface FileSystem
{
    public function dump($filePath, $content);

    /**
     * @return string
     */
    public function getContent($filePath);

    /**
     * @return array
     */
    public function getContentToArray($filePath);
}
