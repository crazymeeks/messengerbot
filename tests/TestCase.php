<?php

namespace Tests;

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

        $fbflow = new \App\Models\FacebookFlow();
        $fbflow->deleteMany();

        $chatter = new \App\Models\Chatter();
        $convo_reply = new \App\Models\ConversationReply();

        $chatter->deleteMany();
        $convo_reply->deleteMany();

        $role = new \App\Models\Role();
        $role->deleteMany();

        $permission = new \App\Models\Permission();
        $permission->deleteMany();

        $adminUser = new \App\Models\AdminUser();
        $adminUser->deleteMany();

        $catalog = new \App\Models\Catalog();
        $catalog->deleteMany();

        $orderCatalog = new \App\Models\OrderCatalog();
        $orderCatalog->deleteMany();

        $order = new \App\Models\Order();
        $order->deleteMany();
        
        $cart = new \App\Models\Cart();
        $cart->deleteMany();

        $shipping = new \App\Models\ShippingDetails();
        $shipping->deleteMany();

        $nextFlow = new \App\Models\NextFlow();
        $nextFlow->deleteMany();

        parent::tearDown();
    }
}
