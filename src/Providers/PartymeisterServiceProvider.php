<?php

namespace Partymeister\Competitions\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsExportVotesToCSVCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsExportWinnersForDHLCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsGenerateCompetitionCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsGenerateEntryCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsLinkEntryFilesCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsPublishReleaseFilesCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsSyncEntriesCommand;
use Partymeister\Competitions\Console\Commands\PartymeisterCompetitionsSyncLiveVotingCommand;
use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Models\Component\ComponentEntry;
use Partymeister\Competitions\Models\Component\ComponentEntryScreenshot;
use Partymeister\Competitions\Models\Component\ComponentEntryUpload;
use Partymeister\Competitions\Models\Component\ComponentVoting;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Models\VoteCategory;

/**
 * Class PartymeisterServiceProvider
 *
 * @package Partymeister\Competitions\Providers
 */
class PartymeisterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->config();
        $this->routes();
        $this->routeModelBindings();
        $this->translations();
        $this->views();
        $this->navigationItems();
        $this->permissions();
        $this->registerCommands();
        $this->migrations();
        $this->publishResourceAssets();
        $this->components();
        merge_local_config_with_db_configuration_variables('partymeister-competitions');
    }

    public function config()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/partymeister-competitions-dhl-export.php', 'partymeister-competitions-dhl-export');
    }

    public function routes()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../../routes/web.php';
            require __DIR__.'/../../routes/api.php';
        }
    }

    public function routeModelBindings()
    {
        Route::bind('option_group', function ($id) {
            return OptionGroup::findOrFail($id);
        });

        Route::bind('competition_type', function ($id) {
            return CompetitionType::findOrFail($id);
        });

        Route::bind('competition', function ($id) {
            return Competition::findOrFail($id);
        });

        Route::bind('vote_category', function ($id) {
            return VoteCategory::findOrFail($id);
        });

        Route::bind('entry', function ($id) {
            return Entry::findOrFail($id);
        });
        Route::bind('access_key', function ($id) {
            return AccessKey::findOrFail($id);
        });
        Route::bind('competition_prize', function ($id) {
            return CompetitionPrize::findOrFail($id);
        });
        Route::bind('vote', function ($id) {
            return Vote::findOrFail($id);
        });

        // Components
        Route::bind('component_voting', function ($id) {
            return ComponentVoting::findOrFail($id);
        });

        Route::bind('component_entry', function ($id) {
            return ComponentEntry::findOrFail($id);
        });

        Route::bind('component_entry_screenshot', function ($id) {
            return ComponentEntryScreenshot::findOrFail($id);
        });

        Route::bind('component_entry_upload', function ($id) {
            return ComponentEntryUpload::findOrFail($id);
        });
    }

    public function translations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'partymeister-competitions');

        $this->publishes([
            __DIR__.'/../../lang' => resource_path('lang/vendor/partymeister-competitions'),
        ], 'motor-backend-translations');
    }

    public function views()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'partymeister-competitions');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/partymeister-competitions'),
        ], 'motor-backend-views');
    }

    public function navigationItems()
    {
        $config = $this->app['config']->get('motor-backend-navigation', []);
        $this->app['config']->set('motor-backend-navigation', array_replace_recursive(require __DIR__.'/../../config/motor-backend-navigation.php', $config));
    }

    public function permissions()
    {
        $config = $this->app['config']->get('motor-backend-permissions', []);
        $this->app['config']->set('motor-backend-permissions', array_replace_recursive(require __DIR__.'/../../config/motor-backend-permissions.php', $config));
    }

    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PartymeisterCompetitionsLinkEntryFilesCommand::class,
                PartymeisterCompetitionsSyncEntriesCommand::class,
                PartymeisterCompetitionsSyncLiveVotingCommand::class,
                PartymeisterCompetitionsPublishReleaseFilesCommand::class,
                PartymeisterCompetitionsExportVotesToCSVCommand::class,
                PartymeisterCompetitionsGenerateCompetitionCommand::class,
                PartymeisterCompetitionsGenerateEntryCommand::class,
                PartymeisterCompetitionsExportWinnersForDHLCommand::class,
            ]);
        }
    }

    public function migrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    public function publishResourceAssets()
    {
        $assets = [
            __DIR__.'/../../resources/assets/sass' => resource_path('assets/sass'),
            __DIR__.'/../../resources/assets/pdf'  => resource_path('assets/pdf'),
        ];

        $this->publishes($assets, 'partymeister-competitions-install-resources');
    }

    public function components()
    {
        $config = $this->app['config']->get('motor-cms-page-components', []);
        $this->app['config']->set('motor-cms-page-components', array_replace_recursive(require __DIR__.'/../../config/motor-cms-page-components.php', $config));
    }
}
