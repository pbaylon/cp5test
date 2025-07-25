<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property string $fname
 * @property string|null $mname
 * @property string $lname
 * @property int $phone_number
 * @property int|null $phone_number2
 * @property int|null $is_new
 * @property int|null $is_member
 * @property int|null $is_vip
 * @property bool $is_active
 * @property int|null $cnd_pts
 * @property int|null $created_by
 * @property \Cake\I18n\DateTime $created_on
 * @property \Cake\I18n\DateTime $modified_on
 * @property \Cake\I18n\DateTime|null $deleted_on
 */
class Client extends Entity
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
        'fname' => true,
        'mname' => true,
        'lname' => true,
        'phone_number' => true,
        'phone_number2' => true,
        'is_new' => true,
        'is_member' => true,
        'is_vip' => true,
        'is_active' => true,
        'cnd_pts' => true,
        'created_by' => true,
        'created_on' => true,
        'modified_on' => true,
        'deleted_on' => true,
    ];
}
