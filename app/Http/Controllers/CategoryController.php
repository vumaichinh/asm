<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;


use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function all()
    {
        return  Category::all();
    }
    public function categoryChiTiet($id)
    {
        return  Category::find($id);
    }
    public function list()
    {
        $listCate = Category::get();
        return view('admin.categories.list', compact('listCate'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {

        $data = $request->all();

        Category::create($data);

        return redirect()->back()->with('success', 'Thêm tin thành công!');
    }

    public function edit($id)
    {
        $cate = Category::find($id);

        return view('admin.categories.edit', ['cate' => $cate]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $cate = Category::findOrFail($id);
        $data = $request->all();
    
            $cate->update($data);
            return redirect()->back()->with('success', 'Cập nhật thành công !');
       
    }
    public function destroy($id)
    {
        Category::destroy($id);
        return back()->with('success', 'Xóa thành công !');
    }
}
