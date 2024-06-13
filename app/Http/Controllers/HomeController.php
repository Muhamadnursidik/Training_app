<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bases\BaseModule;
use App\Modules\Tools\Upload\Service as UploadService;

class HomeController extends BaseModule
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $repo, $rcsarepo, $led, $coachingRepository;

    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    { 
        return $this->serveView([],'dashboard');
    }
    
    public function usermanual(Request $request){
        $url = (new UploadService)->getViewSign(['id' => auth()->user()->roles[0]->id]);

        return $this->serveView(compact('url'),'pages.home.pdf');
    }

    public function video(Request $request){
        // $file = Storage::disk('public')->url('led/usermanual/usermanual.mp4');
        // if (Storage::exists('public/led/usermanual/usermanual.mp4')) {
        //     return $this->serveView(compact('file'),'pages.home.video');
        // }
        abort(404);
    }
}
