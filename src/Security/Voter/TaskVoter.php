<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class TaskVoter extends Voter
{
    const EDIT = 'TASK_EDIT';
    const ADD = 'TASK_ADD';
    const VIEW = 'TASK_VIEW';
    const DELETE = 'TASK_DELETE';

    protected function supports($attribute, $subject): bool
    {
        return \in_array($attribute, [self::ADD, self::EDIT, self::VIEW, self::DELETE], true)
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // @var Task $subject
        return $subject->getList() !== null &&
            $subject->getList()->getUser() !== null &&
            ($user->getId() === $subject->getList()->getUser()->getId())
            ;
    }
}
