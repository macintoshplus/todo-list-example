<?php
declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\TodoList;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class TodoListVoter extends Voter
{
    const EDIT = 'LIST_EDIT';
    const ADD = 'LIST_ADD';
    const VIEW = 'LIST_VIEW';
    const DELETE = 'LIST_DELETE';
    const ADD_TASK = 'LIST_ADD_TASK';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::ADD, self::EDIT, self::VIEW, self::DELETE, self::ADD_TASK])
            && $subject instanceof TodoList;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $subject->getUser() !== null && ($user->getId() === $subject->getUser()->getId());
    }
}
