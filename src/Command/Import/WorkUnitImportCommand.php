<?php

declare(strict_types=1);

namespace App\Command\Import;

use App\Base\WebProxy;
use App\Entity\WorkUnit;
use App\Enum\EQF;
use App\Enum\WorkUnitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WorkUnitImportCommand extends Command
{
    private const WORK_UNIT_JSON_URL = 'https://nsp.cz/api/workUnit?includePublic=true&includeSuggestion=false';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var WebProxy
     */
    private $proxy;

    public function __construct(EntityManagerInterface $em, WebProxy $proxy)
    {
        parent::__construct('jh:import:work_unit');
        $this->em = $em;
        $this->proxy = $proxy;
    }

    protected function configure()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $data = $this->getData($serializer, 0);

        $total = $data['count'];

        $loadedCount = \count($data['data']);

        $p = new ProgressBar($output, $total);
        while ($loadedCount < $total) {
            $data = $this->getData($serializer, $loadedCount);
            $this->importUnits($data);
            $loaded = \count($data['data']);
            $loadedCount += $loaded;
            $this->em->flush();
            $this->em->clear();
            $p->advance($loaded);
        }
        $p->finish();
    }

    private function getData(Serializer $serializer, $offset = 0)
    {
        $context = null;
        $headers = [
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\n".
                            "X-Accept-Version: v1\r\n",
            ],
        ];
        if ($this->proxy->isProxyEnabled()) {
            $context = $this->proxy->getStreamContext($headers);
        } else {
            $context = \stream_context_create($headers);
        }

        return $serializer->decode(\file_get_contents(self::WORK_UNIT_JSON_URL.'&limit=100&offset='.$offset, false, $context), 'json');
    }

    private function importUnits(array $data): void
    {
        foreach ($data['data'] as $unitArr) {
            $unit = new WorkUnit();
            $unit->setTitle($unitArr['title']);
            $unit->setCode($unitArr['code']);
            $unit->setCharacteristics($unitArr['characteristics']);
            $unit->setEqf(EQF::get($unitArr['eqf']['id']));
            $unit->setLegacyId($unitArr['legacyId']);
            $unit->setUrlSlug($unitArr['urlSlug']);
            $unit->setMaxWage($unitArr['maxWage']);
            $unit->setMinWage($unitArr['minWage']);
            $unit->setType(WorkUnitType::get($unitArr['type']));
            $unit->setNspCode($unitArr['nspCode']);
            $this->em->persist($unit);
        }
    }
}
