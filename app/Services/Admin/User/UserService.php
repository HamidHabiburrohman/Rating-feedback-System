<?php

namespace App\Services\Admin\User;

use App\Services\Admin\Shared\BaseAdminService;

class UserService extends BaseAdminService
{
    public function getAllUsers(array $filters = [])
    {
        // TODO: Logika mengambil daftar user/admin
    }

    public function createUser(array $data)
    {
        // TODO: Logika pembuatan akun user/admin
    }

    public function updateUser(string $id, array $data)
    {
        // TODO: Logika update akun user/admin
    }

    public function deleteUser(string $id)
    {
        // TODO: Logika hapus akun user/admin
    }

    public function toggleUserStatus(string $id)
    {
        // TODO: Logika toggle status user/admin
    }
}