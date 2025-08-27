<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\OffreVueService;
use Illuminate\Support\Facades\Log;

class EnregistrerVueOffre implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;
    public $backoff = [10, 30, 60]; // Délais entre les tentatives en secondes

    protected $offreId;
    protected $jeuneId;
    protected $ip;
    protected $userAgent;
    protected $timestamp;

    /**
     * Create a new job instance.
     */
    public function __construct($offreId, $jeuneId = null, $ip = null, $userAgent = null)
    {
        $this->offreId = $offreId;
        $this->jeuneId = $jeuneId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->timestamp = now();
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Enregistrer la vue avec le service
            $vue = OffreVueService::enregistrerVue($this->offreId, $this->jeuneId, true);

            if ($vue) {
                Log::info('Vue d\'offre enregistrée avec succès via job', [
                    'offre_id' => $this->offreId,
                    'jeune_id' => $this->jeuneId,
                    'job_id' => $this->job->getJobId(),
                    'queue' => $this->queue
                ]);
            } else {
                Log::warning('Vue d\'offre non enregistrée (probablement déjà vue)', [
                    'offre_id' => $this->offreId,
                    'jeune_id' => $this->jeuneId,
                    'job_id' => $this->job->getJobId()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'exécution du job EnregistrerVueOffre', [
                'offre_id' => $this->offreId,
                'jeune_id' => $this->jeuneId,
                'job_id' => $this->job->getJobId(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Relancer l'exception pour que le job soit retenté
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Job EnregistrerVueOffre a échoué définitivement', [
            'offre_id' => $this->offreId,
            'jeune_id' => $this->jeuneId,
            'job_id' => $this->job->getJobId(),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Ici on pourrait envoyer une notification à l'admin
        // ou enregistrer l'échec dans une table de suivi
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags()
    {
        return [
            'offre_vue',
            'offre_id:' . $this->offreId,
            'jeune_id:' . ($this->jeuneId ?? 'anonymous')
        ];
    }
}
