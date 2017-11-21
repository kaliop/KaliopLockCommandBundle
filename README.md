# Kaliop Console Bundle

## Installation

### Configure repository
```bash
$ php composer.phar config repositories.kaliopConsoleBundle '{ "type": "vcs", "url": "ssh://git@github.com:kaliop/kaliop-lock-command-bundle.git" }'
```
### Install library
```bash
$ php composer.phar require kaliop/lock-command-bundle
```
### Remove library
```bash
$ php composer.phar remove kaliop/lock-command-bundle
```
### Add bundle to AppKernel
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

## Usage

### Command locker

This bundle gives the possibility to lock/unlock console commands in order to avoid concurrency.
You first have to declare your command as a service (you should always declare commands as services):
```php
services:
    test.console.command:
        class: AppBundle\Command\TestLockCommand
        tags:
            - { name: "console.command", lock: true }
```
As you can see, the `console.command` tag has a new lock option (thus not mandatory) which obviously can be `true` or `false`.
Such registered commands with a lock option set to `true`, will be locked at the command start event and unlocked at the command terminate or exception event.

Additionnally, if needed, you can pass a `--no-lock` option on the command line when launching a command if you don't want the command to be locked:
```bash
$ php bin/console kaliop:command:example --no-lock
```
