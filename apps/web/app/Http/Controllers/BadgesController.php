<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Tienda de placas (reemplaza legacy/badges.php). Es una galería/tienda de solo
 * lectura: lista las placas en venta con su imagen, precio y enlace de compra,
 * tal cual el legacy las definía en badge_config.php. La sección pertenece a la
 * TIENDA (pageid="bshop"), por lo que no lleva barra de navegación de segundo
 * nivel (navi2).
 */
class BadgesController extends Controller
{
    /**
     * Placas en venta. Réplica fiel de legacy/badge_config.php (badge_N, priceN,
     * badge_imageN). Las imágenes viven en /Badges/*.gif (public/Badges).
     *
     * @var list<array{name: string, price: string, image: string, link: string}>
     */
    private const BADGES = [
        ['name' => 'HC2', 'price' => '0', 'image' => '/Badges/HC2.gif', 'link' => '/badge_1.php'],
        ['name' => 'DK1', 'price' => '45', 'image' => '/Badges/DK1.gif', 'link' => '/badge_2.php'],
        ['name' => 'LBB', 'price' => '50', 'image' => '/Badges/LBB.gif', 'link' => '/badge_3.php'],
        ['name' => 'FRE', 'price' => '90', 'image' => '/Badges/FRE.gif', 'link' => '/badge_4.php'],
    ];

    public function index(): View
    {
        return view('badges.index', ['badges' => self::BADGES]);
    }
}
