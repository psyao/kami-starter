<?php

declare(strict_types=1);

namespace App\Forms;

use Psyao\Kami\Forms\Form;

/**
 * The contact form on the home page, `contact` (resources/views/kami/home.blade.php). Its recipient is the site's `MAIL_FROM_ADDRESS`,
 * the visitor's address on `Reply-To`.
 */
final class ContactForm extends Form
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    #[\Override]
    public function recipients(): array
    {
        $address = config('mail.from.address');

        return is_string($address) && $address !== '' ? [$address] : [];
    }

    #[\Override]
    public function replyTo(): string
    {
        return 'email';
    }
}
