<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BonTransfert;
use Illuminate\Auth\Access\HandlesAuthorization;

class BonTransfertPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BonTransfert');
    }

    public function view(AuthUser $authUser, BonTransfert $bonTransfert): bool
    {
        return $authUser->can('View:BonTransfert');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BonTransfert');
    }

    public function update(AuthUser $authUser, BonTransfert $bonTransfert): bool
    {
        return $authUser->can('Update:BonTransfert');
    }

    public function delete(AuthUser $authUser, BonTransfert $bonTransfert): bool
    {
        return $authUser->can('Delete:BonTransfert');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BonTransfert');
    }

    public function restore(AuthUser $authUser, BonTransfert $bonTransfert): bool
    {
        return $authUser->can('Restore:BonTransfert');
    }

    public function forceDelete(AuthUser $authUser, BonTransfert $bonTransfert): bool
    {
        return $authUser->can('ForceDelete:BonTransfert');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BonTransfert');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BonTransfert');
    }

    public function replicate(AuthUser $authUser, BonTransfert $bonTransfert): bool
    {
        return $authUser->can('Replicate:BonTransfert');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BonTransfert');
    }

}