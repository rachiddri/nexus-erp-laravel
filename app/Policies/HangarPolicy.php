<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Hangar;
use Illuminate\Auth\Access\HandlesAuthorization;

class HangarPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Hangar');
    }

    public function view(AuthUser $authUser, Hangar $hangar): bool
    {
        return $authUser->can('View:Hangar');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Hangar');
    }

    public function update(AuthUser $authUser, Hangar $hangar): bool
    {
        return $authUser->can('Update:Hangar');
    }

    public function delete(AuthUser $authUser, Hangar $hangar): bool
    {
        return $authUser->can('Delete:Hangar');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Hangar');
    }

    public function restore(AuthUser $authUser, Hangar $hangar): bool
    {
        return $authUser->can('Restore:Hangar');
    }

    public function forceDelete(AuthUser $authUser, Hangar $hangar): bool
    {
        return $authUser->can('ForceDelete:Hangar');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Hangar');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Hangar');
    }

    public function replicate(AuthUser $authUser, Hangar $hangar): bool
    {
        return $authUser->can('Replicate:Hangar');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Hangar');
    }

}