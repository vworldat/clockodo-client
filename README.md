Command-line interface for clockodo.com
=======================================

This application provides a command-line interface for the [https://my.clockodo.com/](https://my.clockodo.com/) REST API.

Installation & Setup
--------------------

- Clone repository
- Run `composer install`
- Run `php bin/console status`: you will be asked for your clockodo credentials. They will be stored in `clockodo.yml` inside your project folder.
- Create a convenience symlink, like `sudo ln -s /path/to/checked/out/project/bin/console /usr/local/bin/clockodo`

Usage
-----

Assuming you are using the symlink, just type `clockodo` or `clockodo list`.

```
> clockodo
Clockodo command-line interface version 0.1

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --no-debug        Switches off debug mode.
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help         Displays help for a command
  list         Lists commands
  status       Show account/clock status overview
 clock
  clock:start  Start the clock, providing information about the current task
  clock:stop   Stop a running clock
```
