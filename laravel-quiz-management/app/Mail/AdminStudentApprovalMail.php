<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminStudentApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentData;
    public $adminToken;
    public $studentID;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($studentData, $adminToken, $studentID)
    {
        $this->studentData = $studentData;
        $this->adminToken = $adminToken;
        $this->studentID = $studentID; 
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Admin Student Approval Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.student-approval',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
