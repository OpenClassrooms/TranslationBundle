<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class FindMissingKeysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('translation:find-missing-keys');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = $this->getBundlesPath();
        $localeSource = $this->getContainer()->getParameter('openclassrooms.translation.locale_source');
        $localeTargets = $this->getContainer()->getParameter('openclassrooms.translation.locale_targets');

        $localeKeys = $this->getContainer()->get('openclassrooms.translation.catalogue_service')->findMissingKeys(
            $localeSource,
            $localeTargets,
            $paths
        );

        $this->displayLocalesResults($output, $localeKeys);
    }

    /**
     * @return string[]
     */
    private function getBundlesPath()
    {
        /** @var BundleInterface[] $bundles */
        $bundles = $this->getContainer()->get('kernel')->getBundles();
        $bundlesNames = $this->getContainer()->getParameter('openclassrooms.translation.bundles');

        $paths = array();
        foreach ($bundles as $bundle) {
            if (in_array($bundle->getName(), $bundlesNames)) {
                $paths[] = $bundle->getPath();
            }
        }

        return $paths;
    }

    private function displayLocalesResults(OutputInterface $output, $localeKeys)
    {
        $missingKeyCount = 0;
        $tables = array();
        foreach ($localeKeys as $locale => $keys) {
            $missingKeyCount += count($keys);

            /** @var Table $table */
            $table = $this->getHelper('table');
            $table->setHeaders(array($locale));
            $table->setRows($keys);
            $tables[] = $table;
        }

        $output->writeln($missingKeyCount);

        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            foreach ($tables as $table) {
                $table->render($output);
            }
        }
    }

}
