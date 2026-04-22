namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Penting buat hapus foto lama

class NewsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        $data = $request->all();

        // LOGIC UPLOAD FOTO
        if ($request->hasFile('image')) {
            // Simpan file ke storage/app/public/news
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }

        News::create($data);

        return redirect()->back()->with('success', 'Berita berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus foto lama biar gak nyampah di storage
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            // Upload foto baru
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }

        $news->update($data);

        return redirect()->back()->with('success', 'Berita berhasil diupdate!');
    }
}
