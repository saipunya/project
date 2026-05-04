<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Models\User;

final class ProfileController
{
    public function index(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $sessionUser = Auth::user();
        $userModel = new User();
        $userData = $userModel->findById((int) $sessionUser['id']);

        $accessibleMenu = $this->buildAccessibleMenu($sessionUser['role']);

        Response::view('profile/index', [
            'title' => 'โปรไฟล์ผู้ใช้',
            'user' => $userData,
            'menuItems' => $accessibleMenu,
        ]);
    }

    private function buildAccessibleMenu(string $role): array
    {
        $menuItems = [
            [
                'group' => 'ข้อมูลทั่วไป',
                'items' => [
                    ['label' => 'แดชบอร์ด', 'path' => '/dashboard'],
                    ['label' => 'สรุปโครงการ', 'path' => '/summary'],
                ],
            ],
        ];

        if (in_array($role, ['ADMIN', 'STAFF'], true)) {
            $menuItems[] = [
                'group' => 'เมนูการจัดการ',
                'items' => [
                    ['label' => 'โครงการ', 'path' => '/projects'],
                    ['label' => 'KPIs', 'path' => '/kpis'],
                    ['label' => 'กิจกรรม', 'path' => '/activities'],
                    ['label' => 'รายงานประจำเดือน', 'path' => '/reports/create'],
                    ['label' => 'รายงานงบประมาณ', 'path' => '/budget-reports/create'],
                ],
            ];
        }

        if ($role === 'ADMIN') {
            $menuItems[] = [
                'group' => 'เมนูสำหรับผู้ดูแลระบบ',
                'items' => [
                    ['label' => 'สร้างแผน', 'path' => '/plans/create'],
                    ['label' => 'ลงทะเบียนผู้ใช้', 'path' => '/register'],
                ],
            ];
        }

        return $menuItems;
    }
}
