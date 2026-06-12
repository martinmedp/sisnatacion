<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function inicio()
    {
        return view('website.inicio');
    }
    public function nosotros()
    {
        return view('website.nosotros');
    }
    public function contacto()
    {
        return view('website.contacto');
    }
    public function admisiones()
    {
        return view('website.admisiones');
    }
    public function noticias()
    {
        return view('website.noticias');
    }
    public function galeria()
    {
        return view('website.galeria');
    }
}
