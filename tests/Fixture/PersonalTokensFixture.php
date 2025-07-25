<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PersonalTokensFixture
 */
class PersonalTokensFixture extends TestFixture
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
                'user_id' => 1,
                'token' => 'Lorem ipsum dolor sit amet',
                'expires_at' => '2025-07-25 07:09:10',
            ],
        ];
        parent::init();
    }
}
