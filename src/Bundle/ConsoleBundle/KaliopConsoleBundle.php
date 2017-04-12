<?php


namespace Kaliop\Bundle\ConsoleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Kaliop\Bundle\ConsoleBundle\DependencyInjection\Compiler\RegisterLockPass;


class KaliopConsoleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new RegisterLockPass());
    }
}
