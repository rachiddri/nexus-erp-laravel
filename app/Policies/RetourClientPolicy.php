<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RetourClient;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetourClientPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RetourClient');
    }

    public function view(AuthUser $authUser, RetourClient $retourClient): bool
    {
        return $authUser->can('View:RetourClient');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RetourClient');
    }

    public function update(AuthUser $authUser, RetourClient $retourClient): bool
    {
        return $authUser->can('Update:RetourClient');
    }

    public function delete(AuthUser $authUser, RetourClient $retourClient): bool
    {
        return $authUser->can('Delete:RetourClient');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RetourClient');
    }

    public function restore(AuthUser $authUser, RetourClient $retourClient): bool
    {
        return $authUser->can('Restore:RetourClient');
    }

    public function forceDelete(AuthUser $authUser, RetourClient $retourClient): bool
    {
        return $authUser->can('ForceDelete:RetourClient');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RetourClient');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RetourClient');
    }

    public function replicate(AuthUser $authUser, RetourClient $retourClient): bool
    {
        return $authUser->can('Replicate:RetourClient');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RetourClient');
    }

}