<?php

declare(strict_types=1);

namespace App\Command\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class LoadFixturesCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(EntityManagerInterface $em, KernelInterface $kernel)
    {
        parent::__construct('jh:fixtures:load');
        $this->em = $em;
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tablesIDReset = [
        
        ];

        foreach ($tablesIDReset as $table) {
            $metadata = $this->em->getClassMetadata($table);
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());
        }

        $this->loadFixtures($output);

        $connection = $this->em->getConnection();
        $connection->beginTransaction();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tablesIDReset as $table) {
            $metadata = $this->em->getClassMetadata($table);
            $connection->query('ALTER TABLE '.$metadata->getTableName().' CHANGE `id` `id` int(11) NOT NULL AUTO_INCREMENT FIRST');
        }

        $connection->query('SET FOREIGN_KEY_CHECKS=1');
        $connection->commit();
    }

    protected function loadFixtures(OutputInterface $output): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $application->run(
            new ArrayInput(
                [
                    'command' => 'doctrine:schema:drop',
                    '--force' => true,
                ]
            ),
            $output
        );

        $application->run(
            new ArrayInput(
                [
                    'command' => 'doctrine:schema:create',
                ]
            ),
            $output
        );

        // first run create index and alias
//        $application->run(
//            new ArrayInput(
//                [
//                    'command' => 'fos:elastica:populate',
//                ]
//            ),
//            $output
//        );

        $loadFixturesInput = new ArrayInput(
            [
                'command' => 'doctrine:fixtures:load',
                '--no-interaction' => true,
                '--purge-with-truncate' => true,
            ]
        );

        $application->run($loadFixturesInput, $output);
    }
}
