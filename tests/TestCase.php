<?php

use Zaichaopan\Taggable\TaggableServiceProvider;

class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TaggableServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();

        Eloquent::unguard();
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations'),
        ]);
    }

    public function tearDown()
    {
        \Schema::drop('activity');
        \Schema::drop('tags');
        \Schema::drop('taggables');

        parent::tearDown();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        \Schema::create('activities', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });
    }
}
