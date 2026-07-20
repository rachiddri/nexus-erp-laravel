<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InventaireLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventaireLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InventaireLigne');
    }

    public function view(AuthUser $authUser, InventaireLigne $inventaireLigne): bool
    {
        return $authUser->can('View:InventaireLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InventaireLigne');
    }

    public function update(AuthUser $authUser, InventaireLigne $inventaireLigne): bool
    {
        return $authUser->can('Update:InventaireLigne');
    }

    public function delete(AuthUser $authUser, InventaireLigne $inventaireLigne): bool
    {
        return $authUser->can('Delete:InventaireLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InventaireLigne');
    }

    public function restore(AuthUser $authUser, InventaireLigne $inventaireLigne): bool
    {
        return $authUser->can('Restore:InventaireLigne');
    }

    public function forceDelete(AuthUser $authUser, InventaireLigne $inventaireLigne): bool
    {
        return $authUser->can('ForceDelete:InventaireLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InventaireLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InventaireLigne');
    }

    public function replicate(AuthUser $authUser, InventaireLigne $inventaireLigne): bool
    {
        return $authUser->can('Replicate:InventaireLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InventaireLigne');
    }

}