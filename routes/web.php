<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('auth.login');
});

// Login & logout function
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::middleware('auth')->group(function () {

    Route::get('/tempahan', function () {
        $tempahanList = session('tempahan_list', []);

        // Seed contoh bila kosong (untuk nampakkan "Upload Surat")
        if (empty($tempahanList)) {
            $tempahanList = [
                [
                    'id' => Str::upper(Str::random(1)),
                    'nama_program' => 'Majlis Apresiasi Pelajar',
                    'tujuan' => 'Majlis',
                    'kampus_id' => 'S2',
                    'kampus_nama' => 'UiTM Samarahan 2',
                    'ruang_kod' => 'DJ-S2',
                    'ruang_nama' => 'Dewan Jubli',
                    'tarikh_mula' => now()->addDays(10)->setTime(8, 30)->toDateTimeString(),
                    'tarikh_tamat' => now()->addDays(10)->setTime(12, 30)->toDateTimeString(),
                    'perkhidmatan' => ['WiFi', 'Audio Visual'],
                    'status' => 'Disahkan (Available)',
                    'surat' => null,
                ]
            ];
            session(['tempahan_list' => $tempahanList]);
        }
        return view('pages.tempahan.index', compact('tempahanList'));
    })->name('tempahan.index');

    Route::get('/tempahan/form', function () {
        $campusOptions = [
            ['id' => 'S1', 'name' => 'UiTM Samarahan'],
            ['id' => 'S2', 'name' => 'UiTM Samarahan 2'],
            ['id' => 'MUK', 'name' => 'UiTM Mukah'],
        ];
        return view('pages.tempahan.form', compact('campusOptions'));
    })->name('tempahan.form');

    Route::post('/tempahan/submit', function (Request $request) {
        $data = $request->only([
            'nama_program',
            'tarikh_mula',
            'tarikh_tamat',
            'kampus_id',
            'kampus_nama',
            'ruang_kod',
            'ruang_nama',
            'tujuan',
            'perkhidmatan'
        ]);
        $data['status'] = 'Sedang Diproses';
        $data['id'] = Str::upper(Str::random(6));
        $data['surat'] = null; // metadata upload surat (nama, saiz)
        $list = session('tempahan_list', []);
        array_unshift($list, $data);
        session(['tempahan_list' => $list]);

        return redirect()->route('tempahan.index')
            ->with('success', 'Permohonan telah dihantar, sila semak dalam masa 3 hari.');
    })->name('tempahan.submit');

    Route::get('/tempahan/{id}', function ($id) {
        $list = session('tempahan_list', []);
        $tempahan = collect($list)->firstWhere('id', $id);
        abort_if(!$tempahan, 404);
        return view('pages.tempahan.view', compact('tempahan'));
    })->name('tempahan.view');

    /* ===== Tindakan PIC (superadmin view) ===== */

    // Sahkan (Available)
    Route::post('/tempahan/{id}/pic-confirm', function ($id, Request $request) {
        $list = session('tempahan_list', []);
        foreach ($list as &$t) {
            if ($t['id'] === $id) {
                $t['status'] = 'Disahkan (Available)';
                $t['pic_remark'] = $request->input('remark');
                $t['disahkan_pada'] = now()->format('d/m/Y h:i A');
                break;
            }
        }
        session(['tempahan_list' => $list]);

        return redirect()->route('tempahan.index')
            ->with('success', 'Tempahan telah disahkan. Pemohon boleh memuat naik surat.');
    })->name('tempahan.pic.confirm');

    // Tolak / Tidak Tersedia
    Route::post('/tempahan/{id}/pic-reject', function ($id, Request $request) {
        $list = session('tempahan_list', []);
        foreach ($list as &$t) {
            if ($t['id'] === $id) {
                $t['status'] = 'Tidak Tersedia';
                $t['pic_remark'] = $request->input('remark');
                $t['ditolak_pada'] = now()->format('d/m/Y h:i A');
                break;
            }
        }
        session(['tempahan_list' => $list]);

        return redirect()->route('tempahan.index')
            ->with('success', 'Tempahan telah ditandakan sebagai Tidak Tersedia.');
    })->name('tempahan.pic.reject');

    /* ===== Upload Surat oleh Pemohon (di index) ===== */
    Route::post('/tempahan/{id}/upload-surat', function ($id, Request $request) {
        // Demo sahaja: tidak simpan fail ke disk — hanya rekod metadata
        $list = session('tempahan_list', []);
        foreach ($list as &$t) {
            if ($t['id'] === $id) {
                if ($request->hasFile('surat')) {
                    $file = $request->file('surat');
                    $t['surat'] = [
                        'nama' => $file->getClientOriginalName(),
                        'saiz' => $file->getSize(),
                        'muat_naik_pada' => now()->format('d/m/Y h:i A'),
                    ];
                }
                // status boleh kekal Disahkan; atau nak tukar:
                // $t['status'] = 'Surat Diupload';
                break;
            }
        }
        session(['tempahan_list' => $list]);

        return redirect()->route('tempahan.index')
            ->with('success', 'Surat telah dimuat naik (demo).');
    })->name('tempahan.upload.surat');


    // =====================
    //  SEBUTHARGA (MOCKUP)
    // =====================
    Route::prefix('sebutharga')->name('sebutharga.')->group(function () {

        // Mock tempahan ringkas untuk dipilih di borang
        $tempahanList = [
            101 => [
                'id' => 101,
                'nama_program' => 'Festival Kampus',
                'kampus_nama' => 'UiTM Samarahan',
                'ruang_nama' => 'Dewan Jubli',
                'tarikh_mula' => '2025-05-01 08:00:00',
                'tarikh_tamat' => '2025-05-05 23:00:00',
                'status' => 'Diluluskan'
            ],
            102 => [
                'id' => 102,
                'nama_program' => 'Hari Terbuka Fakulti',
                'kampus_nama' => 'UiTM Samarahan',
                'ruang_nama' => 'Dewan Jubli',
                'tarikh_mula' => '2025-11-30 08:00:00',
                'tarikh_tamat' => '2025-11-30 17:00:00',
                'status' => 'Diluluskan'
            ],
        ];

        // INDEX
        Route::get('/', function () use ($tempahanList) {
            // Mock senarai Sebut Harga (ringkas)
            $sebuthargaList = [
                [
                    'id' => 1,
                    'no_rujukan' => 'UITM/SBH/2025/001',
                    'tempahan_id' => 101,
                    'tajuk' => 'Fasiliti & Prasarana',
                    'items' => [
                        ['desc' => 'Dewan Jubli (1–5 Mei 2025)', 'rate' => '2,500.00 / hari x 5', 'amount' => 12500.00],
                        ['desc' => 'Kerusi banquet', 'rate' => '4 x 100', 'amount' => 400.00],
                        ['desc' => 'Bilik Perdana (3 & 4 Mei)', 'rate' => '315 x 1.5', 'amount' => 472.50],
                    ],
                    'status' => 'Ditutup',
                ],
                [
                    'id' => 2,
                    'no_rujukan' => 'UITM/SBH/2025/002',
                    'tempahan_id' => 102,
                    'tajuk' => 'Sewaan Hari Terbuka',
                    'items' => [
                        ['desc' => 'Dewan Jubli (30 Nov 2025)', 'rate' => '2,000.00 x 1', 'amount' => 2000.00],
                    ],
                    'status' => 'Draf',
                ],
            ];

            // Lampirkan ringkasan tempahan & jumlah
            foreach ($sebuthargaList as &$s) {
                $s['tempahan'] = $tempahanList[$s['tempahan_id']];
                $s['total'] = array_sum(array_map(function ($r) {
                    return isset($r['amount']) ? (float) $r['amount'] : 0.0;
                }, $s['items']));
            }

            return view('pages.sebutharga.index', compact('sebuthargaList'));
        })->name('index');

        // FORM (Tambah/Kemaskini) — ringkas
        Route::get('/form', function () use ($tempahanList) {
            $id = request('id');
            $str_mode = $id ? 'Kemaskini' : 'Tambah';
            $save_route = route('sebutharga.save');

            // Data sedia ada bila edit (mock)
            $sebutharga = $id ? [
                'id' => 1,
                'no_rujukan' => 'UITM/SBH/2025/001',
                'tempahan_id' => 101,
                'tajuk' => 'Fasiliti & Prasarana',
                'items' => [
                    ['desc' => 'Dewan Jubli (1–5 Mei 2025)', 'rate' => '2,500.00 / hari x 5', 'amount' => 12500.00],
                    ['desc' => 'Kerusi banquet', 'rate' => '4 x 100', 'amount' => 400.00],
                ],
            ] : null;

            return view('pages.sebutharga.form', compact('str_mode', 'save_route', 'sebutharga', 'tempahanList'));
        })->name('form');

        // SAVE (mock sahaja)
        Route::post('/save', function () {
            return redirect()->route('sebutharga.index')->with('success', 'Sebutharga disimpan (mock).');
        })->name('save');

        // VIEW: Papar satu Sebut Harga (MOCK)
        Route::get('/{id}', function ($id) use ($tempahanList) {
            // MOCK: Rekod detail
            $sebutharga = [
                'id' => (int)$id,
                'tempahan_id' => 101,
                'no_rujukan' => 'UITM/SBH/2025/001',
                'tajuk' => 'Sebut Harga PA System & Kelengkapan',
                'skop' => 'Pembekalan PA system lengkap termasuk mikrofon, mixer, speaker.',
                'tarikh_iklan' => '2025-10-20',
                'tarikh_tutup' => '2025-10-27',
                'pegawai' => 'En. Rahman',
                'anggaran_kos' => '5000',
                'vendor_dipelawa' => 'Syarikat A, Syarikat B, Syarikat C',
                'status' => 'Dihantar',
                'lampiran' => ['nama' => 'Iklan_PA_System.pdf'],
            ];
            $tempahan = $tempahanList[$sebutharga['tempahan_id']] ?? null;

            return view('pages.sebutharga.view', compact('sebutharga', 'tempahan'));
        })->name('view');
    });

    // =====================
    //  PEMBAYARAN (MOCK)
    // =====================
    Route::post('/tempahan/{id}/pembayaran', function ($id) {
        // MOCK: tiada simpan fail. Hanya flash mesej berjaya.
        // Akses data: request('tarikh_bayar'), request('jumlah'), request('rujukan'), request()->file('bukti'), request('catatan')
        return redirect()->back()->with('success', 'Bukti pembayaran dimuat naik (mock).');
    })->name('tempahan.pembayaran.upload');

    // =====================
    //  RUANG (MOCKUP)
    // =====================
    Route::prefix('ruang')->name('ruang.')->group(function () {

        // Mock: Senarai kampus untuk dropdown
        $kampusOptions = [
            1 => 'UiTM Samarahan',
            2 => 'UiTM Samarahan 2',
            3 => 'UiTM Mukah',
        ];

        // INDEX: Senarai Ruang
        Route::get('/', function () use ($kampusOptions) {
            // Mock data ruang
            $ruangList = [
                ['id' => 1, 'nama' => 'Dewan Jubli', 'kampus_id' => 1, 'pemilik' => 'BPF', 'status' => 'Aktif'],
                ['id' => 2, 'nama' => 'Auditorium', 'kampus_id' => 3, 'pemilik' => 'UPP', 'status' => 'Aktif'],
                ['id' => 3, 'nama' => 'Makmal Komputer B4079', 'kampus_id' => 2, 'pemilik' => 'Infostruktur', 'status' => 'Tidak Aktif'],
            ];
            // inject nama kampus
            foreach ($ruangList as &$r) {
                $r['kampus_nama'] = isset($kampusOptions[$r['kampus_id']]) ? $kampusOptions[$r['kampus_id']] : '-';
            }

            return view('pages.ruang.index', compact('ruangList'));
        })->name('index');

        // FORM: Tambah/Kemaskini
        Route::get('/form', function () use ($kampusOptions) {
            $id = request('id');
            $str_mode   = $id ? 'Kemaskini' : 'Tambah';
            $save_route = route('ruang.save');

            // Mock rekod bila edit
            $ruang = $id ? ['id' => 1, 'nama' => 'Dewan Jubli', 'kampus_id' => 1, 'pemilik' => 'BPF', 'status' => 1] : null;

            return view('pages.ruang.form', compact('str_mode', 'save_route', 'ruang', 'kampusOptions'));
        })->name('form');

        // SAVE (mock): hanya flash message
        Route::post('/save', function () {
            return redirect()->route('ruang.index')->with('success', 'Ruang disimpan (mock).');
        })->name('save');

        // VIEW: Papar satu ruang
        Route::get('/{id}', function ($id) use ($kampusOptions) {
            // Mock lookup by id
            $ruang = ['id' => (int)$id, 'nama' => 'Dewan Jubli', 'kampus_id' => 1, 'pemilik' => 'BPF', 'status' => 1];
            $kampus_nama = isset($kampusOptions[$ruang['kampus_id']]) ? $kampusOptions[$ruang['kampus_id']] : '-';
            return view('pages.ruang.view', compact('ruang', 'kampus_nama'));
        })->name('view');
    });

    // =====================
    //  PERKHIDMATAN (MOCKUP)
    // =====================
    Route::prefix('perkhidmatan')->name('perkhidmatan.')->group(function () {

        // PTJ untuk dropdown
        $ptjOptions = [
            10 => 'BPF',
            20 => 'UPP',
            30 => 'Infostruktur',
            40 => 'Fakulti Pengurusan',
        ];

        // INDEX: Senarai Perkhidmatan
        Route::get('/', function () use ($ptjOptions) {
            $perkhidmatanList = [
                ['id' => 1, 'nama' => 'PA System', 'ptj_id' => 20, 'status' => 1],
                ['id' => 2, 'nama' => 'WiFi Acara', 'ptj_id' => 30, 'status' => 1],
                ['id' => 3, 'nama' => 'Pembersihan Khas', 'ptj_id' => 10, 'status' => 0],
            ];
            // inject nama PTJ
            foreach ($perkhidmatanList as &$p) {
                $p['ptj_nama'] = isset($ptjOptions[$p['ptj_id']]) ? $ptjOptions[$p['ptj_id']] : '-';
            }
            return view('pages.perkhidmatan.index', compact('perkhidmatanList'));
        })->name('index');

        // FORM: Tambah/Kemaskini
        Route::get('/form', function () use ($ptjOptions) {
            $id = request('id');
            $str_mode   = $id ? 'Kemaskini' : 'Tambah';
            $save_route = route('perkhidmatan.save');

            // mock rekod bila edit
            $perkhidmatan = $id ? ['id' => 1, 'nama' => 'PA System', 'ptj_id' => 20, 'status' => 1] : null;

            return view('pages.perkhidmatan.form', compact('str_mode', 'save_route', 'perkhidmatan', 'ptjOptions'));
        })->name('form');

        // SAVE (mock): flash saja
        Route::post('/save', function () {
            return redirect()->route('perkhidmatan.index')->with('success', 'Perkhidmatan disimpan (mock).');
        })->name('save');

        // VIEW: Papar satu perkhidmatan
        Route::get('/{id}', function ($id) use ($ptjOptions) {
            $perkhidmatan = ['id' => (int)$id, 'nama' => 'PA System', 'ptj_id' => 20, 'status' => 1];
            $ptj_nama = isset($ptjOptions[$perkhidmatan['ptj_id']]) ? $ptjOptions[$perkhidmatan['ptj_id']] : '-';
            return view('pages.perkhidmatan.view', compact('perkhidmatan', 'ptj_nama'));
        })->name('view');
    });

    //Campus
    Route::get('campus', 'CampusController@index')->name('campus');
    Route::get('campus/view/{id}', 'CampusController@show')->name('campus.show');
    Route::get('/campus/search', 'CampusController@search')->name('campus.search');

    //Position
    Route::get('position', 'PositionController@index')->name('position');
    Route::get('position/view/{id}', 'PositionController@show')->name('position.show');
    Route::get('/position/search', 'PositionController@search')->name('position.search');


    Route::get('/home', 'HomeController@index')->name('home');

    // User Profile
    Route::get('profile/{id}', 'UserProfileController@show')->name('profile.show');
    Route::get('profile/{id}/edit', 'UserProfileController@edit')->name('profile.edit');
    Route::put('profile/{id}', 'UserProfileController@update')->name('profile.update');
    Route::get('profile/{id}/change-password', 'UserProfileController@changePasswordForm')->name('profile.change-password');
    Route::post('profile/{id}/change-password', 'UserProfileController@changePassword')->name('profile.update-password');

    // Superadmin - Activity Log
    Route::get('activity-log', 'ActivityLogController@index')->name('activity-log');
    Route::get('/debug-logs', 'ActivityLogController@showDebugLogs')->name('logs.debug');

    // User Management
    Route::get('user', 'UserController@index')->name('user');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user/store', 'UserController@store')->name('user.store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::post('user/{id}', 'UserController@update')->name('user.update');
    Route::get('user/view/{id}', 'UserController@show')->name('user.show');
    Route::get('/user/search', 'UserController@search')->name('user.search');
    Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');
    Route::get('/user/trash', 'UserController@trashList')->name('user.trash');
    Route::get('/user/{id}/restore', 'UserController@restore')->name('user.restore');
    Route::delete('/user/{id}/force-delete', 'UserController@forceDelete')->name('user.forceDelete');

    // User Role Management
    Route::get('user-role', 'UserRoleController@index')->name('user-role');
    Route::get('user-role/create', 'UserRoleController@create')->name('user-role.create');
    Route::post('user-role/store', 'UserRoleController@store')->name('user-role.store');
    Route::get('user-role/{id}/edit', 'UserRoleController@edit')->name('user-role.edit');
    Route::post('user-role/{id}', 'UserRoleController@update')->name('user-role.update');
    Route::get('user-role/view/{id}', 'UserRoleController@show')->name('user-role.show');
    Route::get('/user-role/search', 'UserRoleController@search')->name('user-role.search');
    Route::delete('user-role/{id}', 'UserRoleController@destroy')->name('user-role.destroy');
    Route::get('/user-role/trash', 'UserRoleController@trashList')->name('user-role.trash');
    Route::get('/user-role/{id}/restore', 'UserRoleController@restore')->name('user-role.restore');
    Route::delete('/user-role/{id}/force-delete', 'UserRoleController@forceDelete')->name('user-role.forceDelete');

    //Campus
    Route::get('campus/create', 'CampusController@create')->name('campus.create');
    Route::post('campus/store', 'CampusController@store')->name('campus.store');
    Route::get('campus/{id}/edit', 'CampusController@edit')->name('campus.edit');
    Route::post('campus/{id}', 'CampusController@update')->name('campus.update');
    Route::delete('campus/{id}', 'CampusController@destroy')->name('campus.destroy');
    Route::get('/campus/trash', 'CampusController@trashList')->name('campus.trash');
    Route::get('/campus/{id}/restore', 'CampusController@restore')->name('campus.restore');
    Route::delete('/campus/{id}/force-delete', 'CampusController@forceDelete')->name('campus.forceDelete');

    //Position
    Route::get('position/create', 'PositionController@create')->name('position.create');
    Route::post('position/store', 'PositionController@store')->name('position.store');
    Route::get('position/{id}/edit', 'PositionController@edit')->name('position.edit');
    Route::post('position/{id}', 'PositionController@update')->name('position.update');
    Route::delete('position/{id}', 'PositionController@destroy')->name('position.destroy');
    Route::get('/position/trash', 'PositionController@trashList')->name('position.trash');
    Route::get('/position/{id}/restore', 'PositionController@restore')->name('position.restore');
    Route::delete('/position/{id}/force-delete', 'PositionController@forceDelete')->name('position.forceDelete');
});
