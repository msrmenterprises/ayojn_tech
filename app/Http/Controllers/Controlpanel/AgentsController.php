<?php
namespace App\Http\Controllers\Controlpanel;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class AgentsController extends Controller
{
    public function index(Request $request)
    {
        $client = new Client();

        $agentId = Auth::user()->id;

        $url = str_replace('{agent_id}', $agentId, config('sitevars.agent.all_tickets'));

        $response = $client->get(
        $url,
        [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]
    );

    $data = json_decode($response->getBody(), true);

    $tickets = $data['tickets'] ?? [];

       return view('controlpanel.agents.index', compact('tickets'));
    }

    public function show($id)
    {
    $client = new Client();

    $url = str_replace('{id}', $id, config('sitevars.agent.ticket_details'));

    $response = $client->get(
        $url,
        [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]
    );

    $data = json_decode($response->getBody(), true);

    // Convert messages & parse dates
    $messages = collect($data['messages'] ?? [])->map(function ($message) {
        $message['created_at'] = Carbon::parse($message['created_at']);
        return $message;
    });

    // Ticket header info (first message)
    $ticket = $messages->first();

    return view('controlpanel.agents.show', compact('ticket', 'messages'));
    }


    public function reply(Request $request, $id)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $client = new Client();

    $agentId = Auth::user()->id;

    $url = str_replace('{id}', $id, config('sitevars.agent.ticket_reply'));

    try {
        $client->post(
            $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message'  => $request->message,
                    'agent_id' => $agentId,
                ]
            ]
        );

        return redirect()->back()->with('success', 'Reply sent successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to send reply');
    }
}

}