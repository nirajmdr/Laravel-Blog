<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
      $title = 'Welcome to my blog website!';
      //return view ('pages.index', compact('title'));
      return view('pages.index')->with('title', $title);
    }

    public function about() {
      return view('pages.about');
    }

    public function services() {
      $data = array(
        'title' => 'Our Services',
        'services' => ['Web Design', 'Web Development', 'PHP', 'WordPress'],
      );
      return view ('pages.services')->with($data);
    }
}
