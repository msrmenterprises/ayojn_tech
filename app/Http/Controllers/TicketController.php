<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index() {
        $tickets = Ticket::with('comments')->where('user_id',Auth::id())->orderBy('id','desc')->get();
        return view('tickets.index', compact('tickets'));
    }


    public function adminindex() {
        $tickets = Ticket::with('comments')->get();
        $assignUsers = User::where('role_id', 2)->get();
        return view('tickets.adminindex', compact('tickets', 'assignUsers'));
    }

    public function create() {
        return view('tickets.create');
    }
    public function store(Request $request) {

        $request->validate([
        'subject' => 'required|string|max:255',
         'g-recaptcha-response' => 'required|captcha',
       // 'description' => 'required|string',
        'category' => 'required|string',
        'subcategory' => 'required|string',
        'priority' => 'required|string',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

         $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('attachments'), $filename);
            $attachmentPath = 'attachments/' . $filename;
        }


       $ticket = Ticket::create([
        'subject' => $request->subject,
        'description' => $request->description,
        'category' => $request->category,
        'subcategory' => $request->subcategory,
        'priority' => $request->priority,
        'user_id' => Auth::id(),
        'status' => 'New',
        'attachment' => $attachmentPath
    ]);

       //notification
        $userDetails = User::find(Auth::id());
        $url = url('tickets/show') . '/' . $ticket->id;
        $linkName = '<a href="' . $url . '">Click here</a>';
        $message = "New Ticket Created:# ".$ticket->id.", please " . $linkName . " to view the details.";
        CommonHelper::sendNotification($userDetails, $message, $url);


        //email
        Mail::send('/mail/Ticket', ['user' => $userDetails,'code'=> $request->subject], function ($m) use ($userDetails) {
                $m->to([$userDetails->email,'msrmenterprises17@gmail.com'])->subject("Ticket Generated")->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            if(count(Mail::failures()) > 0 ) {
              $rst = false;
            } else {
              $rst = true;
           }

        return redirect()->route('ticket');
    }

    
    public function show($id) {
        $ticket = Ticket::with('comments')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function adminshow($id) {
        $ticket = Ticket::with('comments')->findOrFail($id);
        return view('tickets.adminshow', compact('ticket'));
    }

    public function assign(Request $request,$id) {
       // echo "assign function called for ticket ID: " . $request->assign_id; die;
        $ticket = Ticket::findOrFail($id);
       
        $ticket->assign_id = $request->assign_id;
        $ticket->status = 'In Progress';
        $ticket->save();
        return back();
    }
    public function addComment(Request $request, $id) {
        TicketComment::create([
            'ticket_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);


        $ticket = Ticket::where('id', $id)->get()->first();;
        $userDetails = User::find($ticket->assign_id);

        if($userDetails){
        //email
        Mail::send('/mail/TicketComment', ['user' => $userDetails,'code'=> $request->comment], function ($m) use ($userDetails) {
            $m->to([$userDetails->email,'rambthm376@gmail.com'])->subject("Ticket Reply")->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
        });

        if(count(Mail::failures()) > 0 ) {
            $rst = false;
        } else {
            $rst = true;
        }
    }


        return back();
    }

      public function adminaddComment(Request $request, $id) {


        TicketComment::create([
            'ticket_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        $ticket = Ticket::where('id', $id)->get()->first();;
        $userDetails = User::find($ticket->user_id);

        //email
        Mail::send('/mail/TicketComment', ['user' => $userDetails,'code'=> $request->comment], function ($m) use ($userDetails) {
            $m->to([$userDetails->email,'rambthm376@gmail.com'])->subject("Ticket Reply")->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
        });

        if(count(Mail::failures()) > 0 ) {
            $rst = false;
        } else {
            $rst = true;
        }


        return back();
    }

    public function resolve($id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'Resolved';
        $ticket->save();
        return back();
    }
    public function close($id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'Closed';
        $ticket->save();
        return back();
    }
    public function reopen($id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'Reopen';
        $ticket->save();
        return back();
    }


    public function upload(Request $request)
{
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Define public path
        $destinationPath = public_path('uploads');

        // Create the folder if it doesn't exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Move file to public/uploads
        $file->move($destinationPath, $filename);

        //print_r(asset('uploads/' . $filename)); exit;

         $fileUrl = asset('uploads/' . $filename);

        return response()->json([
            'uploaded' => true,
            'url' => $fileUrl,
            'default' => $fileUrl
        ]);
    }

    return response()->json(['error' => 'No file uploaded.']);
}
    



}