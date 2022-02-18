<?php

namespace Tests;

use HaydenPierce\ClassFinder\ClassFinder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected $app;

    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        
        
        $this->withoutMiddleware(
            \App\Http\Middleware\Backend\CheckIfAuthenticated::class
        );
        $this->dbSeeder();
        
        // dd(bcrypt('NW@dmin'));
    }

    protected function dbSeeder()
    {
        $role = new \App\Models\Role();
        $roleInsertResult = $role->insertOne([
            'name' => 'Superadmin',
            'description' => 'Super admin of the site'
        ]);

        $permission = new \App\Models\Permission();
        $permInsertResult = $permission->insertOne([
            'value' => 'Administer site'
        ]);

        $adminUser = new \App\Models\AdminUser();
        $admInsertResult = $adminUser->insertOne([
            'role_id' => $roleInsertResult->getInsertedId(),
            'firstname' => 'Jeff',
            'lastname' => 'Claud',
            'email' => 'admin@nuworks.ph',
            'username' => 'admin',
            'password' => bcrypt(env('SUPERADMIN_PW')),
            'status' => \App\Models\AdminUser::ACTIVE,
            'deleted_at' => null,
        ]);
    }

    public function tearDown(): void
    {
        
        \Mockery::close();

        $this->resetDatabase();

        parent::tearDown();
    }

    protected function resetDatabase()
    {
        if (config('app.env') === 'testing') {
            $models = ClassFinder::getClassesInNamespace('App\Models');
            unset($models[0]);
            foreach($models as $model){
                $instance = $this->app->make($model);
                $instance->deleteMany();
            }
        }
    }
}
