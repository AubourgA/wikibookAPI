<?php

namespace App\DTO;

use App\State\SendEmailProcessor;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;




#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/send-email',
            processor: SendEmailProcessor::class,
            inputFormats: [
                // 'jsonld' => ['application/ld+json'],
                'json' => ['application/json'],  // Ajout de support pour application/json
            ],
            openapiContext: [
                'summary' => 'Send an email',
                'description' => 'Send an email with the provided subject and message to the specified email address',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                        'example' => 'example@example.com'
                                    ],
                                    // 'subject' => [
                                    //     'type' => 'string',
                                    //     'example' => 'Subject of the email'
                                    // ],
                                    'message' => [
                                        'type' => 'string',
                                        'example' => 'Message body of the email'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '204' => [
                        'description' => 'Email sent successfully'
                    ],
                    '400' => [
                        'description' => 'Invalid input'
                    ]
                ]
            ]
        )
    ],
    paginationEnabled: false,
    output: false
)]
final class EmailDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email;

    // #[Assert\NotBlank]
    // public $subject;

    #[Assert\NotBlank]
    public $message;
    
}