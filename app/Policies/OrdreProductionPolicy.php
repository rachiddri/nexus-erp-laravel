<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OrdreProduction;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrdreProductionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OrdreProduction');
    }

    public function view(AuthUser $authUser, OrdreProduction $ordreProduction): bool
    {
        return $authUser->can('View:OrdreProduction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OrdreProduction');
    }

    public function update(AuthUser $authUser, OrdreProduction $ordreProduction): bool
    {
        return $authUser->can('Update:OrdreProduction');
    }

    public function delete(AuthUser $authUser, OrdreProduction $ordreProduction): bool
    {
        return $authUser->can('Delete:OrdreProduction');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:OrdreProduction');
    }

    public function restore(AuthUser $authUser, OrdreProduction $ordreProduction): bool
    {
        return $authUser->can('Restore:OrdreProduction');
    }

    public function forceDelete(AuthUser $authUser, OrdreProduction $ordreProduction): bool
    {
        return $authUser->can('ForceDelete:OrdreProduction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OrdreProduction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OrdreProduction');
    }

    public function replicate(AuthUser $authUser, OrdreProduction $ordreProduction): bool
    {
        return $authUser->can('Replicate:OrdreProduction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OrdreProduction');
    }

}