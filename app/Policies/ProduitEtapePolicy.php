<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProduitEtape;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProduitEtapePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProduitEtape');
    }

    public function view(AuthUser $authUser, ProduitEtape $produitEtape): bool
    {
        return $authUser->can('View:ProduitEtape');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProduitEtape');
    }

    public function update(AuthUser $authUser, ProduitEtape $produitEtape): bool
    {
        return $authUser->can('Update:ProduitEtape');
    }

    public function delete(AuthUser $authUser, ProduitEtape $produitEtape): bool
    {
        return $authUser->can('Delete:ProduitEtape');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProduitEtape');
    }

    public function restore(AuthUser $authUser, ProduitEtape $produitEtape): bool
    {
        return $authUser->can('Restore:ProduitEtape');
    }

    public function forceDelete(AuthUser $authUser, ProduitEtape $produitEtape): bool
    {
        return $authUser->can('ForceDelete:ProduitEtape');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProduitEtape');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProduitEtape');
    }

    public function replicate(AuthUser $authUser, ProduitEtape $produitEtape): bool
    {
        return $authUser->can('Replicate:ProduitEtape');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProduitEtape');
    }

}