<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProduitPhysique;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProduitPhysiquePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProduitPhysique');
    }

    public function view(AuthUser $authUser, ProduitPhysique $produitPhysique): bool
    {
        return $authUser->can('View:ProduitPhysique');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProduitPhysique');
    }

    public function update(AuthUser $authUser, ProduitPhysique $produitPhysique): bool
    {
        return $authUser->can('Update:ProduitPhysique');
    }

    public function delete(AuthUser $authUser, ProduitPhysique $produitPhysique): bool
    {
        return $authUser->can('Delete:ProduitPhysique');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProduitPhysique');
    }

    public function restore(AuthUser $authUser, ProduitPhysique $produitPhysique): bool
    {
        return $authUser->can('Restore:ProduitPhysique');
    }

    public function forceDelete(AuthUser $authUser, ProduitPhysique $produitPhysique): bool
    {
        return $authUser->can('ForceDelete:ProduitPhysique');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProduitPhysique');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProduitPhysique');
    }

    public function replicate(AuthUser $authUser, ProduitPhysique $produitPhysique): bool
    {
        return $authUser->can('Replicate:ProduitPhysique');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProduitPhysique');
    }

}