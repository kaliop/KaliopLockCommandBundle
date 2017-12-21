# Kaliop Lock Command Bundle

## Installation

### Configure repository

```
php composer.phar config repositories.kaliopConsoleBundle '{ "type": "vcs", "url": "https://github.com/kaliop/kaliop-lock-command-bundle.git" }'
```

### Install bundle

```
php composer.phar require kaliop/lock-command-bundle
```

### Add bundle to the Symfony kernel

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            ...
            new Kaliop\LockCommandBundle\LockCommandBundle(),
            ...
        ];
    }
}
```

### Remove bundle

```
$ php composer.phar remove kaliop/lock-command-bundle
```

## Usage

### Command locker

This bundle gives the possibility to "lock" console commands in order to prevent concurrent execution.

In order to do so, you have to declare your command as a service and tag it with `lock: true`

```php
services:
    test.console.command:
        class: AppBundle\Command\TestLockCommand
        tags:
            - { name: "console.command", lock: true }
```

As you can see, the `console.command` tag has available a new lock option, which can be set to either `true` or `false`.
Commands registered with the lock option set to `true` will be locked at the command start event and unlocked at the command terminate or exception event.

Additionally, if needed, you can pass a `--no-lock` option on the command line when launching a command if you don't want the command to be locked for a single execution:
```
php bin/console kaliop:command:example --no-lock
```
