<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClientsFixture
 */
class ClientsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'fname' => 'Lorem ipsum dolor sit amet',
                'mname' => 'Lorem ipsum dolor sit amet',
                'lname' => 'Lorem ipsum dolor sit amet',
                'phone_number' => 1,
                'phone_number2' => 1,
                'is_new' => 1,
                'is_member' => 1,
                'is_vip' => 1,
                'is_active' => 1,
                'cnd_pts' => 1,
                'created_by' => 1,
                'created_on' => '2025-07-25 07:08:33',
                'modified_on' => '2025-07-25 07:08:33',
                'deleted_on' => '2025-07-25 07:08:33',
            ],
        ];
        parent::init();
    }
}
