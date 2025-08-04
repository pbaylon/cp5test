<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Breed Entity
 *
 * @property int $id
 * @property string $name
 * @property int|null $is_active
 * @property int|null $is_cat
 * @property int|null $created_by
 * @property \Cake\I18n\DateTime $created_on
 * @property \Cake\I18n\DateTime $modified_on
 * @property bool|null $is_deleted
 * @property int|null $deleted_by
 * @property \Cake\I18n\DateTime|null $deleted_on
 *
 * @property \App\Model\Entity\Pet[] $pets
 */
class Breed extends Entity
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
        'name' => true,
        'is_active' => true,
        'is_cat' => true,
        'created_by' => true,
        'created_on' => true,
        'modified_on' => true,
        'is_deleted' => true,
        'deleted_by' => true,
        'deleted_on' => true,
        'pets' => true,
    ];
}
