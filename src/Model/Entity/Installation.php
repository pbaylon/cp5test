<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Installation Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $tin
 * @property string|null $series
 * @property string|null $address
 * @property string|null $printer_name
 * @property string|null $printer_port
 * @property int|null $created_by
 * @property \Cake\I18n\DateTime $created_on
 * @property \Cake\I18n\DateTime $modified_on
 * @property bool|null $is_deleted
 * @property int|null $deleted_by
 * @property \Cake\I18n\DateTime|null $deleted_on
 */
class Installation extends Entity
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
        'tin' => true,
        'series' => true,
        'address' => true,
        'printer_name' => true,
        'printer_port' => true,
        'created_by' => true,
        'created_on' => true,
        'modified_on' => true,
        'is_deleted' => true,
        'deleted_by' => true,
        'deleted_on' => true,
    ];
}
