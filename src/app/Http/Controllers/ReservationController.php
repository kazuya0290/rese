<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Favorite;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminderMail;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('shop')->where('user_id', auth()->id())->get(); 
        $favorites = Favorite::with('shop')->where('user_id', auth()->id())->get(); 
        return view('my_page', compact('reservations', 'favorites'));
    }

    public function store(ReservationRequest $request)
    {
        $validated = $request->validated();

        Reservation::create([
            'user_id' => auth()->id(), 
            'shop_id' => $validated['shop_id'], 
            'date' => $validated['date'], 
            'time' => $validated['time'],
            'number_of_people' => $validated['number_of_people'],
        ]);

        return redirect()->route('done.thanks');
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        $request->validate([
            'time' => 'required|date_format:Y-m-d\TH:i',
            'number_of_people' => 'required|integer|min:1|max:20',
        ]);

        $reservation->date = \Carbon\Carbon::parse($request->time)->format('Y-m-d');
        $reservation->time = \Carbon\Carbon::parse($request->time)->format('H:i');
        $reservation->number_of_people = $request->number_of_people;

        $reservation->save();

        return redirect()->route('my_page');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->back();
    }

   
    public function myPage()
    {
        $url = "https://example.com"; 
        $qrCode = QrCode::size(200)->generate($url); 
        $reservations = Reservation::with('shop')->where('user_id', auth()->id())->get();
        $favorites = Favorite::with('shop')->where('user_id', auth()->id())->get();

        return view('my_page', compact('reservations', 'favorites', 'qrCode'));
    }

   
    public function qr()
    {
        $url = "https://example.com";
        $qrCode = QrCode::size(200)->generate($url);

        return view('qr_view', compact('qrCode'));
    }
}
