<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'table_id' => 'required|exists:tables,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i:s',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->booking_time) return;

            $bookingTime = Carbon::createFromFormat('H:i:s', $this->booking_time);
            $endTime = $bookingTime->copy()->addMinutes(90);

            $exists = Booking::where('table_id', $this->table_id)
                ->where('booking_date', $this->booking_date)
                ->where('status', 'active')
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
                $validator->errors()->add(
                    'booking_time',
                    'La mesa ya está reservada en ese horario'
                );
            }
        });
    }
}
