<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateNamesRequest;
use App\Services\BusinessNameService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class NameGeneratorController extends Controller
{

    public function __construct(private BusinessNameService $service)
    {
    }

    public function index() : View{
       return view('name-generator.index', [
            'industries' => $this->service->industries(),
        ]);
    }


    public function generate(GenerateNamesRequest $request): View
    {
        $industry = $request->validated('industry');

        return view('name-generator.generate', [
            'industries' => $this->service->industries(),
            'names'      => $this->service->generateNames($request->validated('keywords'), $industry),
            'taglines'   => $this->service->getTaglines($industry),
            'palette'    => $this->service->getBrandPalette($industry),
        ]);
    }
}
