<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FactureLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class FactureLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FactureLigne');
    }

    public function view(AuthUser $authUser, FactureLigne $factureLigne): bool
    {
        return $authUser->can('View:FactureLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FactureLigne');
    }

    public function update(AuthUser $authUser, FactureLigne $factureLigne): bool
    {
        return $authUser->can('Update:FactureLigne');
    }

    public function delete(AuthUser $authUser, FactureLigne $factureLigne): bool
    {
        return $authUser->can('Delete:FactureLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FactureLigne');
    }

    public function restore(AuthUser $authUser, FactureLigne $factureLigne): bool
    {
        return $authUser->can('Restore:FactureLigne');
    }

    public function forceDelete(AuthUser $authUser, FactureLigne $factureLigne): bool
    {
        return $authUser->can('ForceDelete:FactureLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FactureLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FactureLigne');
    }

    public function replicate(AuthUser $authUser, FactureLigne $factureLigne): bool
    {
        return $authUser->can('Replicate:FactureLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FactureLigne');
    }

}