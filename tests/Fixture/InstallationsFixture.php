<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InstallationsFixture
 */
class InstallationsFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'tin' => 'Lorem ipsum d',
                'series' => 'Lor',
                'address' => 'Lorem ipsum dolor sit amet',
                'printer_name' => 'Lorem ipsum dolor sit amet',
                'printer_port' => 'Lorem ipsum dolor sit amet',
                'created_by' => 1,
                'created_on' => '2025-08-04 03:07:41',
                'modified_on' => '2025-08-04 03:07:41',
                'is_deleted' => 1,
                'deleted_by' => 1,
                'deleted_on' => '2025-08-04 03:07:41',
            ],
        ];
        parent::init();
    }
}
