<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$subject instanceof User) {
            return false;
        }
        
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:

                if($user->getId() === $subject->getId()) {return true; };
                break;

            case self::VIEW:
                if($user->getId() === $subject->getId() || in_array('ROLE_ADMIN',$user->getRoles())) {return true; };
                break;
        }

        return false;
    }
}
