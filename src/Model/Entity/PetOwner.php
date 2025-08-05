<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PetOwner Entity
 *
 * @property int $id
 * @property int $pet_id
 * @property int $client_id
 * @property bool|null $is_active
 * @property int|null $created_by
 * @property \Cake\I18n\DateTime $created_on
 * @property \Cake\I18n\DateTime $modified_on
 * @property int|null $deleted_by
 * @property \Cake\I18n\DateTime|null $deleted_on
 * @property bool|null $is_deleted
 *
 * @property \App\Model\Entity\Pet $pet
 * @property \App\Model\Entity\Client $client
 */
class PetOwner extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'pet_id' => true,
        'client_id' => true,
        'is_active' => true,
        'created_by' => true,
        'created_on' => true,
        'modified_on' => true,
        'deleted_by' => true,
        'deleted_on' => true,
        'is_deleted' => true,
        'pet' => true,
        'client' => true,
    ];
}
