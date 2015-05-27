<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Services\Impl;

use OpenClassrooms\Bundle\TranslationBundle\Services\FileSystem;
use OpenClassrooms\Bundle\TranslationBundle\Services\Transifix;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class TransifixImpl implements Transifix
{
    const LANGUAGE_ROOT_SIZE = 2;

    const YAML_INDENTATION = 2;

    const YAML_INLINE_DEPTH = 50;

    /**
     * @var Yaml
     */
    private $yaml;

    /**
     * @var \HTMLPurifier
     */
    private $htmlPurifier;

    /**
     * @var FileSystem
     */
    private $fileSystem;

    public function fixYamlFile($filePath)
    {
        $translations = $this->loadYaml($filePath);

        if ($translations) {
            $rootName = $this->getYamlRootName($translations);

            if (self::LANGUAGE_ROOT_SIZE === strlen($rootName)) {
                $rootName = $this->getOriginalYamlRootName($filePath);
                $translations = $this->changeYamlRoot($translations, $rootName);
            }

            $translations = $this->fixXss($translations);
        }

        $this->fileSystem->dump(
            $filePath,
            $this->yaml->dump($translations, self::YAML_INLINE_DEPTH, self::YAML_INDENTATION)
        );
    }

    /**
     * @return array
     */
    public function loadYaml($filePath)
    {
        $lines = false;

        while (!$lines) {
            try {
                $lines = $this->yaml->parse($this->fileSystem->getContent($filePath));
            } catch (ParseException $e) {
                $this->fixCutLine($filePath, $e->getParsedLine());
            }
        }

        return $lines;
    }

    /**
     * @return array
     */
    public function fixXss(array $translations)
    {
        return $this->purifyArray($translations);
    }

    /**
     * @return string
     */
    private function getYamlRootName(array $lines)
    {
        reset($lines);

        return key($lines);
    }

    /**
     * @return string
     */
    private function getOriginalYamlRootName($filePath)
    {
        $originalFilePath = preg_replace('/(..)(\.yml)/', 'fr$2', $filePath);

        $lines = $this->loadYaml($originalFilePath);

        return $this->getYamlRootName($lines);
    }

    /**
     * @return array
     */
    private function changeYamlRoot(array $lines, $root)
    {
        return array($root => reset($lines));
    }

    /**
     * @return array
     */
    private function purifyArray(array $lines)
    {
        foreach ($lines as $k => $line) {
            if (is_array($line)) {
                $lines[$k] = $this->purifyArray($line);
            } else {
                $lines[$k] = urldecode($this->htmlPurifier->purify($line));
            }
        }

        return $lines;
    }

    private function fixCutLine($filePath, $line)
    {
        $file = $this->fileSystem->getContentToArray($filePath);
        $file[$line - 1] = rtrim($file[$line - 1]) . ' ';
        $file[$line] = ltrim($file[$line]);
        $this->fileSystem->dump($filePath, $file);
    }

    public function setYaml(Yaml $yaml)
    {
        $this->yaml = $yaml;
    }

    public function setHtmlPurifier(\HTMLPurifier $htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
    }

    public function setFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

}
