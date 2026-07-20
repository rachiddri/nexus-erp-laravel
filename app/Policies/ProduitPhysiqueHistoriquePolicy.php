<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProduitPhysiqueHistorique;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProduitPhysiqueHistoriquePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProduitPhysiqueHistorique');
    }

    public function view(AuthUser $authUser, ProduitPhysiqueHistorique $produitPhysiqueHistorique): bool
    {
        return $authUser->can('View:ProduitPhysiqueHistorique');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProduitPhysiqueHistorique');
    }

    public function update(AuthUser $authUser, ProduitPhysiqueHistorique $produitPhysiqueHistorique): bool
    {
        return $authUser->can('Update:ProduitPhysiqueHistorique');
    }

    public function delete(AuthUser $authUser, ProduitPhysiqueHistorique $produitPhysiqueHistorique): bool
    {
        return $authUser->can('Delete:ProduitPhysiqueHistorique');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProduitPhysiqueHistorique');
    }

    public function restore(AuthUser $authUser, ProduitPhysiqueHistorique $produitPhysiqueHistorique): bool
    {
        return $authUser->can('Restore:ProduitPhysiqueHistorique');
    }

    public function forceDelete(AuthUser $authUser, ProduitPhysiqueHistorique $produitPhysiqueHistorique): bool
    {
        return $authUser->can('ForceDelete:ProduitPhysiqueHistorique');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProduitPhysiqueHistorique');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProduitPhysiqueHistorique');
    }

    public function replicate(AuthUser $authUser, ProduitPhysiqueHistorique $produitPhysiqueHistorique): bool
    {
        return $authUser->can('Replicate:ProduitPhysiqueHistorique');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProduitPhysiqueHistorique');
    }

}