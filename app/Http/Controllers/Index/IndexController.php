<?php

namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Visit;
use App\Models\Report;


class IndexController extends Controller
{    
    /**
     * index
     *
     * @return view
     */
    public function index()
    {

        $recommande = Article::select('articles.id', 'articles.user_id', 'articles.name', 'articles.price', 'articles.description', 'articles.category', 'images.id as imageId', 'images.path', 'images.main')
        -> join('images', 'images.article_id', '=', 'articles.id') 
        -> where("main", "=", 1)
        ->inRandomOrder() 
        ->take(4)
        ->get();  

        $productCounts = [];

        $categoriesWithCounts = Article::select("category", DB::raw('COUNT(*) as count'))
        ->groupBy("category")
        ->get();

        foreach ($categoriesWithCounts as $count) {
            $productCounts[] = [
                "category" => $count->category,
                "count" => $count->count
            ];
        }

        $today = Carbon::today();
        Visit::create([
            'visit_date' => $today,
            'visit_count' => 1
        ]);

        return view('index.index',compact('productCounts','recommande'));
    }
    
    /**
     * category
     *
     * @param  $category
     * @return view
     */
    public function category($category)
    {

        $articles = Article::select('articles.id', 'articles.user_id', 'articles.name', 'articles.price', 'articles.description', 'articles.category', 'images.id as imageId', 'images.path', 'images.main')
        -> join('images', 'images.article_id', '=', 'articles.id') 
        -> where("main", "=", 1) 
        ->where('category', $category)
        ->get()
        ->toArray();  
          
          return view('index.catalogCategory', compact('articles','category'));
    }
    
    /**
     * bestSales
     *
     * @return view
     */
    public function bestSales()
    {
        $articles = Article::where([
            ["bestSell", "=", true],
        ])->get();
          
        return view('index.catalog', compact('articles'));
    }
    
    /**
     * topOffers
     *
     * @return view
     */
    public function topOffers()
    {
        $articles = Article::where([
            ["topOffer", "=", true],
        ])->get();
          
        return view('index.catalog', compact('articles'));
    }
    
    /**
     * dashboard
     *
     * @return view
     */
    public function dashboard()
    {
        $today = Carbon::now();

        $articles = Article::count();
        $customers = User::count();
        $orders = Order::whereDate('date', $today)->distinct('reference')->count('reference');

        $ordersDay = Order::whereDate('date', $today)->get();
        $sales = 0;
        foreach ($ordersDay as $order) {
            $productProfit = $order->price * $order->quantity;
            $sales += $productProfit;
        }

        $reports = Report::whereDate('date', $today)->count();

        $categories = Order::select('category', DB::raw('COUNT(*) as count'))
        ->groupBy('category')
        ->orderByDesc('count')
        ->get();

        $topCategories = $categories->sortByDesc('count')->take(4)->pluck('category')->toArray();

        $topSales = $categories->take(4)->pluck('count')->toArray();

        // Liste des jours de la semaine dans l'ordre
        $daysOfWeekOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Initialiser le tableau avec des valeurs nulles pour chaque jour de la semaine
        $visitsByDayOfWeek = array_fill_keys($daysOfWeekOrder, 0);

        // Récupérer la date de début de la semaine en cours (dimanche)
        $startOfWeek = Carbon::now()->startOfWeek();

        // Récupérer la date de fin de la semaine en cours (samedi)
        $endOfWeek = Carbon::now()->endOfWeek();

        // Récupérer les visites pour la semaine en cours en regroupant par jour de la semaine
        $visits = Visit::whereBetween('visit_date', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE_FORMAT(visit_date, "%W") as day_of_week, SUM(visit_count) as visit_count')
            ->groupBy('day_of_week')
            ->get();

        // Remplir le tableau avec les valeurs réelles à partir de la base de données
        foreach ($visits as $visit) {
            $visitsByDayOfWeek[$visit->day_of_week] = $visit->visit_count;
        }

        // Extraire uniquement les valeurs (nombre de visites) sous forme d'un tableau
        $visitors = array_values($visitsByDayOfWeek);

        

        // Liste des jours de la semaine dans l'ordre
        $daysOfWeekOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Initialiser le tableau avec des valeurs nulles pour chaque jour de la semaine
        $ordersByDayOfWeek = array_fill_keys($daysOfWeekOrder, 0);

        // Récupérer la date de début de la semaine en cours (dimanche)
        $startOfWeek = Carbon::now()->startOfWeek();

        // Récupérer la date de fin de la semaine en cours (samedi)
        $endOfWeek = Carbon::now()->endOfWeek();

        // Récupérer les commandes pour la semaine en cours en regroupant par jour de la semaine
        $ordersWeek = DB::table('orders')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE_FORMAT(date, "%W") as day_of_week, COUNT(DISTINCT reference) as order_count')
            ->groupBy('day_of_week')
            ->get();

        // Remplir le tableau avec les valeurs réelles à partir de la base de données
        foreach ($ordersWeek as $order) {
            $ordersByDayOfWeek[$order->day_of_week] = $order->order_count;
        }

        // Extraire uniquement les valeurs (nombre de commandes) sous forme d'un tableau
        $orderCounts = array_values($ordersByDayOfWeek);


        return view('index.dashboard', compact('articles','orders','customers','sales','reports','topSales','topCategories','orderCounts','visitors'));
    }

}
