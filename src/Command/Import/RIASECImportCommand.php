<?php

declare(strict_types=1);

namespace App\Command\Import;

use App\Base\DirService;
use App\Enum\RIASEC;
use App\Repository\WorkUnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RIASECImportCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DirService
     */
    private $dirService;

    /**
     * @var WorkUnitRepository
     */
    private $workUnitRepository;

    public function __construct(WorkUnitRepository $workUnitRepository, EntityManagerInterface $em, DirService $dirService)
    {
        parent::__construct('jh:import:riasec');
        $this->em = $em;
        $this->dirService = $dirService;
        $this->workUnitRepository = $workUnitRepository;
    }

    protected function configure()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder(';')]);

        $data = $serializer->decode(file_get_contents($this->dirService->getProjectDir().'data/riasec.csv'), 'csv');
        $notFoundCount = 0;
        /*
         * @var array = [
         *            'name' => '',
         *            'id' => '',
         *            'riasec' => '',
         *            ]
         */
        foreach ($data as $item) {
            if ('' === $item['riasec']) {
                continue;
            }

            $unit = $this->workUnitRepository->findOneBy(['legacyId' => $item['id']]);

            if (null === $unit) {
                $unit = $this->workUnitRepository->findOneBy(['title' => $item['name']]);
            }

            if (null === $unit) {
                $output->writeln('Missing unit: '.$item['id'].' '.$item['name']);
                ++$notFoundCount;
                continue;
            }

            if ($unit->getTitle() !== $item['name']) {
                $output->writeln('Title mismatch: '.$unit->getTitle().' '.$item['name']);
            }

            $riasec = \explode(',', $item['riasec']);

            if (0 === \count($riasec)) {
                continue;
            }

            $riasecArr = [];
            foreach ($riasec as $riasecItem) {
                $riasecArr[] = RIASEC::get($riasecItem);
            }

            if (\count($riasecArr) > 1) {
                $output->writeln('More than one: '.$item['id']);
            }

            $unit->setRiasec($riasecArr);
            $this->em->persist($unit);
        }
        $output->writeln('Not found count: '.$notFoundCount);
        $this->em->flush();
    }
}
