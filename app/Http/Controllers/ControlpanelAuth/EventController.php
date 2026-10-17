<?php

namespace App\Http\Controllers\ControlpanelAuth;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendee;
use App\Traits\ApiResponse;
use DataTables;
use Excel;
use Illuminate\Http\Request;
use Validator;

class EventController extends Controller
{

    use ApiResponse;

    public function Events()
    {
        return view('controlpanel.events');
    }

    public function ListEvents()
    {
        $bidLists = Event::with('organizer')->get();

        return Datatables::of($bidLists)
            ->addColumn('organizer', function ($response) {
                //dd($response['country_name']);
                $url = url('controlpanel/attendees/') . "/" . base64_encode($response->id);

                $attendee = '&nbsp;&nbsp;<a href="' . $url . '" > <span class="badge badge-light" >' . $response->attendes->count() .  '</span ></a > ';
                if ($response->organizer) {
                    return $response->organizer->entity .$attendee;
                } else {
                    return '-';
                }
            })->addColumn('country_name', function ($response) {
                if ($response->country_name) {
                    return $response->country_name->country_name;
                } else {
                    return '-';
                }
            })->addColumn('industry', function ($response) {
                //dd($response['country_name']);
                if ($response->industry) {
                    return $response->industry->name;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($response) {
                if ($response->status == 1)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')"><span class="label label-success my_lable_class" id="cust_id' . $response->id . '">Approved</span></a>';
                if ($response->status == 2)
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')" ><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Disapproved</span></a>';
                else
                    return '<a  href="javascript:void(0)"  onclick="changeStatus(' . $response->id . ')" ><span class="label label-danger my_lable_class" id="cust_id' . $response->id . '"  >Pending</span></a>';
            })
            ->addColumn('action', function ($response) {
                $edit = '<a href="' . url('controlpanel/edit-event/' . $response->id) . '" title="Edit" class="btn btn-sm btn-primary pull-left"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['organizer', 'status', 'action', 'industry', 'country_name'])
            ->make(true);
    }

    public function eventChangeStatus(Request $request)
    {
        $event = Event::findOrFail($request->event_id);
        if (!empty($event)) {
            if ($event->status == 0) {
                $event->status = 1;
            } else {
                $event->status = 0;
            }
            $event->save();

            return json_encode(['status' => $event->status, 'msg' => trans("Event status has been updated successfully"), 'user_id' => $request->id]);
        } else {
            return json_encode(['err_msg' => 'User id  is required.', 'user_id' => $request->id]);
        }
    }

    public function editEvent($id)
    {
        $user = Event::where('id', $id)->first();

        return view('controlpanel.event-edit', compact('user'));
    }

    public function updateEvent(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->link = $request->link;
        $event->save();

        return redirect('controlpanel/events')->with('success', 'User type has been successfully updated');

    }

    public function attendeesList(Request $request, $eventId)
    {
        $eventId = base64_decode($eventId);
        $attendeedList = Attendee::with(['user'])->where('event_id', $eventId)->get();

        return view('controlpanel.attendee-list', compact('attendeedList'));
    }
}
