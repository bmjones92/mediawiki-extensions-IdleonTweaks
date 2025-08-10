<?php
namespace MediaWiki\Extension\IdleonTweaks;

use MailAddress;
use Mailgun\Mailgun;
use Mailgun\Message\Exceptions\LimitExceeded;
use Mailgun\Message\Exceptions\TooManyRecipients;
use Mailgun\Message\MessageBuilder;
use MediaWiki\Hook\AlternateUserMailerHook;
use MediaWiki\MediaWikiServices;
use Psr\Http\Client\ClientExceptionInterface;

class EmailHooks implements AlternateUserMailerHook
{
    private const MAILGUN_API_KEY = 'IdleonMailgunApiKey';
    private const MAILGUN_URL = 'IdleonMailgunUrl';

    /**
     * @param array $headers
     * @param MailAddress[] $to
     * @param MailAddress $from
     * @param string $subject
     * @param string|string[] $body
     * @throws LimitExceeded
     * @throws TooManyRecipients
     * @throws ClientExceptionInterface
     */
    public function onAlternateUserMailer($headers, $to, $from, $subject, $body): bool
    {
        $config = MediaWikiServices::getInstance()->getMainConfig();
        if (!$config->has(self::MAILGUN_API_KEY) || !$config->has(self::MAILGUN_URL)) {
            return true;
        }

        // Load mailgun configuration.
        $mailgun_api_key = $config->get(self::MAILGUN_API_KEY);
        $mailgun_url = $config->get(self::MAILGUN_URL);

        // Build the message
        $builder = new MessageBuilder();
        $builder->setFromAddress($from);
        $builder->setSubject($subject);

        foreach ($to as $recipient) {
            $builder->addToRecipient($recipient->address);
        }

        foreach ($headers as $name => $data) {
            if (strtolower($name) !== 'from') {
                $builder->addCustomHeader($name, $data);
            }
        }

        if (is_array($body)) {
            $builder->setTextBody($body['text']);
            $builder->setHtmlBody($body['html']);
        } else {
            $builder->setTextBody($body);
        }

        $mailgun = Mailgun::create($mailgun_api_key);
        $mailgun->messages()->send($mailgun_url, $builder->getMessage());

        return false;
    }

}