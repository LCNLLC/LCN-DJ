<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;

class FAQController extends Controller
{
    public function __construct() {
        // Staff Permission Check
       $this->middleware(['permission:faq'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $faqs = FAQ::orderByDesc('id')->paginate(10);


        return view('backend.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.faq.create');
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
            'artical_code' => 'required',
            'subject_en' => 'required',
            'subject_br' => 'required',
            'subject_jp' => 'required',
            'description_en' => 'required',
            'description_br' => 'required',
            'description_jp' => 'required',
        ]);

        $data = $request->all();

        $languages = ['en', 'br', 'jp'];

        foreach ($languages as $lang) {
            $faq = new FAQ();
            $faq->artical_code = $data['artical_code'];
            $faq->lang = $lang;
            $faq->subject = $data['subject_' . $lang];
            $faq->description = $data['description_' . $lang];

        $faq->save(); 
    }

        flash(translate('Record has been inserted successfully'))->success();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit(Request $request, $id)
     {
         $lang  = $request->lang;
         $faq  = FAQ::findOrFail($id);

         return view('backend.faq.edit', compact('faq', 'lang'));
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
        $faq = FAQ::findOrFail($id);

        $faq->subject = $request->subject;
        $faq->description = $request->description;

        $faq->save();

        flash(translate('Record has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        FAQ::destroy($id);

        flash(translate('Record has been deleted successfully'))->success();
        return redirect()->route('faq.index');
    }

    public function updateStatus(Request $request){
        $faq = FAQ::findOrFail($request->id);
        $faq->status = $request->status;
        $faq->save();

        return 1;
    }
}
