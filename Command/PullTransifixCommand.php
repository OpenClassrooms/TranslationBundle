<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
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

        $paths = $this->getBundlesPath();

        $finder = new Finder();
        $finder->files()->in($paths)->path('Resources/translations')->name('*.yml');

        /** @var File $file */
        foreach ($finder as $file) {
            $output->writeln($file->getRealpath());
            $this->getContainer()->get('openclassrooms.translation.transifix_service')
                ->fixYamlFile($file->getRealpath());
        }
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
}
