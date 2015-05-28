<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class PullPushTransifixCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('transifix:pull-push');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pull = $this->getApplication()->find('transifix:pull');
        $pull->run(new ArrayInput(array()), $output);

        $push = $this->getApplication()->find('transifix:push');
        $push->run(new ArrayInput(array()), $output);
    }
}
