<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EtapeProduction;
use Illuminate\Auth\Access\HandlesAuthorization;

class EtapeProductionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EtapeProduction');
    }

    public function view(AuthUser $authUser, EtapeProduction $etapeProduction): bool
    {
        return $authUser->can('View:EtapeProduction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EtapeProduction');
    }

    public function update(AuthUser $authUser, EtapeProduction $etapeProduction): bool
    {
        return $authUser->can('Update:EtapeProduction');
    }

    public function delete(AuthUser $authUser, EtapeProduction $etapeProduction): bool
    {
        return $authUser->can('Delete:EtapeProduction');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:EtapeProduction');
    }

    public function restore(AuthUser $authUser, EtapeProduction $etapeProduction): bool
    {
        return $authUser->can('Restore:EtapeProduction');
    }

    public function forceDelete(AuthUser $authUser, EtapeProduction $etapeProduction): bool
    {
        return $authUser->can('ForceDelete:EtapeProduction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EtapeProduction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EtapeProduction');
    }

    public function replicate(AuthUser $authUser, EtapeProduction $etapeProduction): bool
    {
        return $authUser->can('Replicate:EtapeProduction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EtapeProduction');
    }

}