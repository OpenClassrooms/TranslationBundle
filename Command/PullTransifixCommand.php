<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

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
        $process = new Process('tx pull --mode reviewed');
        $process->run();

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
                $output->writeln($file->getRealpath());die();
                $this->getContainer()->get('openclassrooms.translation.transifix_service')
                    ->fixYamlFile($file->getRealpath())
                ;
            }
        }
    }
}
