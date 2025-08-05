<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pet Entity
 *
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $species
 * @property string|null $gender
 * @property \Cake\I18n\Date $dob
 * @property int $breed_id
 * @property string|null $gents
 * @property int|null $status
 * @property int|null $created_by
 * @property \Cake\I18n\DateTime $created_on
 * @property \Cake\I18n\DateTime $modified_on
 * @property bool|null $is_deleted
 * @property int|null $deleted_by
 * @property \Cake\I18n\DateTime|null $deleted_on
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Breed $breed
 * @property \App\Model\Entity\PetOwner[] $pet_owners
 */
class Pet extends Entity
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
        'client_id' => true,
        'name' => true,
        'species' => true,
        'gender' => true,
        'dob' => true,
        'breed_id' => true,
        'gents' => true,
        'status' => true,
        'created_by' => true,
        'created_on' => true,
        'modified_on' => true,
        'is_deleted' => true,
        'deleted_by' => true,
        'deleted_on' => true,
        'client' => true,
        'breed' => true,
        'pet_owners' => true,
    ];
}
