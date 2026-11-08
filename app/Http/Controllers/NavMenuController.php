<?php

namespace App\Http\Controllers;

use App\Models\NavMenu;
use Illuminate\Http\Request;

class NavMenuController extends Controller
{
    public function index()
    {
        NavMenu::pastikanTabelSiap();

        // Daftar Induk beserta Anak & Cucunya untuk Tabel Silsilah
        $indukMenus = NavMenu::with(['children.children'])
            ->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Daftar khusus Level 1 (Induk) untuk dropdown saat membuat Anak Menu
        $listInduk = NavMenu::where('level', 1)
            ->orderBy('urutan', 'asc')
            ->get();

        // Daftar khusus Level 2 (Anak) beserta nama Induknya untuk dropdown saat membuat Cucu Menu
        $listAnak = NavMenu::with('parent')
            ->where('level', 2)
            ->orderBy('parent_id', 'asc')
            ->orderBy('urutan', 'asc')
            ->get();

        return view('nav_menu.index', compact('indukMenus', 'listInduk', 'listAnak'));
    }

    public function store(Request $request)
    {
        NavMenu::pastikanTabelSiap();

        $request->validate([
            'level'     => 'required|in:1,2,3',
            'nama_menu' => 'required|string|max:100',
            'url'       => 'nullable|string|max:255',
            'urutan'    => 'required|integer|min:1',
            'target'    => 'required|in:_self,_blank',
        ]);

        $level    = (int) $request->level;
        $parentId = null;

        if ($level === 2) {
            if (!$request->filled('induk_id')) {
                return redirect()->back()->with('error', 'Silakan pilih Induk Menu (Level 1) terlebih dahulu untuk Anak Menu ini!')->withInput();
            }
            $induk = NavMenu::where('id', $request->induk_id)->where('level', 1)->first();
            if (!$induk) {
                return redirect()->back()->with('error', 'Induk Menu yang dipilih tidak valid!')->withInput();
            }
            $parentId = $induk->id;
        } elseif ($level === 3) {
            if (!$request->filled('anak_id')) {
                return redirect()->back()->with('error', 'Silakan pilih Anak Menu (Level 2) terlebih dahulu untuk Cucu Menu ini!')->withInput();
            }
            $anak = NavMenu::where('id', $request->anak_id)->where('level', 2)->first();
            if (!$anak) {
                return redirect()->back()->with('error', 'Anak Menu yang dipilih tidak valid!')->withInput();
            }
            $parentId = $anak->id;
        }

        NavMenu::create([
            'nama_menu' => strip_tags(trim($request->nama_menu)),
            'url'       => trim($request->url ?: '#'),
            'parent_id' => $parentId,
            'level'     => $level,
            'urutan'    => (int) $request->urutan,
            'target'    => $request->target,
            'badge_new' => $level === 1 && $request->has('badge_new'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Menu "' . $request->nama_menu . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        NavMenu::pastikanTabelSiap();

        $menu = NavMenu::with('children.children')->findOrFail($id);

        $request->validate([
            'level'     => 'required|in:1,2,3',
            'nama_menu' => 'required|string|max:100',
            'url'       => 'nullable|string|max:255',
            'urutan'    => 'required|integer|min:1',
            'target'    => 'required|in:_self,_blank',
        ]);

        $level    = (int) $request->level;
        $parentId = null;

        if ($level === 2) {
            if (!$request->filled('induk_id') || (int) $request->induk_id === (int) $menu->id) {
                return redirect()->back()->with('error', 'Pilih Induk Menu (Level 1) yang valid!');
            }
            $parentId = (int) $request->induk_id;
        } elseif ($level === 3) {
            if (!$request->filled('anak_id') || (int) $request->anak_id === (int) $menu->id) {
                return redirect()->back()->with('error', 'Pilih Anak Menu (Level 2) yang valid!');
            }
            $parentId = (int) $request->anak_id;
        }

        $menu->update([
            'nama_menu' => strip_tags(trim($request->nama_menu)),
            'url'       => trim($request->url ?: '#'),
            'parent_id' => $parentId,
            'level'     => $level,
            'urutan'    => (int) $request->urutan,
            'target'    => $request->target,
            'badge_new' => $level === 1 && $request->has('badge_new'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data menu "' . $menu->nama_menu . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        NavMenu::pastikanTabelSiap();

        $menu = NavMenu::with('children.children')->findOrFail($id);

        foreach ($menu->children as $anak) {
            foreach ($anak->children as $cucu) {
                $cucu->delete();
            }
            $anak->delete();
        }

        $menu->delete();

        return redirect()->back()->with('success', 'Menu beserta sub-menu turunannya berhasil dihapus!');
    }
}