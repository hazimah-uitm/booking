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
