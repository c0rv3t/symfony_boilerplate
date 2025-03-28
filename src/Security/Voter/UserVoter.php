<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const INDEX = 'index_user';
    const ADD = 'add_user';
    const EDIT = 'edit_user';
    const DELETE = 'delete_user';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (in_array($attribute, [self::INDEX, self::ADD, self::EDIT, self::DELETE])) {
            if ($attribute === self::INDEX) {
                if (!($subject instanceof User) && $subject !== User::class) {
                    return false;
                }
            } elseif (($attribute === self::EDIT || $attribute === self::DELETE) && !$subject instanceof User) {
                return false;
            }
            return true;
        }
        return false;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $this->security->isGranted('ROLE_ADMIN');
    }
}