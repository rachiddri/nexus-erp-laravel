<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BonTransfertLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class BonTransfertLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BonTransfertLigne');
    }

    public function view(AuthUser $authUser, BonTransfertLigne $bonTransfertLigne): bool
    {
        return $authUser->can('View:BonTransfertLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BonTransfertLigne');
    }

    public function update(AuthUser $authUser, BonTransfertLigne $bonTransfertLigne): bool
    {
        return $authUser->can('Update:BonTransfertLigne');
    }

    public function delete(AuthUser $authUser, BonTransfertLigne $bonTransfertLigne): bool
    {
        return $authUser->can('Delete:BonTransfertLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BonTransfertLigne');
    }

    public function restore(AuthUser $authUser, BonTransfertLigne $bonTransfertLigne): bool
    {
        return $authUser->can('Restore:BonTransfertLigne');
    }

    public function forceDelete(AuthUser $authUser, BonTransfertLigne $bonTransfertLigne): bool
    {
        return $authUser->can('ForceDelete:BonTransfertLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BonTransfertLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BonTransfertLigne');
    }

    public function replicate(AuthUser $authUser, BonTransfertLigne $bonTransfertLigne): bool
    {
        return $authUser->can('Replicate:BonTransfertLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BonTransfertLigne');
    }

}