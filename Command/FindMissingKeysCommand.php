<?php

namespace OC\SystemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $bundles = $this->getContainer()->get('kernel')->getBundles();

        $bundlesNames = array(
            'AppBundle',
            'OCCommonBundle',
            'CorporateBundle',
            'CourseBundle',
            'PartnerBundle',
            'OCSearchBundle',
            'OCShopBundle',
            'OCSpecialEventBundle',
            'SynchronisationBundle',
            'OCSystemBundle',
            'OCTraceBundle',
            'OCUserBundle'
        );

        $paths = array();
        foreach ($bundles as $bundle) {
            if (in_array($bundle->getName(), $bundlesNames)) {
                $paths[] = $bundle->getPath();
            }
        }

        $keys = $this->getContainer()->get('openclassrooms.translation.catalogue_service')->findMissingKeys('fr', array('en'), $paths);

        if($keys['en']) {
            $output->writeln(count($keys['en']));
        } else {
            $output->writeln(false);
        }

        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            $keysPrint = array();
            foreach ($keys['en'] as $key) {
                $keysPrint[] = array($key);
            }

            /** @var Table $table */
            $table = $this->getHelper('table');
            $table->setRows($keysPrint);
            $table->render($output);
        }
    }

}
