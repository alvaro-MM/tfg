<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index()
    {
        $bookings = Booking::with('table', 'user')
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        $tables = Table::where('status', 'available')->get();

        return view('bookings.create', compact('tables'));
    }

    /**
     * Store a newly created booking
     */
    public function store(StoreBookingRequest $request)
    {

        $bookingTime = Carbon::createFromFormat('H:i:s', $request->booking_time);

        Booking::create([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'table_id'     => $request->table_id,
            'user_id'      => auth()->id(),
            'offer_id'     => $request->offer_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $bookingTime->format('H:i:s'),
            'status'       => 'active',
        ]);

        Table::where('id', $request->table_id)
            ->update(['status' => 'reserved']);

        return redirect()
            ->route('home')
            ->with('success', 'Reserva creada correctamente');
    }

    /**
     * Show a booking
     */
    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show form for editing a booking
     */
    public function edit(Booking $booking)
    {
        $tables = Table::all();

        return view('bookings.edit', compact('booking', 'tables'));
    }

    /**
     * Update a booking
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {

        $bookingTime = Carbon::createFromFormat('H:i:s', $request->booking_time);
        $endTime = $bookingTime->copy()->addMinutes(90);

        $exists = Booking::where('table_id', $request->table_id)
            ->where('booking_date', $request->booking_date)
            ->where('status', 'active')
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($bookingTime, $endTime) {
                $query->whereRaw(
                    '? < ADDTIME(booking_time, "01:30:00") AND ? > booking_time',
                    [
                        $bookingTime->format('H:i:s'),
                        $endTime->format('H:i:s'),
                    ]
                );
            })
            ->exists();

        if ($exists) {
            return back()->withErrors('La mesa ya está reservada en ese horario');
        }

        $booking->update([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'table_id'     => $request->table_id,
            'offer_id'     => $request->offer_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $bookingTime->format('H:i:s'),
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Reserva actualizada correctamente');
    }

    public function destroy(Booking $booking)
    {

        $booking->delete();

        $hasActive = $booking->table
            ->bookings()
            ->where('status', 'active')
            ->exists();

        if (!$hasActive) {
            $booking->table->update(['status' => 'available']);
        }

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Reserva eliminada y mesa liberada correctamente');
    }
}
