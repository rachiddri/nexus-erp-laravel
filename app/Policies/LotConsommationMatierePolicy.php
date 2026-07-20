<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LotConsommationMatiere;
use Illuminate\Auth\Access\HandlesAuthorization;

class LotConsommationMatierePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LotConsommationMatiere');
    }

    public function view(AuthUser $authUser, LotConsommationMatiere $lotConsommationMatiere): bool
    {
        return $authUser->can('View:LotConsommationMatiere');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LotConsommationMatiere');
    }

    public function update(AuthUser $authUser, LotConsommationMatiere $lotConsommationMatiere): bool
    {
        return $authUser->can('Update:LotConsommationMatiere');
    }

    public function delete(AuthUser $authUser, LotConsommationMatiere $lotConsommationMatiere): bool
    {
        return $authUser->can('Delete:LotConsommationMatiere');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LotConsommationMatiere');
    }

    public function restore(AuthUser $authUser, LotConsommationMatiere $lotConsommationMatiere): bool
    {
        return $authUser->can('Restore:LotConsommationMatiere');
    }

    public function forceDelete(AuthUser $authUser, LotConsommationMatiere $lotConsommationMatiere): bool
    {
        return $authUser->can('ForceDelete:LotConsommationMatiere');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LotConsommationMatiere');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LotConsommationMatiere');
    }

    public function replicate(AuthUser $authUser, LotConsommationMatiere $lotConsommationMatiere): bool
    {
        return $authUser->can('Replicate:LotConsommationMatiere');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LotConsommationMatiere');
    }

}