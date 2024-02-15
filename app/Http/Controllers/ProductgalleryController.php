<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductGalleryRequest;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductgalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        if (request()->ajax())
        {
            $query = ProductGallery::query();
            return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                    <form class="inline-block" action="'. route('dashboard.gallery.destroy', $item->id). '" method="post">
                        <button class="bg-red-500 text-white rounded-md px-2 py-1 m-2">
                            Hapus
                        </button>
                    '. method_field('delete') . csrf_field() .'
                    </form>
                ';
            })            

            ->editColumn('url', function ($item){
                return '<img style="max-width: 120px" src="'. Storage::url($item->url).'">';
            })

            ->editColumn('is_featured', function ($item){
                return $item->is_featured ? 'Yes' : 'No';
            })
            ->rawColumns(['action','url'])
            ->make();
        }
        return view('pages.dashboard.gallery.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
{
    return view('pages.dashboard.gallery.create', compact('product'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductGalleryRequest $request, Product $product)
{
    logger($request->all()); // log data yang dikirimkan
    logger($request->file('files')); // log file yang dikirimkan

    $files = $request->file('files');

    if ($request->hasFile('files')) {
        foreach ($files as $file) {
            $path = $file->store('public/gallery');

            ProductGallery::create([
                'products_id' => $product->id,
                'url' => $path
            ]);
        }
    }

    return redirect()->route('dashboard.product.gallery.index', $product->id);
}



    /**
     * Display the specified resource.
     */
    public function show(ProductGallery $gallery)
    {
        return view('pages.dashboard.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductGallery $gallery)
    {
        return view('pages.dashboard.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductGallery $gallery)
{
    $request->validate([
        'is_featured' => 'required|boolean',
    ]);

    $gallery->update([
        'is_featured' => $request->is_featured,
    ]);

    return redirect()->route('dashboard.product.gallery.index', $gallery->product_id);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductGallery $gallery)
    {
        $gallery->delete();

        return redirect()->route('dashboard.product.gallery.index', $gallery->products_id);
    }
}
