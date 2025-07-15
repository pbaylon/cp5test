<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $fname
 * @property string|null $lname
 * @property string|null $username
 * @property string|null $password
 * @property int|null $role
 * @property int|null $has_pic
 * @property int|null $is_active
 * @property int|null $created_by
 * @property \Cake\I18n\Date|null $created_on
 */
class User extends Entity
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
        'fname' => true,
        'lname' => true,
        'username' => true,
        'password' => true,
        'role' => true,
        'has_pic' => true,
        'is_active' => true,
        'created_by' => true,
        'created_on' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }

    // test
}
