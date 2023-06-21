<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Input;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $maxdb = Product::max('price');

        if ($request->has('reset')) {
            unset($request['categories'], $request['c'], $request['min'], $request['max']);
        }

        $categoriesSlug = $request['categories'] ?? [];

        $categorySlug = $request['c'] ?? null;
        if ($categorySlug) {
            $categoriesSlug []= $categorySlug;
        }

        $search = $request['q'];
        $min = empty($request['min']) ? 0 : $request['min'];
        $max = empty($request['max']) ? $maxdb : $request['max'];
        $order = $request['order'];
        $sort = 'asc';
        if (!empty($request['sort'])) {
            $sort = $request['sort'];
            if ($sort == 'asc') {
                $sort = 'desc';
            } else {
                $sort = 'asc';
            }
        }


        $arProducts = Product::where('amount', '>', 0)
            ->when(!empty($min), function ($q) use ($min) {
                $q->where('price', '>=', $min);
            })
            ->when(!empty($max), function ($q) use ($max) {
                $q->where('price', '<=', $max);
            })
            ->when(!empty($order), function ($q) use ($order, $sort) {
                $q->orderBy($order, $sort);
            })
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })
            ->when(!(empty($categoriesSlug)), function ($q) use ($categoriesSlug) {
                $categories = Category::query()
                    ->whereIn('slug', $categoriesSlug)
                    ->get()
                    ->map(fn($category) => $category->id);
                if (!empty($categories)) {
                    $q->whereIn('category_id', $categories);
                }
            })
            ->paginate(12);

        $querystringArray = $request->only(['search', 'min', 'max', 'sort', 'order', 'c']);
        $arProducts->appends($querystringArray);

        return view('catalog', compact('arProducts', 'min', 'max', 'maxdb', 'sort', 'search', 'categoriesSlug'));

    }


    public function loadMore()
    {

    }


    public function show($id)
    {
        $obProduct = Product::findOrFail($id);

        return view('card', compact('obProduct'));
    }

}
