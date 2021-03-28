<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\NotificationBundle\Command;

use Sonata\NotificationBundle\Backend\QueueDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CleanupCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('sonata:notification:cleanup');
        $this->setDescription('Clean up backend message');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('<info>Starting ... </info>');

        $this->getBackend()->cleanup();

        $output->writeln('done!');
    }

    /**
     * @return BackendInterface
     */
    private function getBackend()
    {
        $backend = $this->getContainer()->get('sonata.notification.backend');

        if ($backend instanceof QueueDispatcherInterface) {
            return $backend->getBackend(null);
        }

        return $backend;
    }

    private function getContainer(): ContainerInterface
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getApplication()->getKernel();

        return $kernel->getContainer();
    }
}
