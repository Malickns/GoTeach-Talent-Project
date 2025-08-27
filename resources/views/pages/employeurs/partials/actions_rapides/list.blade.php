<div class="sidebar-card quick-actions-compact">
    <h4 style="margin-bottom: 12px; color: var(--dark); font-size: 16px; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-bolt" style="color:var(--accent);"></i>
        Actions rapides
    </h4>
    <style>
        .qa-btn { display: flex; align-items: center; gap: 10px; width: 100%; border: 1px solid #eceff3; background: #f5f7fa; color: var(--primary); font-weight: 700; font-size: 13px; padding: 10px 12px; border-radius: 10px; cursor: pointer; transition: background .2s ease, transform .1s ease; }
        .qa-btn i { color: var(--accent); font-size: 14px; }
        .qa-btn:hover { background: #eef2f6; }
        .qa-group { display: flex; flex-direction: column; gap: 8px; }
    </style>
    <div class="qa-group">
        <button class="qa-btn" onclick="window.location.href='{{ route('offres-emplois.create') }}'">
            <i class="fas fa-plus"></i> Publier une offre
        </button>
        <button class="qa-btn" onclick="employeurDashboard.showToast('Candidatures (à venir)','info')">
            <i class="fas fa-file-alt"></i> Candidatures
        </button>
        <button class="qa-btn" onclick="openModal('searchTalents')">
            <i class="fas fa-search"></i> Rechercher talents
        </button>
        <button class="qa-btn" onclick="window.location.href='{{ route('offres-emplois.index') }}'">
            <i class="fas fa-briefcase"></i> Gérer mes offres
        </button>
    </div>
</div>


