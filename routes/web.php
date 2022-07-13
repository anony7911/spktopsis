<?php

Route::group(['as' => 'admin.', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        $data['trlh'] = count(\App\Model\Rtlh::all());
        return view('admin.dashboard', $data);
    });
    Route::get('/artlh', function () {
        return view('admin.artlh.index');
    });
    Route::get('/datajson', function () {
        return view('admin.artlh.index');
    });
    Route::name('admin.artlh.index')->get('/rtlh', 'rtlhController@index');
    Route::get('/asetting', function () {
        $options = \App\Model\Setting::getAllKeyValue();
        return view('admin.setting', $options);
    });
    Route::get('/alinguistik', function () {
        return view('admin.topsis.linguistik');
    });
    Route::get('/amatrix_keputusan', function () {
        return view('admin.topsis.matrix_keputusan');
    });
    Route::get('/amatrix_keputusan_ternormalisasi', function () {
        return view('admin.topsis.matrix_keputusan_ternormalisasi');
    });
    Route::get('/amatrix_keputusan_terbobot', function () {
        return view('admin.topsis.matrix_keputusan_terbobot');
    });
    Route::get('/ajarak_solusi_positif', function () {
        return view('admin.topsis.jarak_solusi_positif');
    });
    Route::get('/ajarak_solusi_negatif', function () {
        return view('admin.topsis.jarak_solusi_negatif');
    });
    Route::get('/anilai_preferensi', function () {
        return view('admin.topsis.nilai_preferensi');
    });

    Route::get('/ahasil_rekomendasi', function () {
        return view('admin.topsis.hasil_rekomendasi');
    });
    Route::get('/amatrix_solusi_ideal', 'analisaTopsis@solusi_ideal');

    Route::group(['prefix' => 'admin'], function () {
        Route::group(['as' => 'rtlh.', 'prefix' => 'rtlh'], function () {
            Route::get('/', 'rtlhController@index')->name('index');
            Route::get('/data', 'rtlhController@data')->name('data');
            Route::post('/add', 'rtlhController@store')->name('add');
            Route::post('/edit', 'rtlhController@edit');
            // Route::get('/{id}/edit','rtlhController@edit');
            Route::post('/update', 'rtlhController@update')->name('update');
            Route::post('/delete', 'rtlhController@delete')->name('delete');
            Route::post('/softDelete', 'rtlhController@softDelete')->name(
                'softDelete'
            );
            Route::get('/penerima', 'rtlhController@penerima')->name(
                'penerima'
            );
        });

        Route::group(['as' => 'topsis.', 'prefix' => 'topsis'], function () {
            Route::get(
                '/matrix_keputusan',
                'analisaTopsis@matrix_keputusan'
            )->name('matrix_keputusan');
            Route::get(
                '/matrix_keputusan_ternormalisasi',
                'analisaTopsis@matrix_keputusan_ternormalisasi'
            )->name('matrix_keputusan_ternormalisasi');
            Route::get(
                '/matrix_keputusan_terbobot',
                'analisaTopsis@matrix_keputusan_terbobot'
            )->name('matrix_keputusan_terbobot');
            Route::get(
                '/jarak_solusi_positif',
                'analisaTopsis@jarak_solusi_positif'
            )->name('jarak_solusi_positif');
            Route::get(
                '/jarak_solusi_negatif',
                'analisaTopsis@jarak_solusi_negatif'
            )->name('jarak_solusi_negatif');
            Route::get(
                '/nilai_preferensi',
                'analisaTopsis@nilai_preferensi'
            )->name('nilai_preferensi');
        });
        Route::group(['as' => 'setting.', 'prefix' => 'setting'], function () {
            Route::post('/bobot', 'settingController@bobot')->name('bobot');
        });
    });
});

Route::get('/masuk', function () {
    return view('admin.login');
});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/', function () {
//     $data['trlh'] = count(\App\Model\Rtlh::all());
//     return view('koord.dashboard', $data);
// });

// Route::get('/koord/anilai_preferensi', function () {
//     return view('koord.nilai_preferensi');
// });

// Route::get('/rtlh/ahasil_rekomendasi', function () {
//     return view('koord.hasil_rekomendasi');
// });

Route::group(['as' => 'koord.'], function () {
    Route::get('/matrix_keputusan', 'analisaKoord@matrix_keputusan')->name(
        'matrix_keputusan'
    );
    Route::get(
        '/matrix_keputusan_ternormalisasi',
        'analisaKoord@matrix_keputusan_ternormalisasi'
    )->name('matrix_keputusan_ternormalisasi');
    Route::get(
        '/matrix_keputusan_terbobot',
        'analisaKoord@matrix_keputusan_terbobot'
    )->name('matrix_keputusan_terbobot');
    Route::get(
        '/jarak_solusi_positif',
        'analisaKoord@jarak_solusi_positif'
    )->name('jarak_solusi_positif');
    Route::get(
        '/jarak_solusi_negatif',
        'analisaKoord@jarak_solusi_negatif'
    )->name('jarak_solusi_negatif');
    Route::get('/nilai_preferensi', 'analisaKoord@nilai_preferensi')->name(
        'nilai_preferensi'
    );
});
Route::group(['as' => 'setting.', 'prefix' => 'setting'], function () {
    Route::post('/bobot', 'settingController@bobot')->name('bobot');
});
