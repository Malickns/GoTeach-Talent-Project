<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistrer les helpers comme directives Blade
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDate($expression); ?>";
        });

        Blade::directive('formatDateIntelligente', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDateIntelligente($expression); ?>";
        });

        Blade::directive('differenceHumaine', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::differenceHumaine($expression); ?>";
        });

        // Helper pour les statistiques
        Blade::directive('validerStatistiques', function ($expression) {
            return "<?php echo \App\Helpers\StatistiquesHelper::validerStatistiques($expression); ?>";
        });
    }
}
