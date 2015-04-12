<?php
namespace Corley\Deb\Describe;

use Symfony\Component\Yaml\Parser;
use wdm\debian\Packager;
use wdm\debian\control\StandardFile;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PackageCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('package')
            ->setDescription('Create the .deb package')
            ->addArgument( 'name',
                InputArgument::REQUIRED,
                'The source yml file thats will generate a deb file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = new Parser();

        $packager = new Packager();
        $packager->setControl(new StandardFile());

        $describer = new Describer($parser, $packager);
        $command = $describer->compose(file_get_contents($input->getArgument("name")));

        $output->writeln($command);
    }
}
