# TranslationBundle
[![Build Status](https://travis-ci.org/OpenClassrooms/TranslationBundle.svg)](https://travis-ci.org/OpenClassrooms/TranslationBundle)
[![Coverage Status](https://coveralls.io/repos/OpenClassrooms/TranslationBundle/badge.svg)](https://coveralls.io/r/OpenClassrooms/TranslationBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2e060a26-7a9b-4549-9a44-c2aec7c42b24/mini.png)](https://insight.sensiolabs.com/projects/2e060a26-7a9b-4549-9a44-c2aec7c42b24)

Symfony2 Bundle that checks missing translations keys and fix Transifex behavior

## Installation
This bundle can be installed using composer:

```composer require openclassrooms/translation-bundle```
or by adding the package to the composer.json file directly.

```json
{
    "require": {
        "openclassrooms/translation-bundle": "*"
    }
}
```

After the package has been installed, add the bundle to the AppKernel.php file:

```php
// in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new OpenClassrooms\Bundle\TranslationBundle\OpenClassroomsTranslationBundle(),
        // ...
);
```

## Configuration
Add the elasticsearch hosts to the config.yml

``` yml
openclassrooms_translation:
    locale_source: fr
    locale_targets:
        - en
        - es
    bundles:
        - AppBundle
        - UserBundle

```
