<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json(['products' => $products],200);
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            $imagemNome = Str::random(32).".".$request->imagem->getClientoriginalExtension();

            Product::create([
                'name' => $request->name,
                'imagem' => $imagemNome,
                'description' => $request->description
            ]);

            Storage::disk('public')->put($imagemNome, file_get_contents($request->imagem));

            return response()->json([
                'message' => 'Produto criado com sucesso!'
            ], 201);


        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Algo deu errado!'
            ],500);
        }
    }

    public function show(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' =>'Produto não encontrado'
            ],400);
        } else {
            return response()->json([
                'produto' => $product
            ], 200);
        }

    }

    public function update(Request $request, string $id)
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produto não encontrado'
                ], 404);
            }

            $product->name = $request->name;
            $product->description = $request->description;

            if ($request->imagem) {
                $storage = Storage::disk('public');

                if ($storage->exists($product->imagem))
                    $storage->delete($product->imagem);

                $imagemNome = Str::random(32).".".$request->imagem->getClientOriginalExtension();

                $storage->put($imagemNome, file_get_contents($request->imagem));
            }

            $product->save();

            return response()->json([
                'message' => 'Produto atualizado com sucesso!'
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Algo deu errado!   '."".$e
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produto não encontrado!'
            ], 404);
        }

        $storage = Storage::disk('public');

        if ($storage->exists($product->imagem))
            $storage->delete($product->imagem);

        $product->delete();

        return response()->json([
            'message' => 'Produto deletado com sucesso!'
        ], 200);
    }
}
