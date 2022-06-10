<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotCreateRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Traits\ApiTrait;
use App\Http\Traits\ImgTrait;
use App\Models\User;
use Carbon\Carbon;

class NotficationController extends Controller
{
    use ApiTrait;
    use ImgTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();
        ///public
        $nots = Notification::where('user_id', $user->id)->where('hide', false)->paginate(pag);
        //  $host = request()->getHttpHost();
        foreach ($nots as $it) {
            $it->subject = html_entity_decode($it->subject);
            $it->subtitle = html_entity_decode($it->subtitle);
            $it = $this->editHasImage($it, 'nots');
        }
        return $this->returnJson($nots);
    }
    public function all()
    {
        $nots = Notification::with(['getUser'])->paginate(pagadmin);

        return $nots;
        // return view(,compact('news'))

    }

    public function allUsers()
    {
        $users = User::paginate(pagadmin);

        return view('Admin.users', compact('users'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();
        return view('Admin.createnot', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotCreateRequest $request)
    {
        $user = User::where('email', $request->useremail)->first();
        if ($request->type == 'Receipt') {


            if (!$user || $user->type_plane != 1) {
                return  redirect()->back()->with(['error' => "Inappropriate user plan"]);
            }
        }
        if ($request->type == 'Appointment') {
            return  $this->setAppointmentFromAdmin($request, $user);
        }
        $file_name = null;
        $not = new Notification();
        $not->title = $request->title;
        $not->type = $request->type;
        $not->appointment = $request->appointment;
        $not->subject = htmlentities($request->subject);
        $not->subtitle = htmlentities($request->subtitle);
        if ($request->file('photo') != null) {
            $file_name = $this->saveImage($request->photo, 'images/nots');
            $not->image = $file_name;
            $not->isImage = true;
        }

        $not->user_id = $user->id;
        $not->save();
        return  redirect()->back()->with(['success' => 'create successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth('api')->user();
        $not = Notification::where('user_id', $user->id)->where('id', $id)->first();


        if ($not) {
            $not->subject = html_entity_decode($not->subject);
            $not->subtitle = html_entity_decode($not->subtitle);
            if (!$not->read) {
                $not->read = true;
                $not->update();
            }
            $not = $this->editHasImage($not, 'nots');
        } else {
            return   $this->returnJson('', 404, 'notfound');
        }
        return  $this->returnJson($not);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::get();
        $not = Notification::where('id', $id)->first();
        if (!$not) {
            return  redirect()->back()->with(['error' => "not found"]);
        }
        $email = User::find($not->user_id)->email;
        $not->email = $email;
        return view('Admin.editnot', compact('not', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NotCreateRequest $request, $id)
    {
        $not = Notification::find($id);
        if (!$not) {
            return  redirect()->back()->with(['error' => "not found"]);
        }
        $user = User::where('email', $request->useremail)->first();
        if ($request->type == 'Receipt') {


            if (!$user || $user->type_plane != 1) {
                return  redirect()->back()->with(['error' => "Inappropriate user plan"]);
            }
        }
        if ($request->type == 'Appointment') {
            return  $this->updateAppointmentFromAdmin($request, $user, $not);
        }
        $not->title = $request->title;
        $not->appointment = $request->appointment;
        $not->type = $request->type;
        $not->subject = htmlentities($request->subject);
        $not->subtitle = htmlentities($request->subtitle);
        if ($request->file('photo') != null) {
            $file_name = $this->saveImage($request->photo, 'images/nots');
            $not->image = $file_name;
            $not->isImage = true;
        }
        $not->user_id = $user->id;

        $not->update();
        return  redirect()->back()->with(['success' => "successfly update"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $not = Notification::find($request->id);
        if (!$not) {

            return response()->json(['status' => false, 'msg' => 'error', $request->id]);
        }
        $not->delete();
        return response()->json(['status' => true, 'msg' => 'successfully deleted', 'id' => $request->id]);
    }

    public function setAppointment(Request $request)
    {
        $not = new Notification();
        $user = auth('api')->user();
        $not->title = 'Ihre Terminbuchung';
        $not->subtitle = '';
        $not->subject = '';
        $not->read = true;
        $request->validate(['appointment' => ['date', 'date_format:Y-m-d H:i']]);

        $appointment =   Carbon::parse($request->appointment)->format('Y-m-d\Th:i');

        $oldnot = Notification::where('user_id', $user->id)->where('isRecipe', false)->whereDate('appointment', Carbon::parse($request->appointment)->format('Y-m-d'))->first();
        //Check if the user has an appointment on the same day
        if ($oldnot) {
            return $this->returnJson('', 404, 'Please choose a suitable time, you already have an appointment on this day');
        }
        $def = Carbon::parse($request->appointment)->diff(Carbon::now());
        //check if the 
        if ($appointment <= Carbon::now()) {
            return $this->returnJson('', 404, 'Please choose a suitable datetime ');
        }

        if ($def->days < 1 && $def->h < 24) {
            //   return $this->returnJson($def);
            return $this->returnJson(404, 'Sorry, you cannot take this appointment because the time remaining is less than 24 hours');
        }
        $not->appointment =  Carbon::parse($appointment)->format('Y-m-d\Th:i');
        $not->user_id = $user->id;
        $subtitle = "<p>Hallo $user->gender $user->name,</p><p><span style='font-size: 1rem;'>vielen Dank für Ihre Terminbuchung am:</span></p>";
        $sub = '<p>Falls Ihnen unerwartet etwas dazwischen kommt,<br>können

        Sie hier Ihren Termin umbuchen oder absagen bis zu 24<br>Stunden vor Ihrem Besuch.</p>
        
        <p><span style="font-size: 1rem;">Ihr Hausärzte-Team</span></p><p><span style="color: rgb(57, 123, 33); font-size: 1rem;">Gemeinscha spraxis Dr.med. Rechenberg<br>und Schäfer</span></p><p><span style="font-size: 1rem;"><font color="#397b21" style="">
        
        </font></span></p><p>RiHergasse 5<br>97877 Wertheim<br>Tel:&nbsp; &nbsp;+49 (0) 9342 6101</p><p>
        
                            
        
                        </p>';
        $not->subtitle = htmlentities($subtitle);
        $not->subject = htmlentities($sub);
        $success = $not->save();
        if ($success) {
            $not = Notification::find($not->id);
            $not->subject = html_entity_decode($not->subject);
            $not->subtitle = html_entity_decode($not->subtitle);
            return $this->returnJson($not);
        }
        return $this->returnJson('', 404);
    }
    public function setAppointmentFromAdmin(Request $request, User $user)
    {
        $not = new Notification();

        $not->title = $request->title;
        $not->subtitle = '';
        $not->subject = '';

        $request->validate(['appointment' => ['date',]]);
        $not->type = 'Appointment';
        $appointment =   Carbon::parse($request->appointment)->format('Y-m-d\Th:i');

        $oldnot = Notification::where('user_id', $user->id)->where('type', 'Appointment')->whereDate('appointment', Carbon::parse($request->appointment)->format('Y-m-d'))->first();
        //Check if the user has an appointment on the same day
        if ($oldnot) {
            return  redirect()->back()->with(['error' => "Please choose a suitable time, The user already have an appointment on this day"]);
        }
        $def = Carbon::parse($request->appointment)->diff(Carbon::now());
        //check if the 
        if ($appointment <= Carbon::now()) {
            return  redirect()->back()->with(['error' => "Please choose a suitable datetime"]);
        }

        if ($def->days < 1 && $def->h < 24) {
            //   return $this->returnJson($def);

            return  redirect()->back()->with(['error' => "Sorry, you cannot take this appointment because the time remaining is less than 24 hours"]);
        }
        $not->appointment =  Carbon::parse($appointment)->format('Y-m-d\Th:i');
        $not->user_id = $user->id;
        $subtitle = "<p>Hallo $user->gender $user->name,</p><p><span style='font-size: 1rem;'>vielen Dank für Ihre Terminbuchung am:</span></p>";
        $sub = '<p>Falls Ihnen unerwartet etwas dazwischen kommt,<br>können

        Sie hier Ihren Termin umbuchen oder absagen bis zu 24<br>Stunden vor Ihrem Besuch.</p>
        
        <p><span style="font-size: 1rem;">Ihr Hausärzte-Team</span></p><p><span style="color: rgb(57, 123, 33); font-size: 1rem;">Gemeinscha spraxis Dr.med. Rechenberg<br>und Schäfer</span></p><p><span style="font-size: 1rem;"><font color="#397b21" style="">
        
        </font></span></p><p>RiHergasse 5<br>97877 Wertheim<br>Tel:&nbsp; &nbsp;+49 (0) 9342 6101</p><p>
        
                            
        
                        </p>';
        $not->subtitle = htmlentities($subtitle);
        $not->subject = htmlentities($sub);
        $success = $not->save();
        if ($success) {
            $not = Notification::find($not->id);
            $not->subject = html_entity_decode($not->subject);
            $not->subtitle = html_entity_decode($not->subtitle);
            return  redirect()->back()->with(['success' => 'create successfully']);
        }
        return  redirect()->back()->with(['error' => 'unknow']);
    }

    public function updateAppointmentFromAdmin(Request $request, User $user, Notification $not)
    {


        $not->title = $request->title;


        $request->validate(['appointment' => ['date',]]);
        $not->type = 'Appointment';
        $appointment =   Carbon::parse($request->appointment)->format('Y-m-d\Th:i');

        if ($appointment != $not->appointment) {
            $oldnot = Notification::where('user_id', $user->id)->where('type', 'Appointment')->whereDate('appointment', Carbon::parse($request->appointment)->format('Y-m-d'))->first();
            //Check if the user has an appointment on the same day
            if ($oldnot) {
                return  redirect()->back()->with(['error' => "Please choose a suitable time, The user already have an appointment on this day"]);
            }
            $def = Carbon::parse($request->appointment)->diff(Carbon::now());
            //check if the 
            if ($appointment <= Carbon::now()) {
                return  redirect()->back()->with(['error' => "Please choose a suitable datetime"]);
            }

            if ($def->days < 1 && $def->h < 24) {
                //   return $this->returnJson($def);

                return  redirect()->back()->with(['error' => "Sorry, you cannot take this appointment because the time remaining is less than 24 hours"]);
            }
        }
        $not->appointment =  Carbon::parse($appointment)->format('Y-m-d\Th:i');
        $not->user_id = $user->id;
        $not->image=null;
        $not->isImage=false;
        $success = $not->update();
        if ($success) {
            $not = Notification::find($not->id);

            return  redirect()->back()->with(['success' => 'update successfully']);
        }
        return  redirect()->back()->with(['error' => 'unknow']);
    }

    public function hideNotification($id)
    {
        $user = auth('api')->user();
        $not = Notification::where('id', $id)->where('user_id', $user->id)->where('type', '<>', 'Receipt')->first();
        if (!$not) {
            return   $this->returnJson('', 404, 'notfound');
        } else {
            $def = Carbon::parse($not->appointment)->diff(Carbon::now());

            if ($not->type == 'Appointment' && $def->days < 1 && $def->h < 24) {
                return $this->returnJson(404, 'Sorry, you cannot cancel your appointment because the time remaining is less than 24 hours');
            }
            $not->hide = true;
            $not->update();
        }

        return $this->returnJson('successfully deleted');
    }
}
