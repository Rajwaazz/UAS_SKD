<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Film;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    // Pengujian Pengaturan Awal (SetUp)
    public function setUp(): void
    {
        parent::setUp();

        // Menggunakan penyimpanan palsu di disk 'public' untuk mencegah penyimpanan asli selama pengujian
        Storage::fake('public');

        // Membuat pengguna admin fiktif dengan email dan password untuk pengujian
        $this->admin = Admin::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Mengatur pengguna admin sebagai pengguna yang sedang aktif (logged in)
        $this->actingAs($this->admin, 'admin'); // Spesifik 'admin' guard
    }

    // Pengujian Menampilkan Daftar Film di Halaman Index
    public function test_index_displays_films()
    {
        // Membuat 3 film fiktif untuk pengujian
        Film::factory()->count(3)->create();

        // Mengakses rute untuk halaman daftar film admin
        $response = $this->get(route('admin.dashboard.film'));

        // Memastikan respons berhasil, halaman yang ditampilkan adalah admin.dashboard.film, dan data film tersedia
        $response->assertStatus(200)
            ->assertViewIs('admin.dashboard.film')
            ->assertViewHas('films');
    }

    // Pengujian Menambahkan Film Baru
    public function test_store_creates_new_film()
    {
        // Membuat file gambar fiktif untuk diupload
        $file = UploadedFile::fake()->image('movie.jpg');

        // Menyiapkan data film yang akan ditambahkan
        $filmData = [
            'poster' => $file, // Upload gambar sebagai poster
            'judul' => 'Test Movie', // Judul film
            'deskripsi' => 'Test Description', // Deskripsi film
            'genre' => ['Action', 'Drama'], // Genre film
            'tanggalRilis' => '2024-01-01', // Tanggal rilis film
            'duration' => 120 // Durasi film dalam menit
        ];

        // Mengirim permintaan POST untuk menambah film baru dengan data di atas
        $response = $this->post(route('admin.movies.store'), $filmData);

        // Memastikan ada redirect ke halaman daftar film dan pesan sukses
        $response->assertRedirect(route('admin.dashboard.film'))
            ->assertSessionHas('success', 'Film Berhasil Ditambahkan');

        // Memastikan film baru berhasil disimpan dalam database dengan judul dan durasi yang benar
        $this->assertDatabaseHas('films', [
            'judul' => 'Test Movie',
            'duration' => 120
        ]);

        // Memastikan file gambar tersimpan di penyimpanan palsu (disk 'public') pada folder 'posters'
        Storage::disk('public')->assertExists('posters/' . $file->hashName());
    }

    // Pengujian Memperbarui Film
    public function test_update_modifies_existing_film()
    {
        // Membuat film fiktif awal dengan judul dan durasi tertentu
        $film = Film::factory()->create([
            'judul' => 'Original Title',
            'duration' => 100
        ]);

        // Membuat file gambar baru untuk menggantikan poster sebelumnya
        $newFile = UploadedFile::fake()->image('new_movie.jpg');

        // Menyiapkan data pembaruan film
        $updateData = [
            'poster' => $newFile, // Poster baru
            'judul' => 'Updated Movie', // Judul baru
            'deskripsi' => 'Updated Description', // Deskripsi baru
            'genre' => ['Action', 'Drama'], // Genre
            'tanggalRilis' => '2024-02-01', // Tanggal rilis baru
            'duration' => 150 // Durasi baru
        ];

        // Mengirim permintaan PUT untuk memperbarui data film
        $response = $this->put(route('admin.dashboard.film.update', $film), $updateData);

        // Memastikan ada redirect ke halaman daftar film dan pesan sukses
        $response->assertRedirect(route('admin.dashboard.film'))
            ->assertSessionHas('success', 'Film Telah Terupdate');

        // Memastikan data film di database sudah diperbarui dengan judul dan durasi baru
        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'judul' => 'Updated Movie',
            'duration' => 150
        ]);

        // Memastikan poster lama diganti dengan poster baru
        Storage::disk('public')->assertExists('posters/' . $newFile->hashName());
    }

    // Pengujian Menghapus Film
    public function test_destroy_removes_film()
    {
        // Membuat film fiktif dengan poster yang disimpan di storage
        $film = Film::factory()->create([
            'poster' => 'posters/test.jpg'
        ]);

        // Menambahkan file fiktif ke penyimpanan palsu (disk 'public')
        Storage::disk('public')->put('posters/test.jpg', 'fake image content');

        // Mengirim permintaan DELETE untuk menghapus film
        $response = $this->delete(route('admin.dashboard.film.destroy', $film));

        // Memastikan ada redirect ke halaman daftar film dan pesan sukses
        $response->assertRedirect(route('admin.dashboard.film'))
            ->assertSessionHas('success', 'Film Telah Dihapus');

        // Memastikan data film di database sudah dihapus
        $this->assertDatabaseMissing('films', [
            'id' => $film->id
        ]);

        // Memastikan file poster terkait dihapus dari penyimpanan
        Storage::disk('public')->assertMissing('posters/sgUJcu2SZjCQcFNm7HBBwKkeNvpPVS1LspwsNFsg copy.png');
    }

    // Pengujian Menampilkan Form Tambah Film
    public function test_create_displays_create_form()
    {
        // Mengakses halaman form untuk menambah film baru
        $response = $this->get(route('admin.dashboard.createfilm'));

        // Memastikan halaman ditampilkan dengan sukses dan menggunakan view yang benar
        $response->assertStatus(200)
            ->assertViewIs('admin.dashboard.createfilm');
    }

    // Pengujian Menampilkan Form Edit Film
    public function test_edit_displays_edit_form()
    {
        // Membuat film fiktif yang akan diedit
        $film = Film::factory()->create();

        // Mengakses halaman form untuk mengedit film yang dipilih
        $response = $this->get(route('admin.dashboard.editfilm', $film));

        // Memastikan halaman ditampilkan dengan sukses dan memiliki data film
        $response->assertStatus(200)
            ->assertViewIs('admin.dashboard.editfilm')
            ->assertViewHas('film');
    }

    // Pengujian Validasi Tambah Film
    public function test_store_validates_required_fields()
    {
        // Mengirim permintaan POST tanpa data apapun untuk memeriksa validasi
        $response = $this->post(route('admin.movies.store'), []);

        // Memastikan ada kesalahan validasi pada field yang dibutuhkan
        $response->assertSessionHasErrors([
            'poster',
            'judul',
            'deskripsi',
            'genre',
            'tanggalRilis',
            'duration'
        ]);
    }
}
