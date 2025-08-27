<?php
use App\Services\StatistiqueService;
$stats = StatistiqueService::getStatistiquesLocales();
?>
@extends('pages.admins.local.layout.app')

@section('title', 'Dashboard - Admin local')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Jeunes inscrits</p>
                    <h6 class="mb-0">{{ number_format($stats['jeunes_inscrits']) }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-building fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Entreprises</p>
                    <h6 class="mb-0">{{ number_format($stats['entreprises_partenaires']) }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-briefcase fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Offres actives</p>
                    <h6 class="mb-0">{{ number_format($stats['offres_actives']) }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-file-alt fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Candidatures (mois)</p>
                    <h6 class="mb-0">{{ number_format($stats['candidatures_mois']) }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Évolution mensuelle (placeholder)</h6>
                </div>
                <canvas id="salse-revenue"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Répartition (placeholder)</h6>
                </div>
                <canvas id="worldwide-sales"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection