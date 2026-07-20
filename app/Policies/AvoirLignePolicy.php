<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AvoirLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvoirLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AvoirLigne');
    }

    public function view(AuthUser $authUser, AvoirLigne $avoirLigne): bool
    {
        return $authUser->can('View:AvoirLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AvoirLigne');
    }

    public function update(AuthUser $authUser, AvoirLigne $avoirLigne): bool
    {
        return $authUser->can('Update:AvoirLigne');
    }

    public function delete(AuthUser $authUser, AvoirLigne $avoirLigne): bool
    {
        return $authUser->can('Delete:AvoirLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AvoirLigne');
    }

    public function restore(AuthUser $authUser, AvoirLigne $avoirLigne): bool
    {
        return $authUser->can('Restore:AvoirLigne');
    }

    public function forceDelete(AuthUser $authUser, AvoirLigne $avoirLigne): bool
    {
        return $authUser->can('ForceDelete:AvoirLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AvoirLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AvoirLigne');
    }

    public function replicate(AuthUser $authUser, AvoirLigne $avoirLigne): bool
    {
        return $authUser->can('Replicate:AvoirLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AvoirLigne');
    }

}