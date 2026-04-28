<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'required|url',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|url',
            'order' => 'integer',
        ]);

        Slider::create($request->all());

        return redirect()->route('sliders.index')->with('success', 'Slider created successfully!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'required|url',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|url',
            'order' => 'integer',
        ]);

        $slider->update($request->all());

        return redirect()->route('sliders.index')->with('success', 'Slider updated successfully!');
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully!');
    }

    public function toggle(Slider $slider)
    {
        $slider->is_active = !$slider->is_active;
        $slider->save();
        
        return redirect()->route('sliders.index')->with('success', 'Slider status updated!');
    }
}