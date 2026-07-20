<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LotProduit;
use Illuminate\Auth\Access\HandlesAuthorization;

class LotProduitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LotProduit');
    }

    public function view(AuthUser $authUser, LotProduit $lotProduit): bool
    {
        return $authUser->can('View:LotProduit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LotProduit');
    }

    public function update(AuthUser $authUser, LotProduit $lotProduit): bool
    {
        return $authUser->can('Update:LotProduit');
    }

    public function delete(AuthUser $authUser, LotProduit $lotProduit): bool
    {
        return $authUser->can('Delete:LotProduit');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LotProduit');
    }

    public function restore(AuthUser $authUser, LotProduit $lotProduit): bool
    {
        return $authUser->can('Restore:LotProduit');
    }

    public function forceDelete(AuthUser $authUser, LotProduit $lotProduit): bool
    {
        return $authUser->can('ForceDelete:LotProduit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LotProduit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LotProduit');
    }

    public function replicate(AuthUser $authUser, LotProduit $lotProduit): bool
    {
        return $authUser->can('Replicate:LotProduit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LotProduit');
    }

}