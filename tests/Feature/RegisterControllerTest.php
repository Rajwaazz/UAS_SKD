<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class RegisterControllerTest extends TestCase
{
    // Menggunakan trait RefreshDatabase untuk mereset database ke kondisi awal setelah setiap pengujian
    use RefreshDatabase;

    // Fungsi untuk menguji apakah halaman registrasi ditampilkan dengan benar
    public function test_register_displays_registration_page()
    {
        // Mengirim request GET ke URL '/admin/register' untuk mengakses halaman registrasi
        $response = $this->get('/admin/register');
        
        // Memastikan bahwa status responsnya adalah 200, yang berarti halaman berhasil dimuat
        $response->assertStatus(200);
        
        // Memastikan bahwa view yang ditampilkan adalah 'admin.register'
        $response->assertViewIs('admin.register');
    }

    // Fungsi untuk menguji pembuatan admin baru melalui registrasi
    public function test_register_creates_new_admin()
    {
        // Mengirim request POST ke URL '/admin/register' dengan data admin baru
        $response = $this->post('/admin/register', [
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // Memastikan bahwa responsnya mengarahkan ke route 'admin.dashboard' setelah registrasi berhasil
        $response->assertRedirect(route('admin.dashboard'));

        // Memastikan bahwa data admin baru telah ditambahkan ke database
        $this->assertDatabaseHas('admin', [
            'name' => 'Test Admin',
            'email' => 'test@example.com'
        ]);
    }

    // Fungsi untuk menguji validasi email agar unik dalam proses registrasi
    public function test_register_validates_email_uniqueness()
    {
        // Membuat data admin dengan email tertentu terlebih dahulu menggunakan factory
        Admin::factory()->create([
            'email' => 'test@example.com'
        ]);

        // Mengirim request POST ke URL '/admin/register' dengan email yang sudah ada di database
        $response = $this->post('/admin/register', [
            'name' => 'Test Admin',
            'email' => 'test@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // Memastikan bahwa ada pesan error di session terkait validasi email unik
        $response->assertSessionHasErrors('email');
    }
}