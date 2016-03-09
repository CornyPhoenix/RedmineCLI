Redmine CLI 0.0.2
=================

> Command line tool written in PHP to communicate with your Redmine server.

## Requirements

You need at least PHP 5.6 and the cURL extension installed.

## Installation

To install Redmine CLI, follow these steps:

1.  Just download the latest **redmine.phar** from
https://github.com/CornyPhoenix/RedmineCLI/releases/latest
2. Execute the programm by calling `php redmine.phar` from the download directory.
3. It is recommended to copy the phar into a global direcotry, e.g. `/usr/local/bin/redmine`,
so you can execute the programm by just calling `redmine`.

## Commands

Available commands to interact with Redmine:

### API

* `redmine api http://redmine.example.com`
    - Selects an API endpoint
    - Asks you for an API key or username/password

### Project

* `redmine project [list]`
    - Lists all your available projects
* `redmine project select`
    - Selects the active project you work on

Also take a look at the [changenotes](CHANGENOTES.md).

## License

This tool is available under the [MIT license](LICENSE.md).
