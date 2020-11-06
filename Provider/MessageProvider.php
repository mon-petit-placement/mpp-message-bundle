<?php

declare(strict_types=1);

namespace Mpp\MessageBundle\Provider;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

final class MessageProvider
{
    private MailerInterface $mailer;
    private Environment $twig;
    private array $messages;

    public function __construct(MailerInterface $mailer, Environment $twig, array $messages)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->messages = $messages;
    }

    public function send(string $messageIdentifier, array $transportOptions, array $contents): void
    {
        $message = $this->getMessageByIdentifier($messageIdentifier);
        $resolvedTransportOptions = self::resolveTransportOptions($transportOptions);

        $email = (new Email())
            ->from($message['from'])
            ->to(...$resolvedTransportOptions['recipientC'])
            ->subject($message['subject'])
            ->text($this->twig->render($message['template_txt'], $contents))
            ->html($this->twig->render($message['template_html'], $contents))
        ;

        if (isset($resolvedTransportOptions['recipientCc']) && null !== $resolvedTransportOptions['recipientCc']) {
            $email->addCc(...$resolvedTransportOptions['recipientCc']);
        }

        if (isset($resolvedTransportOptions['recipientBcc']) && null !== $resolvedTransportOptions['recipientBcc']) {
            $email->addBcc(...$resolvedTransportOptions['recipientBcc']);
        }

        if (!empty($message['attachments'])) {
            foreach ($message['attachments'] as $attachment) {
                $email->attach(fopen($attachment['file'], 'r'), $attachment['name'], $attachment['mime_type']);
            }
        }

        $this->mailer->send($email);
    }

    private function getMessageByIdentifier(string $messageIdentifier): array
    {
        if (!array_key_exists($messageIdentifier, $this->messages)) {
            throw new \UnexpectedValueException(sprintf('No message found for identifier : %s', $messageIdentifier));
        }

        return $this->messages[$messageIdentifier];
    }

    public static function resolveTransportOptions(array $transportOptions): array
    {
        $resolver = (new OptionsResolver())
            ->setRequired('recipientC')->setAllowedTypes('recipientC', ['array'])
            ->setDefined('recipientCc')->setAllowedTypes('recipientCc', ['array'])
            ->setDefined('recipientBcc')->setAllowedTypes('recipientBcc', ['array'])
        ;

        return $resolver->resolve($transportOptions);
    }
}
