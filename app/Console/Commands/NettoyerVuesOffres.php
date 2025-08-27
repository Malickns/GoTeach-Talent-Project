<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OffreVueService;
use Illuminate\Support\Facades\Log;

class NettoyerVuesOffres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offres:nettoyer-vues {--days=365 : Nombre de jours à conserver} {--force : Forcer le nettoyage sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les anciennes vues d\'offres pour maintenir les performances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $force = $this->option('force');

        if (!$force) {
            if (!$this->confirm("Êtes-vous sûr de vouloir supprimer les vues d'offres de plus de {$days} jours ?")) {
                $this->info('Opération annulée.');
                return 0;
            }
        }

        $this->info("Nettoyage des vues d'offres de plus de {$days} jours...");

        try {
            $deletedCount = OffreVueService::nettoyerAnciennesVues($days);

            if ($deletedCount > 0) {
                $this->info("✅ {$deletedCount} vues d'offres ont été supprimées avec succès.");
                
                // Log de la commande
                Log::info("Commande de nettoyage des vues d'offres exécutée", [
                    'deleted_count' => $deletedCount,
                    'older_than_days' => $days,
                    'executed_by' => 'artisan_command'
                ]);
            } else {
                $this->info("ℹ️  Aucune vue d'offre à supprimer.");
            }

            // Optimiser les tables
            $this->info("Optimisation des tables...");
            $this->call('db:optimize');

            $this->info("🎉 Nettoyage terminé avec succès !");

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors du nettoyage : " . $e->getMessage());
            
            Log::error("Erreur lors du nettoyage des vues d'offres", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }

        return 0;
    }
}
