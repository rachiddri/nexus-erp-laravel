<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OrdreProductionLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrdreProductionLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OrdreProductionLigne');
    }

    public function view(AuthUser $authUser, OrdreProductionLigne $ordreProductionLigne): bool
    {
        return $authUser->can('View:OrdreProductionLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OrdreProductionLigne');
    }

    public function update(AuthUser $authUser, OrdreProductionLigne $ordreProductionLigne): bool
    {
        return $authUser->can('Update:OrdreProductionLigne');
    }

    public function delete(AuthUser $authUser, OrdreProductionLigne $ordreProductionLigne): bool
    {
        return $authUser->can('Delete:OrdreProductionLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:OrdreProductionLigne');
    }

    public function restore(AuthUser $authUser, OrdreProductionLigne $ordreProductionLigne): bool
    {
        return $authUser->can('Restore:OrdreProductionLigne');
    }

    public function forceDelete(AuthUser $authUser, OrdreProductionLigne $ordreProductionLigne): bool
    {
        return $authUser->can('ForceDelete:OrdreProductionLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OrdreProductionLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OrdreProductionLigne');
    }

    public function replicate(AuthUser $authUser, OrdreProductionLigne $ordreProductionLigne): bool
    {
        return $authUser->can('Replicate:OrdreProductionLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OrdreProductionLigne');
    }

}