<?php

namespace App\Http\Controllers\UserAuth;

use App\Country;
use App\Http\Controllers\Controller;
use App\Industry;
use App\Models\Attendee;
use App\Models\Event;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    //
    public function index(Request $request)
    {
        $continents = [
            'Asia' => DateTimeZone::ASIA,
            'Africa' => DateTimeZone::AFRICA,
            'America' => DateTimeZone::AMERICA,
            'Antarctica' => DateTimeZone::ANTARCTICA,
            'Arctic' => DateTimeZone::ARCTIC,
            'Atlantic' => DateTimeZone::ATLANTIC,
            'Australia' => DateTimeZone::AUSTRALIA,
            'Europe' => DateTimeZone::EUROPE,
            'Indian' => DateTimeZone::INDIAN,
            'Pacific' => DateTimeZone::PACIFIC
        ];
        $newTimeZone = [];
        foreach ($continents as $continent => $mask) {
            $timezones = DateTimeZone::listIdentifiers($mask);

            // start optgroup tag

            // create option tags
            foreach ($timezones as $timezone) {
                $newTimeZone[] = $this->formatTimezone($timezone, $continent, true);
            }

            // end optgroup tag
        }
        $myEvents = Event::with('attendes')->where('user_id', Auth::user()->id)->get();


        $othersEvents = Event::with(['organizer', 'checkAttendes'])->where('user_id', '!=', Auth::user()->id)->where('status', 1)->get();

        $industryLists = Industry::all();
        $countries = Country::all();
        return view('events.index', compact('newTimeZone', 'myEvents', 'othersEvents', 'industryLists', 'countries'));
    }

    protected function formatTimezone($timezone, $continent, $htmlencode = true)
    {
        $var = ' - ';
        $time = new DateTime(null, new DateTimeZone($timezone));
        $offset = $time->format('P');

        if ($htmlencode) {
            $offset = str_replace('-', ' &minus; ', $offset);
            $offset = str_replace('+', ' &plus; ', $offset);
        }

//        $timezone = substr($timezone, strlen($continent) + 1);
//        $timezone = str_replace('St_', 'St. ', $timezone);
//        $timezone = str_replace('_', ' ', $timezone);

        $formatted = '(GMT/UTC' . $offset . ')' . ($htmlencode ? $var : ' ') . $timezone;
        return $formatted;
    }

    public function addEvent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'event_title' => "required",
            'event_date' => "required",
            'timezone' => "required",
            'event_type' => "required",
            'event_free_paid' => "required",
        ], [
            'event_title.required' => "Please enter event title",
            'event_date.required' => "Please enter event date",
            'timezone.required' => "Please select timezone",
            'event_type.required' => "Please select event type",
            'event_free_paid.required' => "Please select event paid or free",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])
                ->setStatusCode(200);
        }

        if (($request->event_type == 'Physical' || $request->event_type == 'Hybrid') && empty($request->event_location)) {
            return response()->json(['status' => 0, 'message' => 'Please enter location'])
                ->setStatusCode(200);
        }

        if (($request->event_free_paid == 'Paid') && empty($request->event_fee)) {
            return response()->json(['status' => 0, 'message' => 'Please enter fee'])
                ->setStatusCode(200);
        }
        $eventData = [
            'user_id' => Auth::user()->id,
            'event_title' => $request->event_title,
            'event_date' => $request->event_date,
            'event_finish' => $request->event_finish,
            'industry_focus' => $request->industry_focus,
            'geo_focus' => $request->geo_focus,
            'timezone' => $request->timezone,
            'event_type' => $request->event_type,
            'event_location' => $request->event_location,
            'event_free_paid' => $request->event_free_paid,
            'event_fee' => $request->event_fee,
            'payment_link' => $request->payment_link,
            'voucher_code' => $request->voucher_code,
            'status' => 0,
        ];
        $event = Event::create($eventData);
        $event->share_id = $this->uniquiShareId($event->id);
        $event->save();

        if (!empty($event)) {
            return response()->json(['status' => 1, 'message' => 'This event is pending for approval.'])
                ->setStatusCode(200);
        } else {
            return response()->json(['status' => 0, 'message' => 'Something went wrong'])
                ->setStatusCode(200);
        }

    }

    public function shareEvent($id)
    {
        $othersEvents = Event::with(['organizer'])->where('share_id', $id)->where('status', 1)->get();
        if (!empty($othersEvents->first())) {
            session()->flash('url.intended', '/' . request()->path());

            return view('events.share-event', compact('othersEvents'));
        } else {
            return view('events.datanotfound');
        }
    }

    public function attendeesList(Request $request, $eventId)
    {
        $eventId = base64_decode($eventId);
        $event = Event::where('id', $eventId)->where('user_id', Auth::user()->id)->first();
        if (empty($event)) {
            return redirect('events');
        }
        $attendeedList = Attendee::with(['user'])->where('event_id', $eventId)->get();

        return view('events.attendees-list', compact('attendeedList', 'eventId'));
    }

    public function changeStatusAttendees($id)
    {
        $attendeedList = Attendee::where('id', $id)->first();
        if ($attendeedList->status == 1) {
            $attendeedList->status = 0;
        } else {
            $attendeedList->status = 1;
        }
        $attendeedList->save();
        return json_encode(['status' => 1, 'msg' => trans("status has been updated successfully")]);
    }

    public function attendEvent(Request $request, $event)
    {
        $eventData = Event::where('id', $event)->first();
        if (!empty($eventData)) {
            Attendee::updateOrCreate(['user_id' => Auth::user()->id, 'event_id' => $eventData->id, 'status' => 0]);
            return json_encode(['status' => 1, 'msg' => trans("Request successfully sent")]);
        } else {
            return json_encode(['status' => 0, 'msg' => trans("Event not found")]);
        }


    }

    public function cancelEvent(Request $request, $attendeeId)
    {
        $attendeeData = Attendee::where('id', $attendeeId)->first();
        if (!empty($attendeeData)) {
            $attendeeData->status = 2;
            $attendeeData->save();
            return json_encode(['status' => 1, 'msg' => trans("Request successfully sent")]);
        } else {
            return json_encode(['status' => 0, 'msg' => trans("Attendee not found")]);
        }


    }

    public function uniquiShareId($id)
    {
        return md5(uniqid(rand() . $id, true));
    }

    public function ExportAttendees(Request $request, $id)
    {
        $records = Attendee::with('user')->where('event_id', $id)->get();

        foreach ($records as $key => $record) {
            $data[$key]['User Email'] = (!empty($record->user)) ? $record->user->email : '-';
            $data[$key]['User Entity'] = (!empty($record->user)) ? $record->user->entity : '-';
            $data[$key]['User Phone'] = (!empty($record->user)) ? $record->user->phone_no : '-';
            if ($record->status == 0) {
                $data[$key]['Status'] = 'Pending';
            } else if ($record->status == 1) {
                $data[$key]['Status'] = 'Approved';
            } else if ($record->status == 2) {
                $data[$key]['Status'] = 'Cancel';
            }
        }
        $event = Event::find($id);

        return Excel::create('Attandees', function ($excel) use ($data) {
            $excel->sheet('Attandees', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }


}
