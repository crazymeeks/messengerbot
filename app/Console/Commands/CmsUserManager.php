<?php

namespace App\Console\Commands;


use Exception;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AdminUser;

class CmsUserManager
{

    public function createUser(array $values)
    {
        $role = $this->createRole();
        $permission = $this->createPermission();

        $values['role_id'] = $role;
        $values['status'] = AdminUser::ACTIVE;
        $values['deleted'] = null;


        $admin = new AdminUser();

        $found = $admin->findOne([
            'email' => $values['email']
        ]);

        if (!$found) {

            $found = $admin->findOne([
                'username' => $values['username']
            ]);
            if (!$found) {
                $admin->insertOne($values);
                return true;
            }
        }

        
        throw new Exception("Cannot create user because a username or email already exists!");


    }

    protected function createRole()
    {
        $role = new Role();
        $role = $role->findOne(['name' => 'Superadmin']);

        if ($role) {
            return $role->_id;
        }


        $role = new Role();
        $role = $role->insertOne([
            'name' => 'Superadmin',
            'description' => 'Super admin of the site'
        ]);

        return $role->getInsertedId();


    }

    protected function createPermission()
    {
        $permission = new Permission();
        $permission = $permission->findOne(['value' => 'Administer cms']);

        if ($permission) {
            return $permission->_id;
        }

        $permission = new Permission();
        $permission = $permission->insertOne([
            'value' => 'Administer cms'
        ]);

        return $permission->getInsertedId();
    }
}