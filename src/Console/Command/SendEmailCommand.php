<?php declare(strict_types=1);

namespace Swag\Training\SendMail\Console\Command;

use Shopware\Core\Content\Mail\Service\AbstractMailService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends Command
{
    private AbstractMailService $mailService;
    private EntityRepositoryInterface $entityRepository;

    /**
     * @param AbstractMailService $mailService
     * @param string|null $name
     */
    public function __construct(
        AbstractMailService $mailService,
        EntityRepositoryInterface $entityRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->mailService = $mailService;
        $this->entityRepository = $entityRepository;
    }

    protected function configure()
    {
        $this->setName('swag:mail:send')
            ->setDescription('Send a test mail')
            ->addArgument('email');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        if (empty($email)) {
            $output->writeln('<error>No email address given</error>');
            return 1;
        }

        $output->writeln('Sending mail to "' . $email . '"');
        $context = Context::createDefaultContext();

        $data = new DataBag();
        $data->set('recipients',  [$email => 'John Doe']);
        $data->set('senderName', 'info@yireo.com');
        $data->set('subject', 'Test from Shopware');
        $data->set('contentHtml', 'Hello World');
        $data->set('contentPlain', strip_tags('Hello World'));
        $data->set('salesChannelId', $this->getAnySalesChannelId());

        $templateData = [];
        $this->mailService->send($data->all(), $context, $templateData);

        return 0;
    }

    /**
     * @return string
     */
    private function getAnySalesChannelId(): string
    {
        $criteria = new Criteria();
        $searchResult = $this->entityRepository->searchIds($criteria, Context::createDefaultContext());
        $searchIds = $searchResult->getIds();
        return array_shift($searchIds);
    }
}
