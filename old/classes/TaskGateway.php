<?php

class TaskGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }


    public function all_customer(int $user_id): array
    {
        $sql = "SELECT * FROM task WHERE user_id = :user_id ORDER BY name";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // convert the 1 to true for the is_completed column for all the record
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row["is_completed"] = (bool) $row["is_completed"];

            $data[] = $row;
        }
        return $data;
    }

    public function read_customer(int $user_id, string $bvn, string $nin)
    {
        $sql = "SELECT * FROM task WHERE user_id = :user_id AND (bvn = :bvn  OR  nin = :nin)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':bvn', $bvn, PDO::PARAM_INT);
        $stmt->bindValue(':nin', $nin, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function create_customer(int $user_id, array $data): string
    {
        $sql = "INSERT INTO task (name, priority, is_completed, user_id) VALUES (:name, :priority, :is_completed, :user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        if (empty($data["priority"])) {
            $stmt->bindValue(':priority', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':priority', $data["priority"], PDO::PARAM_INT);
        }
        $stmt->bindValue(':is_completed', $data["is_completed"] ?? false, PDO::PARAM_BOOL);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function delete_customer(int $user_id, string $id): int
    {
        $sql = "DELETE FROM task WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function update_customer(int $user_id, string $id, array $data): int
    {
        $fields = [];
        if (!empty($data["name"])) {
            $fields["name"] = [$data["name"], PDO::PARAM_STR];
        }
        if (array_key_exists("priority", $data)) {
            $fields["priority"] = [$data["priority"], $data["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT];
        }
        if (array_key_exists("is_completed", $data)) {
            $fields["is_completed"] = [$data["is_completed"], PDO::PARAM_BOOL];
        }

        if (empty($fields)) {
            return 0;
        } else {

            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE task" . " SET " . implode(", ", $sets) . " WHERE id = :id" . " AND user_id = :user_id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            foreach ($fields as $name => $value) {
                $stmt->bindValue(":$name", $value[0], $value[1]);
            }
            $stmt->execute();

            return $stmt->rowCount();
        }
    }

}
