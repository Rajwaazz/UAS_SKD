<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginControllerTest extends TestCase
{
    // Menggunakan trait RefreshDatabase untuk mereset database ke kondisi awal setelah setiap pengujian
    use RefreshDatabase;

    // Fungsi untuk menguji apakah halaman login ditampilkan dengan benar
    public function test_login_displays_login_page()
    {
        // Mengirim request GET ke URL '/admin/login' untuk mengakses halaman login
        $response = $this->get('/admin/login');
        
        // Memastikan status responsnya adalah 200, yang berarti halaman berhasil dimuat
        $response->assertStatus(200);
        
        // Memastikan bahwa view yang ditampilkan adalah 'admin.login'
        $response->assertViewIs('admin.login');
    }

    // Fungsi untuk menguji login dengan pengguna yang sudah terautentikasi
    public function test_login_authenticated_user()
    {
        // Membuat pengguna admin baru menggunakan factory dengan email dan password yang di-hash
        $admin = Admin::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        // Mengirim request POST ke URL '/admin/login' dengan kredensial yang benar
        $response = $this->post('/admin/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Memastikan responsnya mengarahkan ke route 'admin.dashboard' setelah login berhasil
        $response->assertRedirect(route('admin.dashboard'));

        // Memastikan bahwa pengguna telah terautentikasi sebagai admin
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    // Fungsi untuk menguji login dengan kredensial yang salah
    public function test_login_with_invalid_credentials()
    {
        // Mengirim request POST ke URL '/admin/login' dengan email dan password yang salah
        $response = $this->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        // Memastikan ada pesan error dalam sesi (karena kredensial tidak valid)
        $response->assertSessionHasErrors();

        // Memastikan bahwa tidak ada pengguna yang terautentikasi sebagai admin
        $this->assertGuest('admin');
    }
}