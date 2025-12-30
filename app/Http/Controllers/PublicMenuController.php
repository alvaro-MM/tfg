<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PublicMenuController extends Controller
{
    /**
     * Página pública del menú asociada a una mesa (vía QR)
     */
    public function show(string $token): View
    {
        $table = Table::byQrToken($token)
            ->with(['menu'])
            ->firstOrFail();

        return view('public.menu', compact('table'));
    }

    /**
     * Datos del menú en JSON para la SPA del frontend público
     */
    public function getMenuData(string $token): JsonResponse
    {
        $table = Table::byQrToken($token)
            ->with([
                'menu.dishes.category',
                'menu.dishes.allergens',
            ])
            ->firstOrFail();

        $menu = $table->menu;

        // Si por lo que sea no hay menú asignado, devolvemos platos/bebidas globales disponibles
        if (!$menu) {
            $categories = Category::orderBy('name')->get();

            $dishes = \App\Models\Dish::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get();

            $drinks = \App\Models\Drink::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get();
        } else {
            // Obtener platos del menú asignado
            $dishes = $menu->dishes()
                ->with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get();

            // Obtener todas las bebidas disponibles
            $drinks = \App\Models\Drink::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get();

            // Obtener categorías de los platos del menú y de las bebidas
            $categoryIds = $dishes->pluck('category_id')
                ->merge($drinks->pluck('category_id'))
                ->filter()
                ->unique();

            $categories = Category::whereIn('id', $categoryIds)
                ->orderBy('name')
                ->get();
        }

        return response()->json([
            'table' => [
                'id' => $table->id,
                'name' => $table->name,
                'capacity' => $table->capacity,
            ],
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            }),
            'dishes' => $dishes->map(function ($dish) {
                return [
                    'id' => $dish->id,
                    'name' => $dish->name,
                    'description' => $dish->description,
                    'price' => (float) $dish->price,
                    'image' => $dish->image ? asset('storage/' . $dish->image) : null,
                    'category_id' => $dish->category_id,
                    'allergens' => $dish->allergens->pluck('name')->toArray(),
                ];
            }),
            'drinks' => $drinks->map(function ($drink) {
                return [
                    'id' => $drink->id,
                    'name' => $drink->name,
                    'description' => $drink->description,
                    'price' => (float) $drink->price,
                    'image' => $drink->image ? asset('storage/' . $drink->image) : null,
                    'category_id' => $drink->category_id,
                    'allergens' => method_exists($drink, 'allergens')
                        ? $drink->allergens->pluck('name')->toArray()
                        : [],
                ];
            }),
        ]);
    }
}


