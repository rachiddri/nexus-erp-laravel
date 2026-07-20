<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BonCommandeLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class BonCommandeLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BonCommandeLigne');
    }

    public function view(AuthUser $authUser, BonCommandeLigne $bonCommandeLigne): bool
    {
        return $authUser->can('View:BonCommandeLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BonCommandeLigne');
    }

    public function update(AuthUser $authUser, BonCommandeLigne $bonCommandeLigne): bool
    {
        return $authUser->can('Update:BonCommandeLigne');
    }

    public function delete(AuthUser $authUser, BonCommandeLigne $bonCommandeLigne): bool
    {
        return $authUser->can('Delete:BonCommandeLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BonCommandeLigne');
    }

    public function restore(AuthUser $authUser, BonCommandeLigne $bonCommandeLigne): bool
    {
        return $authUser->can('Restore:BonCommandeLigne');
    }

    public function forceDelete(AuthUser $authUser, BonCommandeLigne $bonCommandeLigne): bool
    {
        return $authUser->can('ForceDelete:BonCommandeLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BonCommandeLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BonCommandeLigne');
    }

    public function replicate(AuthUser $authUser, BonCommandeLigne $bonCommandeLigne): bool
    {
        return $authUser->can('Replicate:BonCommandeLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BonCommandeLigne');
    }

}