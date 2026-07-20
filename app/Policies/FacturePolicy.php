<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Facture;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacturePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Facture');
    }

    public function view(AuthUser $authUser, Facture $facture): bool
    {
        return $authUser->can('View:Facture');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Facture');
    }

    public function update(AuthUser $authUser, Facture $facture): bool
    {
        return $authUser->can('Update:Facture');
    }

    public function delete(AuthUser $authUser, Facture $facture): bool
    {
        return $authUser->can('Delete:Facture');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Facture');
    }

    public function restore(AuthUser $authUser, Facture $facture): bool
    {
        return $authUser->can('Restore:Facture');
    }

    public function forceDelete(AuthUser $authUser, Facture $facture): bool
    {
        return $authUser->can('ForceDelete:Facture');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Facture');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Facture');
    }

    public function replicate(AuthUser $authUser, Facture $facture): bool
    {
        return $authUser->can('Replicate:Facture');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Facture');
    }

}