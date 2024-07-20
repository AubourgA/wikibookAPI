<?php

namespace App\State;

use App\Dto\EmailDTO;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SendEmailProcessor implements ProcessorInterface
{
    private $mailer;
    private $validator;

    public function __construct(MailerInterface $mailer, ValidatorInterface $validator)
    {
        $this->mailer = $mailer;
        $this->validator = $validator;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // Validate the data
        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new HttpException(400, $errorsString);
        }
      
    
        // Send the email
        $email = (new Email())
            ->from($_ENV['FROM_MAILER'])
            ->to($data->email)
            ->subject("vous avez recu un nouveau message")
            ->text("Nom : ".$data->lastname."Prenom : ".$data->firstname." Message :". $data->message);

        $this->mailer->send($email);
    }
}
