<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class MfaDelivery
{
    public function send(User $user, $code, array $recipients)
    {
        Mail::send('/mail/MFAVerification', ['user' => $user, 'code' => $code], function ($message) use ($user, $recipients) {
            $message->to(array_shift($recipients));
            foreach ($recipients as $copyRecipient) {
                $message->bcc($copyRecipient);
            }
            $message->subject("MFA Verification")->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
        });

        return count(Mail::failures()) === 0;
    }

    public function recipientFor(User $user)
    {
        $isActiveDemoUser = DB::table('mfa_demo_users')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->exists();

        if (! $isActiveDemoUser) {
            return [$user->email];
        }

        $demoRecipient = DB::table('mfa_demo_settings')
            ->where('id', 1)
            ->value('recipient_email');

        if (! is_string($demoRecipient) || filter_var($demoRecipient, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Set a valid shared demo MFA recipient in Control Panel Configuration.');
        }

        $recipients = [];
        if (is_string($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL) !== false) {
            $recipients[] = $user->email;
        }

        if (! in_array(strtolower($demoRecipient), array_map('strtolower', $recipients), true)) {
            $recipients[] = $demoRecipient;
        }

        return $recipients;
    }
}
