<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MatierePremiere;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatierePremierePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MatierePremiere');
    }

    public function view(AuthUser $authUser, MatierePremiere $matierePremiere): bool
    {
        return $authUser->can('View:MatierePremiere');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MatierePremiere');
    }

    public function update(AuthUser $authUser, MatierePremiere $matierePremiere): bool
    {
        return $authUser->can('Update:MatierePremiere');
    }

    public function delete(AuthUser $authUser, MatierePremiere $matierePremiere): bool
    {
        return $authUser->can('Delete:MatierePremiere');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MatierePremiere');
    }

    public function restore(AuthUser $authUser, MatierePremiere $matierePremiere): bool
    {
        return $authUser->can('Restore:MatierePremiere');
    }

    public function forceDelete(AuthUser $authUser, MatierePremiere $matierePremiere): bool
    {
        return $authUser->can('ForceDelete:MatierePremiere');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MatierePremiere');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MatierePremiere');
    }

    public function replicate(AuthUser $authUser, MatierePremiere $matierePremiere): bool
    {
        return $authUser->can('Replicate:MatierePremiere');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MatierePremiere');
    }

}