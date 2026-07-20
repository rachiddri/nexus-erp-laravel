<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BonCommande;
use Illuminate\Auth\Access\HandlesAuthorization;

class BonCommandePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BonCommande');
    }

    public function view(AuthUser $authUser, BonCommande $bonCommande): bool
    {
        return $authUser->can('View:BonCommande');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BonCommande');
    }

    public function update(AuthUser $authUser, BonCommande $bonCommande): bool
    {
        return $authUser->can('Update:BonCommande');
    }

    public function delete(AuthUser $authUser, BonCommande $bonCommande): bool
    {
        return $authUser->can('Delete:BonCommande');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BonCommande');
    }

    public function restore(AuthUser $authUser, BonCommande $bonCommande): bool
    {
        return $authUser->can('Restore:BonCommande');
    }

    public function forceDelete(AuthUser $authUser, BonCommande $bonCommande): bool
    {
        return $authUser->can('ForceDelete:BonCommande');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BonCommande');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BonCommande');
    }

    public function replicate(AuthUser $authUser, BonCommande $bonCommande): bool
    {
        return $authUser->can('Replicate:BonCommande');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BonCommande');
    }

}