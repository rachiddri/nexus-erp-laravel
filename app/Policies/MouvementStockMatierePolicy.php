<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MouvementStockMatiere;
use Illuminate\Auth\Access\HandlesAuthorization;

class MouvementStockMatierePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MouvementStockMatiere');
    }

    public function view(AuthUser $authUser, MouvementStockMatiere $mouvementStockMatiere): bool
    {
        return $authUser->can('View:MouvementStockMatiere');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MouvementStockMatiere');
    }

    public function update(AuthUser $authUser, MouvementStockMatiere $mouvementStockMatiere): bool
    {
        return $authUser->can('Update:MouvementStockMatiere');
    }

    public function delete(AuthUser $authUser, MouvementStockMatiere $mouvementStockMatiere): bool
    {
        return $authUser->can('Delete:MouvementStockMatiere');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MouvementStockMatiere');
    }

    public function restore(AuthUser $authUser, MouvementStockMatiere $mouvementStockMatiere): bool
    {
        return $authUser->can('Restore:MouvementStockMatiere');
    }

    public function forceDelete(AuthUser $authUser, MouvementStockMatiere $mouvementStockMatiere): bool
    {
        return $authUser->can('ForceDelete:MouvementStockMatiere');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MouvementStockMatiere');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MouvementStockMatiere');
    }

    public function replicate(AuthUser $authUser, MouvementStockMatiere $mouvementStockMatiere): bool
    {
        return $authUser->can('Replicate:MouvementStockMatiere');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MouvementStockMatiere');
    }

}