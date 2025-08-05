<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PetRecordsFixture
 */
class PetRecordsFixture extends TestFixture
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
                'pet_id' => 1,
                'type' => 1,
                'date' => '2025-08-05',
                'remarks' => 'Lorem ipsum dolor sit amet',
                'vet_id' => 1,
                'details' => 'Lorem ipsum dolor sit amet',
                'created_by' => 1,
                'created_on' => '2025-08-05 06:28:57',
                'modified_on' => '2025-08-05 06:28:57',
                'is_deleted' => 1,
                'deleted_by' => 1,
                'deleted_on' => '2025-08-05 06:28:57',
            ],
        ];
        parent::init();
    }
}
