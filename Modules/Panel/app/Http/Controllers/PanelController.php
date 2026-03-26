<?php

namespace Modules\Panel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index()
    {
        return view('panel::index');
    }

    public function create()
    {
        return view('panel::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('panel::show');
    }

    public function edit($id)
    {
        return view('panel::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
