<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function list()
    {
        $data = Post::get();
        return view('admin.posts.list', compact('data'));
    }
    public function create()
    {
        $cate = Category::all();
        return view('admin.posts.create', compact('cate'));
    }
    public function store(StorePostRequest $request)
    {
        try {
            $data = $request->all();

            $data['date_of_post'] = now();

            if ($request->hasFile('img')) {
                $a = Storage::put('images', $request->file("img"));
                $data['img'] = $a;
            }

            Post::create($data);

            return redirect()->back()->with('success', 'Thêm tin thành công!');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
    public function edit($id)
    {
        $listCate = Category::all();
        $post = Post::find($id);
        // dd($post);

        return view('admin.posts.edit', ['post' => $post, 'listCate' => $listCate]);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $tin = Post::findOrFail($id);
        $data = $request->all();
        try {
            if ($request->hasFile('img')) {
                \Storage::delete($tin->img);
                $a = Storage::put('images', $request->file("img"));
                $data['img'] = $a;
            } else {
                $data['img'] = $tin->img;
            }
            $tin->update($data);
            return redirect()->back()->with('success', 'Cập nhật thành công !');
        } catch (\Exception $exception) {
            return back()->with('erorr', 'Fail !');
        }
    }
    public function destroy( $id)
    {
        Post::destroy($id);
        return back()->with('success', 'Xóa thành công !');
    }


    public function all()
    {
        // $data = DB::table("posts")->get();
        $data = Post::get();
        return $data;
    }

    public function tinHot()
    {
        return Post::where('date_of_post', '>=', now()->subDays(30))
            ->orderBy('view', 'desc')
            ->first();
    }
    public function tinMoi()
    {
        return Post::select('posts.*', 'categories.category_name')
        ->join('categories', 'posts.category_id', '=', 'categories.id')
        ->orderBy('date_of_post', 'desc')
        ->take(4)
        ->get();
    
    }

    public function tinKhac()
    {
        return Post::orderBy('view', 'desc')
            ->offset(1)
            ->take(3)
            ->get();
    }
    public function index()
    {
        $tinHot = $this->tinHot();
        $tinMoi = $this->tinMoi();
        $tinKhac = $this->tinKhac();
        $listCate = Category::all();

        return view('index', compact('tinHot', 'tinMoi', 'tinKhac', 'listCate'));
    }

    public function postChiTiet($id)
    {
        $ctTin = Post::find($id);
        $listCate = Category::all();
        $ctTin->increment('view');
        return view('chitiettin', compact('ctTin', 'listCate'));
        // dd($ctTin);

    }
    public function listTin($id)
    {
        $listCate = Category::all();
        $tins = Post::where('category_id', $id)
            ->get();

        $loaitin = Post::join('categories', 'posts.category_id', '=', 'categories.id')
            ->get();

        return view('tintrongloai', compact('tins', 'loaitin', 'listCate'));
    }
    public function  timKiem(Request $request)
    {
        $listCate = Category::all();
        $tins =  Post::where('title', 'LIKE', '%' . $request
            ->input('keyword') . '%')
            ->get();
        return view('timkiem', ['tins' => $tins, 'tenLoai' => 'Kết quả tìm kiếm :', 'listCate' => $listCate]);
    }
}
