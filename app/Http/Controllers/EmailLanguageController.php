<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailLanguage;
use App\Models\BusinessSetting;


class EmailLanguageController extends Controller
{
	public function index(Request $request)
	{
		$sort_search = null;
		$langs = EmailLanguage::orderByDesc('id');
		if ($request->search != null){
			$sort_search = $request->search;
            $langs = $langs->where('name', 'like', '%'.$sort_search.'%')->orWhere('subject', 'like', '%'.$sort_search.'%'); 
        }

		$langs = $langs->paginate(15);
		return view('backend.email_settings.index', compact('langs','sort_search'));
	}

	public function edit($id)
	{
		$lang = EmailLanguage::findOrFail($id);
		return view('backend.email_settings.edit', compact('lang'));
	}


	public function sendPreviewEmail(Request $request)
    {
	    $templateId = $request->id;
	    if(sendPreviewEmail($templateId))
        {
        	return response()->json(['success' => true]);
        }
        else
        {
           return response()->json(['success' => false]);
        }
	   
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'subject' => 'required',
			'message' => 'required'
		]);
		$lang = EmailLanguage::findOrFail($id);
		$lang->update($request->all());
		flash(translate('Updated Successfully'))->success();
		return redirect()->route('email.language.edit', $lang->id);
	}

	public function GenSettingEmail()
	{
		$type = 'general_email_template';
		$general_email = BusinessSetting::where('type', $type)->value('value');
		return view('backend.email_settings.general_email',compact('general_email'));
	}

	public function UpdateGenEmail(Request $request){
		$type = 'general_email_template'; 
		$newValue = $request->message;
		BusinessSetting::where('type', $type)->update(['value' => $newValue]);

		flash(translate("Settings updated successfully"))->success();
		return back();
	}

	public function create()
	{
		return view('backend.email_settings.create');
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'code' => 'required',
			'subject_en' => 'required',
			'subject_br' => 'required',
			'subject_jp' => 'required',
			'message_en' => 'required',
			'message_br' => 'required',
			'message_jp' => 'required',
		]);

		$data = $request->all();

		$languages = ['en', 'br', 'jp'];

		foreach ($languages as $lang) {
			$emailLanguage = new EmailLanguage();
			$emailLanguage->name = $data['name'];
			$emailLanguage->code = $data['code'];
			$emailLanguage->lang = $lang;
			$emailLanguage->subject = $data['subject_' . $lang];
			$emailLanguage->message = $data['message_' . $lang];

        $emailLanguage->save(); 
    }
    flash(translate("Added successfully"))->success();
    return redirect()->route('email.language.index');
}
}

