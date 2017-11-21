<?php


namespace Kaliop\LockCommandBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Kaliop\Bundle\ConsoleBundle\DependencyInjection\Compiler\RegisterLockPass;


class LockCommandBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new RegisterLockPass());
    }
}
