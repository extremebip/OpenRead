<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Modules\IHomeService;

class HomeController extends Controller
{
    private $homeService;

    public function __construct(IHomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index()
    {
        $topPicks = $this->homeService->GetTopPicks();
        return view('home', $topPicks->toArray());
    }

    public function search(Request $request)
    {
        $search = $request->query('q');
        if (is_null($search))
            abort(404);

        $page = $request->query('p');
        $result = $this->homeService->Search($search, $this->ValidatePageParam($page));
        if (is_null($result))
            abort(404);

        return view('search', $result);
    }

    private function ValidatePageParam($page)
    {
        if (!is_int($page))
            return 1;
        return $page;
    }

    public function genre($genre_id = null)
    {
        if (is_null($genre_id))
            abort(404);

        $result = $this->homeService->GetStoryAndProfileByGenre($genre_id);
        if (is_null($result))
            abort(404);

        return view('genre', $result);
    }
}
