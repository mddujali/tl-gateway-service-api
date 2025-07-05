# Gateway Service API
Expose API endpoints that bridges between `Auth Service API` and `IP Service API`.

## Requirements
- Composer 2.7.x or higher
- 8.3.x or higher
- Node.js 20.x.x or higher
- Docker 20.x.x or higher

## Setup
- To install project dependencies
    - `composer install`
    - `npm install`
- Copy `.env.local.example` to `.env`
- Copy `.env.testing.example` to `.env.testing`
- Copy `docker-compose.yml.example` to `docker-compose.yml`
- Add `alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'` to `.bashrc` or `.zshrc`
- Run `sail build` to build the Docker containers
- Run `sail up` to start the Docker containers
- Run `sail up -d` to start the Docker containers in detached mode
- Run `sail exec app composer sail:resolve-permissions` to resolve files and folders ownership issue
- Run `sail artisan key:generate` to create application key
- Run `sail artisan key:generate --env=testing` to create application key for testing
- Run `sail artisan migrate` to execute outstanding migrations
- Run `sail artisan db:seed` to seed all tables with records
- Run `sail artisan migrate --seed` to drop all tables and run all migrations and seed all tables with records
- Run `sail artisan migrate:fresh --seed` to drop all tables and re-run all migrations and seed all tables with records
- Use RSA public/private key strategy
  - Copy `jwt.key` and `jwt.pub` to storage/oauth from Auth Service API

## Development Commands
- Run `sail artisan ide-helper:model --nowrite` to generate PHPDocs for models
- Run `sail artisan ide-helper:generate` to generate PHPDocs for Laravel Facades
- Run `sail artisan ide-helper:meta` to generate PhpStorm meta file
- Run `sail artisan test` to run the application tests
- Add `alias pint=./vendor/bin/pint` to `.bashrc` or `.zshrc`
- Run `pint --test` to simply inspect your code for style errors
- Run `pint --dirty` to only modify the files that have uncommitted changes according to Git
- Run `pint --repair` to fix any files with code style errors but also exit with a non-zero exit code if any errors were fixed
- Run `./vendor/bin/rector process` to refactor codebase
- Run `sail php ./vendor/bin/phpstan analyse` to analyse code with Larastan (PHPStan)
- Run `sail composer lint` for coding style checks and fixes
- Run `sail composer dump-autoload` to regenerate the list of all classes that need to be included in the project
- Run `sail artisan cache:clear` to clear the cache
- Run `sail artisan config:clear` to clear the configuration cache
- Run `sail artisan clear-compiled` to clear the compiled classes
- Run `sail artisan event:clear` to clear the event cache
- Run `sail artisan route:clear` to clear the route cache
- Run `sail artisan view:clear` to clear the view cache
- Run `sail artisan optimize:clear` to remove all the cache files
