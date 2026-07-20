<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum MethodeScan: string implements HasLabel
{
    case CameraMobile = 'camera_mobile';
    case SaisieManuelleMobile = 'saisie_manuelle_mobile';
    case CameraWeb = 'camera_web';
    case SaisieManuelleWeb = 'saisie_manuelle_web';
    case Api = 'api';

    public function getLabel(): string
    {
        return match ($this) {
            self::CameraMobile => 'Caméra mobile',
            self::SaisieManuelleMobile => 'Saisie manuelle mobile',
            self::CameraWeb => 'Caméra web',
            self::SaisieManuelleWeb => 'Saisie manuelle web',
            self::Api => 'API',
        };
    }

    /** @return array<string,string> */
    public static function options(): array
    {
        return Collection::make(self::cases())
            ->mapWithKeys(fn (self $c) => [$c->value => $c->getLabel()])
            ->all();
    }
}
