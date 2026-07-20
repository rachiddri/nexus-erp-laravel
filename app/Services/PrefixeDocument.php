<?php

namespace App\Services;

/**
 * Préfixes de numérotation des documents (§6.1).
 * Autonome pour être autoloadable (extrait de SequenceService).
 */
enum PrefixeDocument: string
{
    case OP = 'op';      // Ordre de production
    case LOT = 'lot';    // Lot
    case BC = 'bc';      // Bon de commande
    case BL = 'ds';      // Bon de livraison
    case BE = 'be';      // Bon d'enlèvement
    case FAC = 'facture';
    case AV = 'avoir';
    case PAIE = 'paie';
    case BT = 'bt';
    case INV = 'inv';
    case RET = 'ret';
    case TRF = 'trf';
    case PHY = 'phy';

    public function prefixe(): string
    {
        return $this->value;
    }
}
