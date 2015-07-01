<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Services\Impl;

use OpenClassrooms\Bundle\TranslationBundle\Services\CatalogueService;
use Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class CatalogueServiceImpl implements CatalogueService
{

    /**
     * @var TranslationLoader
     */
    private $translationLoader;

    /**
     * @return array
     */
    public function findMissingKeys($reference, array $locales = array(), array $paths = array())
    {
        $missingKeys = array();
        foreach ($paths as $path) {

            foreach ($locales as $locale) {

                $currentCatalogue = new MessageCatalogue($locale);
                $this->translationLoader->loadMessages($path, $currentCatalogue);

                $fallbackCatalogue = new MessageCatalogue($reference);
                $this->translationLoader->loadMessages($path, $fallbackCatalogue);

                foreach ($fallbackCatalogue->all() as $domain) {

                    foreach ($domain as $key => $value) {

                        if (!$currentCatalogue->defines($key)) {
                            $missingKeys[$locale][] = $key;
                        }
                    }
                }
            }
        }

        return $missingKeys;
    }

    public function setTranslationLoader(TranslationLoader $loader)
    {
        $this->translationLoader = $loader;
    }
}
