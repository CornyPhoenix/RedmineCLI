Redmine CLI 0.0.1
=================

> Command line tool written in PHP to communicate with your Redmine server.

Available commands to interact with Redmine:

## API

* `redmine api http://redmine.example.com`
    - Selects an API endpoint
    - Asks you for an API key or username/password

## Project

* `redmine project [list]`
    - Lists all your available projects
* `redmine project select`
    - Selects the active project you work on