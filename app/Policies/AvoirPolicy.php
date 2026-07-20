<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Avoir;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvoirPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Avoir');
    }

    public function view(AuthUser $authUser, Avoir $avoir): bool
    {
        return $authUser->can('View:Avoir');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Avoir');
    }

    public function update(AuthUser $authUser, Avoir $avoir): bool
    {
        return $authUser->can('Update:Avoir');
    }

    public function delete(AuthUser $authUser, Avoir $avoir): bool
    {
        return $authUser->can('Delete:Avoir');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Avoir');
    }

    public function restore(AuthUser $authUser, Avoir $avoir): bool
    {
        return $authUser->can('Restore:Avoir');
    }

    public function forceDelete(AuthUser $authUser, Avoir $avoir): bool
    {
        return $authUser->can('ForceDelete:Avoir');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Avoir');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Avoir');
    }

    public function replicate(AuthUser $authUser, Avoir $avoir): bool
    {
        return $authUser->can('Replicate:Avoir');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Avoir');
    }

}