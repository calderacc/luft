<?php declare(strict_types=1);

namespace App\Command;

use App\Pollution\DataPersister\UniquePersisterInterface;
use App\Pollution\Value\Value;
use App\Provider\Luftdaten\LuftdatenProvider;
use App\Provider\Luftdaten\SourceFetcher\ArchiveFetcher\ArchiveFetcher;
use App\Provider\Luftdaten\SourceFetcher\Parser\Parser;
use App\Provider\Luftdaten\SourceFetcher\SourceFetcher;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LuftdatenArchiveCommand extends ContainerAwareCommand
{
    /** @var ArchiveFetcher $archiveFetcher */
    protected $archiveFetcher;

    /** @var UniquePersisterInterface $uniquePersister */
    protected $uniquePersister;

    /** @var LuftdatenProvider $provider */
    protected $provider;

    public function __construct(?string $name = null, ArchiveFetcher $archiveFetcher, UniquePersisterInterface $uniquePersister, LuftdatenProvider $luftdatenProvider)
    {
        $this->archiveFetcher = $archiveFetcher;
        $this->uniquePersister = $uniquePersister;
        $this->provider = $luftdatenProvider;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName('luft:luftdaten-archive')
            ->setDescription('')
            ->addArgument('date', InputArgument::REQUIRED, 'Date of data to fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $valueList = $this->archiveFetcher
            ->setDateTime(new \DateTime('2018-11-01'))
            ->fetch();

        $this->uniquePersister
            ->setProvider($this->provider)
            ->persistValues($valueList);
    }
}
