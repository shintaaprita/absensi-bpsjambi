<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('nip_lama', 'like', "%{$search}%")
                  ->orWhere('nip_baru', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif' ? 1 : 0);
        }

        $users = $query->orderBy('fullname')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname'  => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username',
            'nip_lama'  => 'nullable|string|max:20|unique:users,nip_lama',
            'nip_baru'  => 'nullable|string|max:20|unique:users,nip_baru',
            'jabatan'   => 'nullable|string|max:255',
            'email'     => 'nullable|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'is_active' => 'boolean',
        ], [
            'fullname.required'  => 'Nama lengkap wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'nip_lama.unique'    => 'NIP Lama sudah terdaftar.',
            'nip_baru.unique'    => 'NIP Baru sudah terdaftar.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        User::create([
            'name'      => $request->fullname,
            'fullname'  => $request->fullname,
            'username'  => $request->username,
            'nip_lama'  => $request->nip_lama,
            'nip_baru'  => $request->nip_baru,
            'jabatan'   => $request->jabatan,
            'email'     => $request->email ?? $request->username . '@bpsjambi.go.id',
            'password'  => Hash::make($request->password),
            'roles_json'=> [2], // Default: employee role
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Anggota {$request->fullname} berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        $user->load('attendances.session');
        $totalAttendances = $user->attendances()->count();
        $monthlyAttendances = $user->attendances()
            ->whereMonth('captured_at', now()->month)
            ->whereYear('captured_at', now()->year)
            ->count();
        $recentAttendances = $user->attendances()
            ->with('session')
            ->orderBy('captured_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact('user', 'totalAttendances', 'monthlyAttendances', 'recentAttendances'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullname'  => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username,' . $user->id,
            'nip_lama'  => 'nullable|string|max:20|unique:users,nip_lama,' . $user->id,
            'nip_baru'  => 'nullable|string|max:20|unique:users,nip_baru,' . $user->id,
            'jabatan'   => 'nullable|string|max:255',
            'email'     => 'nullable|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
        ], [
            'fullname.required'  => 'Nama lengkap wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'nip_lama.unique'    => 'NIP Lama sudah terdaftar.',
            'nip_baru.unique'    => 'NIP Baru sudah terdaftar.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $data = [
            'name'      => $request->fullname,
            'fullname'  => $request->fullname,
            'username'  => $request->username,
            'nip_lama'  => $request->nip_lama,
            'nip_baru'  => $request->nip_baru,
            'jabatan'   => $request->jabatan,
            'email'     => $request->email,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Data anggota {$request->fullname} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->username === 'admin') {
            return back()->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $name = $user->fullname ?? $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Anggota {$name} berhasil dihapus.");
    }

    // ---- Import CSV ----
    public function importForm()
    {
        return view('admin.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv_file.required' => 'File CSV wajib dipilih.',
            'csv_file.mimes'    => 'File harus berformat CSV.',
            'csv_file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $file = $request->file('csv_file');
        $content = file_get_contents($file->getRealPath());

        // Hapus BOM jika ada
        $content = ltrim($content, "\xEF\xBB\xBF");

        // Auto-detect delimiter: titik koma (Excel Indonesia) atau koma (standar)
        $firstLine = strtok($content, "\n");
        $delimiter = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

        // Tulis ulang ke file temp tanpa BOM
        $tmpPath = tempnam(sys_get_temp_dir(), 'csv_');
        file_put_contents($tmpPath, $content);
        $handle = fopen($tmpPath, 'r');

        // Skip header row
        $header = fgetcsv($handle, 0, $delimiter);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $row = 1;

        while (($line = fgetcsv($handle, 0, $delimiter)) !== false) {
            $row++;

            // Expect columns: fullname, username, nip_lama, nip_baru, jabatan, password
            if (count($line) < 2) {
                $errors[] = "Baris {$row}: Format tidak valid.";
                $skipped++;
                continue;
            }

            $fullname = trim($line[0] ?? '');
            $username = trim($line[1] ?? '');
            $nip_lama = trim($line[2] ?? '') ?: null;
            // Hapus prefix tab jika ada (dari template Excel)
            $nip_baru = ltrim(trim($line[3] ?? ''), "\t") ?: null;
            $jabatan  = trim($line[4] ?? '') ?: null;
            $password = trim($line[5] ?? '') ?: 'password123';

            if (empty($fullname) || empty($username)) {
                $errors[] = "Baris {$row}: Nama dan username wajib diisi.";
                $skipped++;
                continue;
            }

            // Skip if username already exists
            if (User::where('username', $username)->exists()) {
                $errors[] = "Baris {$row}: Username '{$username}' sudah terdaftar.";
                $skipped++;
                continue;
            }

            // Skip if nip_lama already exists
            if ($nip_lama && User::where('nip_lama', $nip_lama)->exists()) {
                $errors[] = "Baris {$row}: NIP Lama '{$nip_lama}' sudah terdaftar.";
                $skipped++;
                continue;
            }

            try {
                User::create([
                    'name'       => $fullname,
                    'fullname'   => $fullname,
                    'username'   => $username,
                    'nip_lama'   => $nip_lama,
                    'nip_baru'   => $nip_baru,
                    'jabatan'    => $jabatan,
                    'email'      => $username . '@bpsjambi.go.id',
                    'password'   => Hash::make($password),
                    'roles_json' => [2],
                    'is_active'  => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$row}: Gagal menyimpan ({$e->getMessage()}).";
                $skipped++;
            }
        }

        fclose($handle);
        // Hapus file temp
        if (isset($tmpPath) && file_exists($tmpPath)) {
            unlink($tmpPath);
        }

        $message = "Import selesai: {$imported} anggota berhasil ditambahkan, {$skipped} dilewati.";

        return redirect()->route('admin.users.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    // Download CSV template
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_anggota.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar Excel langsung baca dengan benar
            fputs($file, "\xEF\xBB\xBF");

            // Gunakan titik koma (;) sebagai delimiter — standar Excel Indonesia
            $delimiter = ';';

            // Header kolom
            fputcsv($file, ['Nama Lengkap', 'Username', 'NIP Lama', 'NIP Baru', 'Jabatan', 'Password'], $delimiter);

            // Contoh data
            // NIP Baru diberi prefix tab (\t) agar Excel tidak mengubah angka panjang
            fputcsv($file, ['Budi Santoso', 'budi.santoso', '340012345', "\t199001012020011001", 'Statistisi Ahli Muda', 'password123'], $delimiter);
            fputcsv($file, ['Siti Rahayu', 'siti.rahayu', '340012346', "\t199005012020012001", 'Analis Data', 'password123'], $delimiter);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
