<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserKey;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Webauthn\Bundle\Repository\AbstractPublicKeyCredentialUserEntityRepository;
use Webauthn\PublicKeyCredentialUserEntity;

final class UserKeyRepository extends AbstractPublicKeyCredentialUserEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserKey::class);
    }

    public function createUserEntity(string $username, string $displayName, ?string $icon): PublicKeyCredentialUserEntity
    {
        $id = Uuid::uuid4()->toString();

        return new UserKey($id, $username, $displayName, $icon, []);
    }

    public function saveUserEntity(PublicKeyCredentialUserEntity $userEntity): void
    {
        if (!$userEntity instanceof UserKey) {
            $userEntity =  new UserKey(
                $userEntity->getId(),
                $userEntity->getName(),
                $userEntity->getDisplayName(),
                $userEntity->getId()
            );
        }

        parent::saveUserEntity($userEntity);
    }

    public function find(string $username): ?UserKey
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('u')
            ->from(UserKey::class, 'u')
            ->where('u.name = :name')
            ->setParameter(':name', $username)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
