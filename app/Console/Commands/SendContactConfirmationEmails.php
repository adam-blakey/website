<?php

namespace App\Console\Commands;

use App\Mail\ContactConfirmation;
use App\Mail\AdminContact;
use App\Models\Contact;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendContactConfirmationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-contact-confirmation-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send confirmation emails to contacts who have not received one yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contacts = Contact::where('confirmation_sent', false)->get();

        if ($contacts->isEmpty()) {
            $this->info('No pending confirmation emails to send.');
            return 0;
        }

        $count = 0;
        $failed = 0;
        foreach ($contacts as $contact) {
            try {
                Mail::send(new ContactConfirmation($contact));
                $this->info("Sent confirmation email to {$contact->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send confirmation email to {$contact->email}: {$e->getMessage()}");

                // This is to prevent emails being sent every minute for the unfortunate form-filler-in-erer.
                $contact->confirmation_sent = true;
                $contact->save();

                $failed++;
                continue;
            }

            $admin_email = config('app.admin_email');
            try {
                Mail::send(new AdminContact($contact));
                $this->info("Sent confirmation email to {$admin_email}");
            } catch (\Exception $e) {
                $this->error("Failed to send admin confirmation email to {$admin_email}: {$e->getMessage()}");

                // This is to prevent emails being sent every minute for the unfortunate form-filler-in-erer.
                $contact->confirmation_sent = true;
                $contact->save();

                $failed++;
                continue;
            }

            $contact->confirmation_sent = true;
            $contact->save();

            $count++;
        }

        $this->info("Sent {$count} confirmation emails.");
        return $failed ? 1 : 0;
    }
}
