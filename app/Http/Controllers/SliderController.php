<?php

namespace App\Http\Controllers;

use Auth;
use Artisan;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Exports\SliderExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sliders = Slider::orderBy('position', 'asc')->paginate(15);
        return view('backend.slider.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website_settings.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'slider_image' => 'required|image|mimes:jpg,jpeg,png,webp',
            'position' => 'required',
            'status' => 'required',
            'link' => 'nullable|url',

            'start_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // If end_date is provided but start_date is not, it will fail
                    if ($request->end_date && !$value) {
                        $fail('The start date is required when the end date is provided.');
                    }

                    // If start_date is provided but end_date is not, it will fail
                    if ($value && !$request->end_date) {
                        $fail('The end date is required when the start date is provided.');
                    }
                },
            ],

            'end_date' => [
                'nullable',
                'date',
                'after:start_date', // end_date must be after start_date if both are present
                function ($attribute, $value, $fail) use ($request) {
                    // If start_date is provided but end_date is not, it will fail
                    if ($request->start_date && !$value) {
                        $fail('The end date is required when the start date is provided.');
                    }

                    // If end_date is provided but start_date is not, it will fail
                    if ($value && !$request->start_date) {
                        $fail('The start date is required when the end date is provided.');
                    }
                },
            ],
        ]);

        $image = Image::make($request->file('slider_image')->getRealPath());
        if ($image->width() !== 1180 || $image->height() !== 627) {
            return back()->withErrors(['slider_image' => 'The slider image must be 1180x627 pixels.']);
        }
        $filePath = '';

        if ($request->hasFile('slider_image')) {
            $file = $request->file('slider_image');
            $fileName = uniqid('img') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('slider_img', $fileName, 'public');
        }

        $slider = new Slider;

        $slider->image = $filePath;
        $slider->position = $request->position;
        $slider->status = $request->status;
        $slider->link = $request->link;
        $slider->start_date = $request->start_date;
        $slider->end_date = $request->end_date;
        // dd($slider);
        $slider->save();

        flash(translate('slider has been created successfully'))->success();
        return redirect()->route('website.slider');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit($id)
{

    // Retrieve the slider from the databasea
    $slider = Slider::findOrFail($id);

    // Pass the slider data to the view
    return view('backend.slider.edit-slider', compact('slider'));
}
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, $id)
{
    $request->validate([
        'slider_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'position' => 'required',
        'status' => 'required',
        'link' => 'nullable|url',
        'start_date' => [
            'nullable',
            'date',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->end_date && !$value) {
                    $fail('The start date is required when the end date is provided.');
                }
                if ($value && !$request->end_date) {
                    $fail('The end date is required when the start date is provided.');
                }
            },
        ],
        'end_date' => [
            'nullable',
            'date',
            'after:start_date',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->start_date && !$value) {
                    $fail('The end date is required when the start date is provided.');
                }
                if ($value && !$request->start_date) {
                    $fail('The start date is required when the end date is provided.');
                }
            },
        ],
    ]);

    // Retrieve the slider record
    $slider = Slider::findOrFail($id);

    // Check and validate image dimensions if a new image is uploaded
    if ($request->hasFile('slider_image')) {
        $image = Image::make($request->file('slider_image')->getRealPath());
        if ($image->width() !== 1180 || $image->height() !== 627) {
            return back()->withErrors(['slider_image' => 'The slider image must be 1180x627 pixels.']);
        }

        // Delete old image if it exists using the correct disk call
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        // Save new image
        $file = $request->file('slider_image');
        $fileName = uniqid('img') . '.' . $file->getClientOriginalExtension();
        $slider->image = $file->storeAs('slider_img', $fileName, 'public');
    }

    // Update the slider fields
    $slider->position   = $request->position;
    $slider->status     = $request->status;
    $slider->link       = $request->link;
    $slider->start_date = $request->start_date;
    $slider->end_date   = $request->end_date;

    // Save the updated slider record
    $slider->save();

    flash(translate('Slider has been updated successfully'))->success();
    return redirect()->back();
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        Slider::destroy($id);
        flash(translate('Slider has been deleted successfully'))->success();
        return redirect()->route('website.slider');

    }

    public function status(Request $request)
    {
        $slider = Slider::findOrFail($request->id);
        $slider->status = $request->status;
        if ($slider->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function export()
    {
        return Excel::download(new SliderExport, 'Slider_Data.xlsx');
    }
}
