<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
        $process = new Process('tx push -s');
        $process->run();
    }
}
