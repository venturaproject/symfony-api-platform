<?php

declare(strict_types=1);

namespace App\Shared\Application\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Send an email using the specified template and data, with optional attachments.
     *
     * @param string $template The email template to use.
     * @param array<string, mixed> $data The data to pass to the template.
     * @param string $recipient The email address of the recipient.
     * @param string $subject The subject of the email.
     * @param array<string> $attachments Paths to files to attach to the email.
     * @return void
     */
    public function send(string $template, array $data, string $recipient, string $subject, array $attachments = []): void
    {
      
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: $recipient");
        }

        $email = (new Email())
            ->from('your_email@example.com') 
            ->to($recipient)
            ->subject($subject)
            ->html($this->renderTemplate($template, $data)); 

        foreach ($attachments as $filePath) {
            $email->attachFromPath($filePath);
        }

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to send email: " . $e->getMessage());
        }
    }

    /**
     * Render a Twig template with the provided data.
     *
     * @param string $template The template to render.
     * @param array<string, mixed> $data The data to use in the template.
     * @return string The rendered template.
     */
    private function renderTemplate(string $template, array $data): string
    {
        /** @var array<string, mixed> $data */
        return $this->twig->render($template, $data);
    }
}
