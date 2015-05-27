<?php

namespace Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class PushTransifixCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('transifix:push');
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

        $finder = new Finder();

        foreach ($paths as $path) {
            
            $finder->files()->in($path . 'Resources/translations')->name('*.yml');
            foreach ($finder as $file) {
                $this->getContainer()->get('openclassrooms.translation.transifex_service')
                    ->fixYamlFile($file->getRealpath())
                ;
            }
        }
    }
}
