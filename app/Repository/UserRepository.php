<?php

namespace App\Repository;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laudis\Neo4j\Contracts\TransactionInterface;
use Laudis\Neo4j\Types\CypherMap;
use Ramsey\Uuid\Uuid;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    private const RETURN_ALIASES = <<<CYPHER
u.id AS id,
u.name AS name,
u.email AS email,
u.password AS password,
u.remember_token AS remember_token,
u.email_verified_at AS email_verified_at,
u.created_at AS created_at,
u.updated_at AS updated_at
CYPHER;

    public function save(User $user): void
    {
        $params = [
            'id' => $user->id ?? Uuid::uuid4(),
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'remember_token' => $user->remember_token,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at ?? Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $this->client->writeTransaction(static function (TransactionInterface $tsx) use ($params) {
            $query = 'MERGE (u:User {id: $id}) '
                . 'SET u.name = $name, '
                . 'u.email = $email, '
                . 'u.password = $password, '
                . 'u.remember_token = $remember_token, '
                . 'u.email_verified_at = $email_verified_at, '
                . 'u.created_at = $created_at, '
                . 'u.updated_at = $updated_at';

            $tsx->run($query, $params);
        });
    }

    /**
     * @throws UserNotFoundException
     */
    public function getById(string $identifier): ?User
    {
        $query = 'MATCH (u:User {id: $id}) RETURN ' . self::RETURN_ALIASES;

        return $this->exec($query, [
            'id' => $identifier
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByIdAndToken(mixed $identifier, string $token): ?User
    {
        $query = 'MATCH (u:User {id: $id, remember_token: $token}) RETURN ' . self::RETURN_ALIASES;

        return $this->exec($query, [
            'id' => $identifier,
            'token' => $token
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function retrieveByCredentials(array $credentials): ?User
    {
        $query = 'MATCH (u:User {email: $email}) RETURN ' . self::RETURN_ALIASES;
        return $this->exec($query, $credentials);
    }

    /**
     * @throws UserNotFoundException
     */
    private function exec($query, $params = []): ?User
    {
        $results = $this->client->run($query, $params);
        if ($results->isEmpty()) {
            throw new UserNotFoundException();
        }

        $userNode = $results->first();

        return $this->nodeToUser($userNode);
    }

    private function nodeToUser(CypherMap $userNode): User
    {
        $user = new User;
        $user->id = $userNode->get("id");
        $user->name = $userNode->get("name");
        $user->email = $userNode->get("email");
        $user->password = $userNode->get("password");
        $user->remember_token = $userNode->get("remember_token");
        $user->email_verified_at = $userNode->get("email_verified_at");
        $user->created_at = $userNode->get("created_at");
        $user->updated_at = $userNode->get("updated_at");

        return $user;
    }
}
