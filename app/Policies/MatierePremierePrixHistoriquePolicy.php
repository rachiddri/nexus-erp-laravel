<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MatierePremierePrixHistorique;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatierePremierePrixHistoriquePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MatierePremierePrixHistorique');
    }

    public function view(AuthUser $authUser, MatierePremierePrixHistorique $matierePremierePrixHistorique): bool
    {
        return $authUser->can('View:MatierePremierePrixHistorique');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MatierePremierePrixHistorique');
    }

    public function update(AuthUser $authUser, MatierePremierePrixHistorique $matierePremierePrixHistorique): bool
    {
        return $authUser->can('Update:MatierePremierePrixHistorique');
    }

    public function delete(AuthUser $authUser, MatierePremierePrixHistorique $matierePremierePrixHistorique): bool
    {
        return $authUser->can('Delete:MatierePremierePrixHistorique');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MatierePremierePrixHistorique');
    }

    public function restore(AuthUser $authUser, MatierePremierePrixHistorique $matierePremierePrixHistorique): bool
    {
        return $authUser->can('Restore:MatierePremierePrixHistorique');
    }

    public function forceDelete(AuthUser $authUser, MatierePremierePrixHistorique $matierePremierePrixHistorique): bool
    {
        return $authUser->can('ForceDelete:MatierePremierePrixHistorique');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MatierePremierePrixHistorique');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MatierePremierePrixHistorique');
    }

    public function replicate(AuthUser $authUser, MatierePremierePrixHistorique $matierePremierePrixHistorique): bool
    {
        return $authUser->can('Replicate:MatierePremierePrixHistorique');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MatierePremierePrixHistorique');
    }

}