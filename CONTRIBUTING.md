# Contributing
First and foremost, we appreciate your interest in this project. This document contains essential information, should you want to contribute.

## Development discussion
For bugs, new features or improvements, open a new [issue](https://github.com/CruzVermelhaCNE/OperatorSoftware/issues/new).

## Which Branch?
Pull requests should always be done against the `master` branch.

## Coding Style
This project follows the [PSR-2](https://www.php-fig.org/psr/psr-2/) coding style guide and the [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloader standard.

### PHP Coding Standards Fixer
A [PHP CS Fixer](https://cs.symfony.com/) script is hooked into the CI pipeline, so you'll be notified of any coding standard issue when pushing code.

#### Check
On each build, the `composer cs-check` script is executed to make sure the coding standards are followed.

#### Fix
If the build breaks due to coding standards, the following command fixes the issues:

```sh
composer cs-fix <file or directory name>
```

#### Pre-Commit Hook installation
To run the coding style check before each commit, install the bundled script in the project root with the following command:

```sh
cp pre-commit.sh .git/hooks/pre-commit
```

This prevents code from being committed if the check fails.

## Committing to git
Each commit **MUST** have a proper message describing the work that has been done.
This is called [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/).

We use the following types:

* **build**: Changes that affect the build system or external dependencies (example scopes: gulp, broccoli, npm)
* **ci**: Changes to our CI configuration files and scripts (example scopes: Travis, Circle, BrowserStack, SauceLabs)
* **docs**: Documentation only changes
* **task**: A task that doesn't fit in any other type
* **feat**: A new feature
* **fix**: A bug fix
* **conf**: A change in the configurations
* **perf**: A code change that improves performance
* **refactor**: A code change that neither fixes a bug nor adds a feature
* **test**: Adding missing tests or correcting existing tests
* **visual**: A visual change in any of sort

We use the following scopes:

* **user**
* **cdr**
* **fop2**
* **analytics**
* **dashboard**


## Branching strategy
We will be using the **branch-per-issue** workflow.

This means, that for each open [issue](https://github.com/vostpt/api/issues), we'll create a corresponding **git** branch.

For instance, issue `#123` should have a corresponding `OS-123/ShortTaskDescription` branch, which **MUST** branch off the latest code in `master`.

## This CONTRIBUTING guide is base on:

[VOST Portugal API](https://github.com/vostpt/api/) CONTRIBUTING guide. [Link](https://github.com/vostpt/api/blob/master/CONTRIBUTING.md)