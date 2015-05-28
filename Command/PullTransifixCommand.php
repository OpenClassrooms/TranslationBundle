<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class PullTransifixCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('transifix:pull');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        exec('tx pull --mode reviewed');

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

        $bundles = $this->getContainer()->get('kernel')->getBundles();

        $paths = array();
        foreach ($bundles as $bundle) {
            if (in_array($bundle->getName(), $bundlesNames)) {
                $paths[] = $bundle->getPath();
            }
        }

        $finder = new Finder();

        foreach ($paths as $path) {
            $finder->files()->in($path . '/Resources/translations')->name('*.yml');
            foreach ($finder as $file) {
                $this->getContainer()->get('openclassrooms.translation.transifix_service')
                    ->fixYamlFile($file->getRealpath())
                ;
            }
        }
    }
}
