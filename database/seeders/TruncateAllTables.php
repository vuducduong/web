<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateAllTables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach ($this->getTargetTableNames() as $tableName) {
            DB:: table($tableName)->truncate();
        }

    }


    private function getTargetTableNames(): array
    {
        $excludes = ['migrations'];
        return array_diff($this->getAllTableNames(), $excludes);
    }

    /**
     * @return array
     */
    private function getAllTableNames(): array
    {
        return DB:: connection()->getDoctrineSchemaManager()->listTableNames();
    }
}
