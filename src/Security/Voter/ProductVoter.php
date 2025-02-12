<?php

namespace App\Security\Voter;

use App\Entity\Product;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    public const ADD = 'add_product';
    public const EDIT = 'edit_product';
    public const DELETE = 'delete_product';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (in_array($attribute, [self::ADD, self::EDIT, self::DELETE])) {
            if (($attribute === self::EDIT || $attribute === self::DELETE) && !$subject instanceof Product) {
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

        return $this->security->isGranted('ROLE_ADMIN');
    }
}