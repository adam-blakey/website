@servers(['production' => '-A ' . getenv('DEPLOY_SSH_USER') . '@' . getenv('DEPLOY_SSH_HOST') . ' -p ' . getenv('DEPLOY_SSH_PORT')])

@setup
    /*
    |--------------------------------------------------------------------------
    | Variables we take branch and domain form envoy command
    |--------------------------------------------------------------------------
    */
    $root = getenv('DEPLOY_SSH_USER');
    $repository = 'git@github.com:adam-blakey/website.git';

    /*
    |--------------------------------------------------------------------------
    | Fixed
    |--------------------------------------------------------------------------
    */
    $app_dir = '/home/' . $root . '/domains/' . $domain ;
    $resources_dir = '/resources/' . $domain .'/';
    $keep = 4;

    function logMessage($message){return "echo '\033[32m" . $message . "\033[0m';\n";}
@endsetup

@story('deploy', ['on' => 'production'])
    git_pull
    artisan_down
    optimize_installation
    migrate_database
    artisan_up
    application_deployed
@endstory

@task('git_pull')
    {{ logMessage('🐛  Pulling changes...') }}
    cd {{ $app_dir }}
    ssh-agent /bin/bash
    ssh-add ~/.ssh/GitHubActionsdeploy
    git reset --hard
    git fetch origin {{ $branch }}
    git fetch --tags --force
    git reset --hard origin/{{ $branch }}
    git describe --tags --abbrev=0 --always > VERSION
    rm -f bootstrap/cache/*.php
@endtask

@task('artisan_down')
    {{ logMessage('🔒  Putting Application into maintenance mode...') }}
    cd {{ $app_dir }}
    php artisan down
@endtask

@task('optimize_installation')
    {{ logMessage('✨  Optimizing installation...') }}
    cd {{ $app_dir }}
    php artisan clear-compiled --env=production
    php artisan optimize --env=production
    php artisan config:cache
    php artisan route:cache
    php artisan event:cache
    php artisan cache:clear

    php artisan queue:restart
@endtask

@task('migrate_database')
    {{ logMessage('🙈  Migrating database...') }}
    cd {{ $app_dir }}
    php artisan migrate --force
@endtask

@task('artisan_up')
    {{ logMessage('🔓  Bringing Application out of maintenance mode...') }}
    cd {{ $app_dir }}
    php artisan up
@endtask

@task('application_deployed')
    {{ logMessage('🚀  Application deployed!') }}
@endtask
