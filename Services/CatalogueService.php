<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Services;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */

interface CatalogueService
{
    /**
     * @return array
     */
    public function findMissingKeys($reference, array $locales = array(), array $paths = array());
}
