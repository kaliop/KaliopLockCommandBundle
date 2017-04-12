<?php

namespace Kaliop\Bundle\ConsoleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass to register locked jobs to the registry
 *
 * @author    Benoit Wannepain <bwannepain@kaliop.com>
 */
class RegisterLockPass implements CompilerPassInterface
{
    /** @staticvar string The subscriber id */
    const SUBSCRIBER_ID = 'kaliop.console.subscriber.lock';

    /** @staticvar string */
    const SERVICE_TAG = 'console.command';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::SUBSCRIBER_ID)) {
            return;
        }

        $subscriberDefinition = $container->getDefinition(self::SUBSCRIBER_ID);

        foreach ($container->findTaggedServiceIds(self::SERVICE_TAG) as $serviceId => $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['lock']) && $tag['lock']) {
                    $serviceDefinition = $container->getDefinition($serviceId);
                    $subscriberDefinition->addMethodCall('registerCommand', [$serviceDefinition->getClass()]);
                }
            }
        }
    }
}
