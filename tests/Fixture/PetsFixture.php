<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PetsFixture
 */
class PetsFixture extends TestFixture
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
                'client_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'species' => 'Lorem ipsum dolor sit amet',
                'gender' => 'Lorem ipsum dolor sit amet',
                'dob' => '2025-08-05',
                'breed_id' => 1,
                'gents' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'status' => 1,
                'created_by' => 1,
                'created_on' => '2025-08-05 04:33:30',
                'modified_on' => '2025-08-05 04:33:30',
                'is_deleted' => 1,
                'deleted_by' => 1,
                'deleted_on' => '2025-08-05 04:33:30',
            ],
        ];
        parent::init();
    }
}
