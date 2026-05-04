<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Models\User;

final class AuthController
{
    public function showRegister(): void
    {
        Response::view('auth/register', ['title' => 'Register']);
    }

    public function showLogin(): void
    {
        Response::view('auth/login', ['title' => 'Login']);
    }

    public function register(): void
    {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($fullName === '' || $username === '' || $password === '') {
            Response::view('auth/register', [
                'title' => 'Register',
                'error' => 'Full name, username, and password are required.',
            ]);
            return;
        }

        if ($password !== $confirmPassword) {
            Response::view('auth/register', [
                'title' => 'Register',
                'error' => 'Password and confirm password do not match.',
            ]);
            return;
        }

        if (strlen($password) < 8) {
            Response::view('auth/register', [
                'title' => 'Register',
                'error' => 'Password must be at least 8 characters.',
            ]);
            return;
        }

        $userModel = new User();

        if ($userModel->findByUsername($username)) {
            Response::view('auth/register', [
                'title' => 'Register',
                'error' => 'Username already exists.',
            ]);
            return;
        }

        $staffRoleId = $userModel->roleIdByName('STAFF');
        if ($staffRoleId === null) {
            Response::view('auth/register', [
                'title' => 'Register',
                'error' => 'STAFF role is not configured in database.',
            ]);
            return;
        }

        $userModel->create([
            'role_id' => $staffRoleId,
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'full_name' => $fullName,
            'is_active' => 1,
        ]);

        Response::view('auth/login', [
            'title' => 'Login',
            'success' => 'Registration successful. Please login.',
        ]);
    }

    public function login(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (!Auth::attempt($username, $password)) {
            Response::view('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid credentials',
            ]);
            return;
        }

        Response::redirect('/profile');
    }

    public function logout(): void
    {
        Auth::logout();
        Response::redirect('/login');
    }
}
