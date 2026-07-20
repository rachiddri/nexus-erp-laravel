<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InventaireProduitPhysique;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventaireProduitPhysiquePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InventaireProduitPhysique');
    }

    public function view(AuthUser $authUser, InventaireProduitPhysique $inventaireProduitPhysique): bool
    {
        return $authUser->can('View:InventaireProduitPhysique');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InventaireProduitPhysique');
    }

    public function update(AuthUser $authUser, InventaireProduitPhysique $inventaireProduitPhysique): bool
    {
        return $authUser->can('Update:InventaireProduitPhysique');
    }

    public function delete(AuthUser $authUser, InventaireProduitPhysique $inventaireProduitPhysique): bool
    {
        return $authUser->can('Delete:InventaireProduitPhysique');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InventaireProduitPhysique');
    }

    public function restore(AuthUser $authUser, InventaireProduitPhysique $inventaireProduitPhysique): bool
    {
        return $authUser->can('Restore:InventaireProduitPhysique');
    }

    public function forceDelete(AuthUser $authUser, InventaireProduitPhysique $inventaireProduitPhysique): bool
    {
        return $authUser->can('ForceDelete:InventaireProduitPhysique');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InventaireProduitPhysique');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InventaireProduitPhysique');
    }

    public function replicate(AuthUser $authUser, InventaireProduitPhysique $inventaireProduitPhysique): bool
    {
        return $authUser->can('Replicate:InventaireProduitPhysique');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InventaireProduitPhysique');
    }

}