<?php
namespace App\Http\Controllers\ControlpanelAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        return view('controlpanel/faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        Faq::create($request->only('question', 'answer'));
        return redirect()->back()->with('success', 'FAQ added!');
    }

    public function delete($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'FAQ deleted!');
    }

    public function update(Request $request)
{
    $faq = Faq::find($request->id);
    if ($faq) {
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return response()->json(['status' => true, 'message' => 'FAQ updated!']);
    }
    return response()->json(['status' => false, 'message' => 'FAQ not found!']);
}
}