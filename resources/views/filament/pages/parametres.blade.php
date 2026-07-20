<x-filament-panels::page>
    <div class="space-y-6">

        <div>
            <h1 class="text-3xl font-bold tracking-tight">Paramètres</h1>
            <p style="color: var(--c-foreground-subtle, #6b7280);">Configuration générale du système</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">

            {{-- Dépôts & Stock --}}
            <x-filament::section
                heading="Dépôts & Stock"
                description="Aperçu des données de base configurées"
            >
                <div class="space-y-3">
                    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                        <span style="font-size:0.875rem;">Dépôts</span>
                        <x-filament::badge color="gray">{{ $stats['depots'] }}</x-filament::badge>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                        <span style="font-size:0.875rem;">Matières premières</span>
                        <x-filament::badge color="gray">{{ $stats['matieres'] }}</x-filament::badge>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:0.5rem;">
                        <span style="font-size:0.875rem;">Produits finis</span>
                        <x-filament::badge color="gray">{{ $stats['produits'] }}</x-filament::badge>
                    </div>
                </div>
            </x-filament::section>

            {{-- Commercial --}}
            <x-filament::section
                heading="Commercial"
                description="Aperçu des données de base configurées"
            >
                <div class="space-y-3">
                    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                        <span style="font-size:0.875rem;">Clients actifs</span>
                        <x-filament::badge color="gray">{{ $stats['clients'] }}</x-filament::badge>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                        <span style="font-size:0.875rem;">Étapes de production</span>
                        <x-filament::badge color="gray">{{ $stats['etapes'] }}</x-filament::badge>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:0.5rem;">
                        <span style="font-size:0.875rem;">Utilisateurs actifs</span>
                        <x-filament::badge color="gray">{{ $stats['users'] }}</x-filament::badge>
                    </div>
                </div>
            </x-filament::section>

        </div>

        {{-- Système --}}
        <x-filament::section
            heading="Système"
            description="Informations techniques"
        >
            <div class="space-y-2" style="font-size:0.875rem;">
                <div style="display:flex; justify-content:space-between; padding-bottom:0.25rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                    <span>Version</span>
                    <span style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">Nexus ERP v5.1.1</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding-bottom:0.25rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                    <span>Stack</span>
                    <span style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">Laravel 13 + Filament 4 + Shield</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding-bottom:0.25rem; border-bottom:1px solid rgba(120,120,120,0.25);">
                    <span>Base de données</span>
                    <span style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">MariaDB / MySQL</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Devise</span>
                    <span>DZD (Algérien)</span>
                </div>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
