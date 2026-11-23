<?php

namespace App\Http\Requests;

use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /** @var string[] Generic email domains that are rejected in enterprise mode */
    protected const GENERIC_DOMAINS = [
        'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com',
        'aol.com', 'icloud.com', 'mail.com', 'proton.me',
        'protonmail.com', 'zohomail.com', 'yandex.com',
        'live.com', 'msn.com', 'me.com',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Step 1
            'service_id'       => ['required', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'headcount_range'  => ['required', Rule::in(['1-5', '6-20', '20+'])],

            // Step 2
            'scheduled_date'   => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'scheduled_time'   => ['required', 'date_format:H:i'],
            'client_timezone'  => ['required', 'timezone'],

            // Step 3
            'client_name'      => ['required', 'string', 'max:150'],
            'company_name'     => ['required', 'string', 'max:150'],
            'client_email'     => ['required', 'email:rfc,dns', 'max:255', function ($attr, $value, $fail) {
                $domain = strtolower(substr(strrchr($value, '@'), 1));
                if (in_array($domain, self::GENERIC_DOMAINS, true)) {
                    $fail('Please use your official corporate email address. Generic email providers are not accepted.');
                }
            }],
            'client_phone'             => ['nullable', 'string', 'max:50'],
            'operations_country'       => ['nullable', 'string', 'max:100'],
            'operational_challenges'   => ['nullable', 'string', 'max:2000'],
            'nda_requested'            => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required'      => 'Please select a BPO service.',
            'service_id.exists'        => 'The selected service is no longer available.',
            'headcount_range.required' => 'Please indicate your headcount requirement.',
            'headcount_range.in'       => 'Invalid headcount range selected.',
            'scheduled_date.required'  => 'Please select a consultation date.',
            'scheduled_date.after_or_equal' => 'The consultation date must be today or in the future.',
            'scheduled_time.required'  => 'Please select a time slot.',
            'client_timezone.required' => 'Please select your timezone.',
            'client_timezone.timezone' => 'Invalid timezone provided.',
            'client_name.required'     => 'Your full name is required.',
            'company_name.required'    => 'Company name is required.',
            'client_email.required'    => 'Your work email is required.',
            'client_email.email'       => 'Please enter a valid email address.',
        ];
    }

    /**
     * After validation: parse scheduled_at in UTC using client timezone.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($v->errors()->any()) {
                return;
            }

            $data = $v->getData();

            try {
                $scheduledAt = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $data['scheduled_date'] . ' ' . $data['scheduled_time'],
                    $data['client_timezone']
                )->utc();
            } catch (\Exception) {
                $v->errors()->add('scheduled_time', 'Invalid date/time combination.');
                return;
            }

            // Reject weekends (Saturday = 6, Sunday = 0)
            if (in_array($scheduledAt->dayOfWeek, [0, 6])) {
                $v->errors()->add('scheduled_date', 'Consultations are only available Monday through Friday.');
                return;
            }

            // Check slot availability via BookingService
            $service = app(BookingService::class);
            if (! $service->isSlotAvailable($scheduledAt)) {
                $v->errors()->add('scheduled_time', 'This time slot is no longer available. Please choose another.');
            }
        });
    }

    /**
     * Return resolved UTC datetime after validation passes.
     */
    public function scheduledAtUtc(): Carbon
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i',
            $this->scheduled_date . ' ' . $this->scheduled_time,
            $this->client_timezone
        )->utc();
    }
}
