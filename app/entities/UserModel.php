<?php

namespace app\entities;

use app\data\UserDTO;
use Connection;

final class UserModel
{
    public function __construct(
        public Connection $connection
    ) {
    }

    public function getUsers()
    {
        return $this->connection->query("SELECT * FROM users");
    }

    public function createUser(UserDTO $dataUser): bool
    {
        $sqlInsert = "INSERT INTO users (name, email) VALUES (:name, :email)";

        $this->connection->getConnection()->beginTransaction();

        $sqlBuilder = $this->connection->prepare($sqlInsert);

        $sqlBuilder->bindParam(":name", $dataUser->name);
        $sqlBuilder->bindParam(":email", $dataUser->email);

        if ($sqlBuilder->execute()) {
            $this->connection->getConnection()->commit();

            $userId = $this->connection->getConnection()->lastInsertId();

            $this->syncColors($userId, $dataUser->colors);
            return true;
        }

        return false;
    }

    public function updateUser(int $userId, UserDTO $dataUser): bool
    {
        $sqlInsert = "UPDATE users SET name = :name, email = :email WHERE id = :userId";

        $this->connection->getConnection()->beginTransaction();

        $sqlBuilder = $this->connection->prepare($sqlInsert);

        $sqlBuilder->bindParam(":name", $dataUser->name);
        $sqlBuilder->bindParam(":email", $dataUser->email);
        $sqlBuilder->bindParam(":userId", $userId);

        if ($sqlBuilder->execute()) {
            $this->connection->getConnection()->commit();
            $this->syncColors($userId, $dataUser->colors);

            return true;
        }

        return false;
    }

    private function syncColors(int $userId, array $colors = []): void
    {
        $this->connection->getConnection()->beginTransaction();

        $sqlDeleteColors = "DELETE FROM user_colors WHERE user_id = :userId";

        $sqlBuilderDeleteColors = $this->connection->prepare($sqlDeleteColors);
        $sqlBuilderDeleteColors->bindParam(":userId", $userId);
        $sqlBuilderDeleteColors->execute();

        foreach ($colors as $color) {
            $sqlInsert = "INSERT INTO user_colors (user_id, color_id) VALUES (:userId, :colorId)";
            $sqlBuilderInsertColors = $this->connection->prepare($sqlInsert);
            $sqlBuilderInsertColors->bindParam(":userId", $userId);
            $sqlBuilderInsertColors->bindParam(":colorId", $color);

            if (!$sqlBuilderInsertColors->execute()) {
                $this->connection->getConnection()->rollBack();
            }
        }

        $this->connection->getConnection()->commit();
    }

    public function find(int $id): ?UserDTO
    {
        $sqlSelect = "SELECT * FROM users WHERE id = :paramId";

        $sqlBuilder = $this->connection->prepare($sqlSelect);

        $sqlBuilder->bindParam(':paramId', $id);
        $sqlBuilder->execute();

        $user = $sqlBuilder->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $sqlSelectColor = "
                SELECT colors.id, colors.name
                  FROM colors 
                  JOIN user_colors 
                    ON user_colors.color_id = colors.id
                 WHERE user_colors.user_id = :userId
            ";

            $sqlBuilderColors = $this->connection->prepare($sqlSelectColor);

            $sqlBuilderColors->bindParam(':userId', $id);
            $sqlBuilderColors->execute();

            $colors = $sqlBuilderColors->fetchAll(\PDO::FETCH_ASSOC);

            return new UserDTO(
                name: $user['name'],
                email: $user['email'],
                colors: $colors ?: []
            );
        }

        return null;
    }

    public function deleteUser(int $userId)
    {
        $this->connection->getConnection()->beginTransaction();

        $sqlDeleteColors = "DELETE FROM user_colors WHERE user_id = :userId";

        $sqlBuilderDeleteColors = $this->connection->prepare($sqlDeleteColors);
        $sqlBuilderDeleteColors->bindParam(":userId", $userId);
        if (!$sqlBuilderDeleteColors->execute()) {
            $this->connection->getConnection()->rollBack();
            return false;
        }

        $sqlDeleteUser = "DELETE FROM users WHERE id = :userId";
        $sqlBuilderDeleteUser = $this->connection->prepare($sqlDeleteUser);
        $sqlBuilderDeleteUser->bindParam(":userId", $userId);

        if (!$sqlBuilderDeleteUser->execute()) {
            $this->connection->getConnection()->rollBack();
            return false;
        }

        $this->connection->getConnection()->commit();
        return true;
    }
}