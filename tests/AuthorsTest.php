<?php

namespace App\Tests;

use App\Entity\Author;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;



class AuthorApiTest extends ApiTestCase
{
    public function testCreateAuthor(): void
    {
        $client = self::createClient();

        // Remplacez la route par celle de votre API Platform
        $client->request('POST', '/api/authors', [
            'json' => [
                '@context' => '/api/contexts/Author',
                '@type' => 'Author',
                'name' => 'Doe',
                'firstname' => 'John',
                'birthdate' => '1985-07-31T00:00:00+00:00',
                'nationality' => '/api/nationalities/1', // Remplacez par l'URL de la nationalité
            ],
            'headers' => ['Content-Type' => 'application/ld+json'],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateAuthorInvalidUniqueNameFirstname(): void
    {
        $client = self::createClient();

        // Remplacez la route par celle de votre API Platform
        $client->request('POST', '/api/authors', [
            'json' => [
                '@context' => '/api/contexts/Author',
                '@type' => 'Author',
                'name' => 'Doe',
                'firstname' => 'John',
                'birthdate' => '1985-07-31T00:00:00+00:00',
                'nationality' => '/api/nationalities/1', // Remplacez par l'URL de la nationalité
            ],
            'headers' => ['Content-Type' => 'application/ld+json'],
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testCreateAuthorInvalidName():void
    {
        $client = self::createClient();

        // Remplacez la route par celle de votre API Platform
        $client->request('POST', '/api/authors', [
            'json' => [
                '@context' => '/api/contexts/Author',
                '@type' => 'Author',
                'name' => '44',
                'firstname' => 'John',
                'birthdate' => '1985-07-31T00:00:00+00:00',
                'nationality' => '/api/nationalities/1', // Remplacez par l'URL de la nationalité
            ],
            'headers' => ['Content-Type' => 'application/ld+json'],
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testUpdateAuthor(): void
    {
    
        $client = static::createClient();
        $name = $this->findIriBy(Author::class, ['name' => 'Doe']);

        $client->request('PATCH', $name, [
            'json' => [
                'name' => 'doel',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]           
        ]);

        $this->assertResponseIsSuccessful();
    }
}
