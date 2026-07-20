<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Lot;
use Illuminate\Auth\Access\HandlesAuthorization;

class LotPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Lot');
    }

    public function view(AuthUser $authUser, Lot $lot): bool
    {
        return $authUser->can('View:Lot');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Lot');
    }

    public function update(AuthUser $authUser, Lot $lot): bool
    {
        return $authUser->can('Update:Lot');
    }

    public function delete(AuthUser $authUser, Lot $lot): bool
    {
        return $authUser->can('Delete:Lot');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Lot');
    }

    public function restore(AuthUser $authUser, Lot $lot): bool
    {
        return $authUser->can('Restore:Lot');
    }

    public function forceDelete(AuthUser $authUser, Lot $lot): bool
    {
        return $authUser->can('ForceDelete:Lot');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Lot');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Lot');
    }

    public function replicate(AuthUser $authUser, Lot $lot): bool
    {
        return $authUser->can('Replicate:Lot');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Lot');
    }

}