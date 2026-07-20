<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StockMatierePremiere;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockMatierePremierePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StockMatierePremiere');
    }

    public function view(AuthUser $authUser, StockMatierePremiere $stockMatierePremiere): bool
    {
        return $authUser->can('View:StockMatierePremiere');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StockMatierePremiere');
    }

    public function update(AuthUser $authUser, StockMatierePremiere $stockMatierePremiere): bool
    {
        return $authUser->can('Update:StockMatierePremiere');
    }

    public function delete(AuthUser $authUser, StockMatierePremiere $stockMatierePremiere): bool
    {
        return $authUser->can('Delete:StockMatierePremiere');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:StockMatierePremiere');
    }

    public function restore(AuthUser $authUser, StockMatierePremiere $stockMatierePremiere): bool
    {
        return $authUser->can('Restore:StockMatierePremiere');
    }

    public function forceDelete(AuthUser $authUser, StockMatierePremiere $stockMatierePremiere): bool
    {
        return $authUser->can('ForceDelete:StockMatierePremiere');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StockMatierePremiere');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StockMatierePremiere');
    }

    public function replicate(AuthUser $authUser, StockMatierePremiere $stockMatierePremiere): bool
    {
        return $authUser->can('Replicate:StockMatierePremiere');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StockMatierePremiere');
    }

}