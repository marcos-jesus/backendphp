<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::all();

        if ($cart) {
            return response()->json(['cart' => $cart], 200);
        }
    }

    public function store(Request $request,)
    {
        try {

            $product = Product::find($request->id);

            if ($product) {
                Cart::create([
                    'name' => $request->name,
                    'imagem' => $request->imagem,
                    'description' => $request->description,
                ]);

                return response()->json([
                    'message' => 'Adicionado ao Carrinho!'
                ], 201);
            }

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Algo deu errado!'
            ],500);
        }
    }

    public function destroy(string $id)
    {
        $product = Cart::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Item não encontrado!'
            ], 404);
        }

        $product->delete();

        return response()->json(['Item removido do carrinho!'], 200);


    }
}
