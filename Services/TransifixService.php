<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Services;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
interface TransifixService
{
    public function fixYamlFile($filePath);

    /**
     * @return array
     */
    public function loadYaml($filePath);

    /**
     * @return array
     */
    public function fixXss(array $translations);
}
