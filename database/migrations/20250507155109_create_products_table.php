<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('products', ['id' => false, 'primary_key' => 'id'])
        ->addColumn('id', 'uuid', ['null' => false])
        ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('price', 'float')
            ->addColumn('category', 'string', ['limit' => 50])
            ->addColumn('attributes', 'json', ['null' => true])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->create();
    }
}
