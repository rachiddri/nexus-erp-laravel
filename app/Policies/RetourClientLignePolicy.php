<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RetourClientLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetourClientLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RetourClientLigne');
    }

    public function view(AuthUser $authUser, RetourClientLigne $retourClientLigne): bool
    {
        return $authUser->can('View:RetourClientLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RetourClientLigne');
    }

    public function update(AuthUser $authUser, RetourClientLigne $retourClientLigne): bool
    {
        return $authUser->can('Update:RetourClientLigne');
    }

    public function delete(AuthUser $authUser, RetourClientLigne $retourClientLigne): bool
    {
        return $authUser->can('Delete:RetourClientLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RetourClientLigne');
    }

    public function restore(AuthUser $authUser, RetourClientLigne $retourClientLigne): bool
    {
        return $authUser->can('Restore:RetourClientLigne');
    }

    public function forceDelete(AuthUser $authUser, RetourClientLigne $retourClientLigne): bool
    {
        return $authUser->can('ForceDelete:RetourClientLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RetourClientLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RetourClientLigne');
    }

    public function replicate(AuthUser $authUser, RetourClientLigne $retourClientLigne): bool
    {
        return $authUser->can('Replicate:RetourClientLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RetourClientLigne');
    }

}