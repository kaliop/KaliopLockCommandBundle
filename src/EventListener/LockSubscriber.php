<?php


namespace Kaliop\LockCommandBundle\EventListener;


use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\LockHandler;

/**
 * Class LockSubscriber
 * @package Tollens\Bundle\AkeneoBundle\EventListener
 */
class LockSubscriber implements EventSubscriberInterface
{
    /** @var array */
    protected $supportedCommands;

    /** @var LockHandler */
    protected $lockHandler;

    /**
     * LockSubscriber constructor.
     */
    public function __construct()
    {
        $this->supportedCommands = [];
    }

    /**
     * @param string $command
     */
    public function registerCommand($command)
    {
        $this->supportedCommands[] = $command;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onCommandStart',
            ConsoleEvents::TERMINATE => 'onCommandTerminate',
            ConsoleEvents::EXCEPTION => 'onCommandTerminate',
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onCommandStart(ConsoleCommandEvent $event)
    {
        if (false === $this->supportsCommand($event)) {
            return;
        }

        $output = $event->getOutput();
        $this->lockHandler = new LockHandler(md5(get_class($event->getCommand())));
        if (!$this->lockHandler->lock()) {
            $output->writeln(sprintf('<error>Command already locked by %s</error>', get_class($this)));
            $event->disableCommand();
        } else {
            $output->writeln(sprintf('<info>Command locked by %s</info>', get_class($this)));
        }
    }

    /**
     * @param ConsoleEvent $event
     */
    public function onCommandTerminate(ConsoleEvent $event)
    {
        if ($event->getExitCode() === ConsoleCommandEvent::RETURN_CODE_DISABLED
            || false === $this->supportsCommand($event)) {

            return;
        }

        $output = $event->getOutput();
        $this->lockHandler->release();
        $output->writeln(sprintf('<info>Command unlocked by %s</info>', get_class($this)));
    }

    /**
     * @param ConsoleEvent $event
     * @return bool
     */
    protected function supportsCommand(ConsoleEvent $event)
    {
        $def = explode(' ', $event->getInput()->__toString());
        if ($event->getInput()->hasOption('no-lock') || in_array('--no-lock', $def)) {
            return false;
        }

        return in_array(get_class($event->getCommand()), $this->supportedCommands);
    }
}
