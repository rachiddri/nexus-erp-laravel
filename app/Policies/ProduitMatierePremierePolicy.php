<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProduitMatierePremiere;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProduitMatierePremierePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProduitMatierePremiere');
    }

    public function view(AuthUser $authUser, ProduitMatierePremiere $produitMatierePremiere): bool
    {
        return $authUser->can('View:ProduitMatierePremiere');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProduitMatierePremiere');
    }

    public function update(AuthUser $authUser, ProduitMatierePremiere $produitMatierePremiere): bool
    {
        return $authUser->can('Update:ProduitMatierePremiere');
    }

    public function delete(AuthUser $authUser, ProduitMatierePremiere $produitMatierePremiere): bool
    {
        return $authUser->can('Delete:ProduitMatierePremiere');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProduitMatierePremiere');
    }

    public function restore(AuthUser $authUser, ProduitMatierePremiere $produitMatierePremiere): bool
    {
        return $authUser->can('Restore:ProduitMatierePremiere');
    }

    public function forceDelete(AuthUser $authUser, ProduitMatierePremiere $produitMatierePremiere): bool
    {
        return $authUser->can('ForceDelete:ProduitMatierePremiere');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProduitMatierePremiere');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProduitMatierePremiere');
    }

    public function replicate(AuthUser $authUser, ProduitMatierePremiere $produitMatierePremiere): bool
    {
        return $authUser->can('Replicate:ProduitMatierePremiere');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProduitMatierePremiere');
    }

}