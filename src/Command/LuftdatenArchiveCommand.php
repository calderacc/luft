<?php declare(strict_types=1);

namespace App\Command;

use App\Pollution\DataPersister\UniquePersisterInterface;
use App\Provider\Luftdaten\LuftdatenProvider;
use App\Provider\Luftdaten\SourceFetcher\ArchiveFetcher\ArchiveFetcherInterface;
use App\Provider\Luftdaten\SourceFetcher\ArchiveSourceFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LuftdatenArchiveCommand extends ContainerAwareCommand
{
    /** @var ArchiveFetcherInterface $archiveFetcher */
    protected $archiveFetcher;

    /** @var ArchiveSourceFetcherInterface $archiveSourceFetcher */
    protected $archiveSourceFetcher;

    /** @var UniquePersisterInterface $uniquePersister */
    protected $uniquePersister;

    /** @var LuftdatenProvider $provider */
    protected $provider;

    public function __construct(?string $name = null, ArchiveSourceFetcherInterface $archiveSourceFetcher,  ArchiveFetcherInterface $archiveFetcher, UniquePersisterInterface $uniquePersister, LuftdatenProvider $luftdatenProvider)
    {
        $this->archiveFetcher = $archiveFetcher;
        $this->archiveSourceFetcher = $archiveSourceFetcher;
        $this->uniquePersister = $uniquePersister;
        $this->provider = $luftdatenProvider;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName('luft:luftdaten-archive')
            ->setDescription('')
            ->addOption('flush-after', null, InputOption::VALUE_OPTIONAL, '', 100)
            ->addArgument('date', InputArgument::REQUIRED, 'Date of data to fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $dateTime = new \DateTime($input->getArgument('date'));

        $this->uniquePersister->setProvider($this->provider);

         $this->archiveSourceFetcher
            ->setDateTime($dateTime)
            ->fetchStationCsvFiles();

        $csvLinkList = $this->archiveSourceFetcher->getCsvLinkList();

        $progressBar = new ProgressBar($output, count($csvLinkList));

        $offset = 0;
        $length = (int) $input->getOption('flush-after');
        $maxOffset = floor(count($csvLinkList) / $length);

        for ($offset = 0; $offset <= $maxOffset; ++$offset) {
            $offsetLinkList = array_slice($csvLinkList, $offset * $length, $length);

            $this->archiveFetcher->setCsvLinkList($offsetLinkList);

            $valueList = $this->archiveFetcher->fetch(function () use ($progressBar) {
                $progressBar->advance();
            });

            $this->uniquePersister->reset()->persistValues($valueList);
        }

        $progressBar->finish();

        $output->writeln(sprintf('Persisted values: <info>%d</info>', count($this->uniquePersister->getNewValueList())));
        $output->writeln(sprintf('Skipped values: <info>%d</info>', count($this->uniquePersister->getDuplicateDataList())));
    }
}
