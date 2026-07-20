<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Sequence;
use Illuminate\Auth\Access\HandlesAuthorization;

class SequencePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Sequence');
    }

    public function view(AuthUser $authUser, Sequence $sequence): bool
    {
        return $authUser->can('View:Sequence');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Sequence');
    }

    public function update(AuthUser $authUser, Sequence $sequence): bool
    {
        return $authUser->can('Update:Sequence');
    }

    public function delete(AuthUser $authUser, Sequence $sequence): bool
    {
        return $authUser->can('Delete:Sequence');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Sequence');
    }

    public function restore(AuthUser $authUser, Sequence $sequence): bool
    {
        return $authUser->can('Restore:Sequence');
    }

    public function forceDelete(AuthUser $authUser, Sequence $sequence): bool
    {
        return $authUser->can('ForceDelete:Sequence');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Sequence');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Sequence');
    }

    public function replicate(AuthUser $authUser, Sequence $sequence): bool
    {
        return $authUser->can('Replicate:Sequence');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Sequence');
    }

}