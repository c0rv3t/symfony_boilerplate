<?php

namespace App\Security\Voter;

use App\Entity\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ClientVoter extends Voter
{
    public const INDEX = 'index_client';
    public const ADD = 'add_client';
    public const EDIT = 'edit_client';
    public const DELETE = 'delete_client';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (in_array($attribute, [self::INDEX, self::ADD, self::EDIT, self::DELETE])) {
            if ($attribute === self::INDEX) {
                if (!($subject instanceof Client) && $subject !== Client::class) {
                    return false;
                }
            } elseif (($attribute === self::EDIT || $attribute === self::DELETE) && !$subject instanceof Client) {
                return false;
            }
            return true;
        }
        return false;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user) {
            return false;
        }

        return $this->security->isGranted('ROLE_MANAGER');
    }
}