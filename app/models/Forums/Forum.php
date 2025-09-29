<?php

require_once "app/models/BaseModel.php";

class Forum extends BaseModel
{
    public $id;
    public $name;
    public $about;
    public $isPrivate;
    public $key;
    public $photo;
    public $owner_id;
    public $created_at;

    public static function getAll()
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT * FROM forums ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function findById($id)
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT * FROM forums WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public static function create($data)
    {
        $conn = self::getConnection();
        $conn->begin_transaction();

        try {
            $id = uniqid();
            $photoPath = $data['photo'] ?? null;
            $keyForum = $data['isPrivate'] ? ($data['keyForum'] ?? null) : null;

            // 1. Masukkan data ke tabel forums
            $stmt = $conn->prepare("INSERT INTO forums (id, name, about, isPrivate, `key`, photo, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisss", $id, $data['forumName'], $data['bio'], $data['isPrivate'], $keyForum, $photoPath, $data['user_id']);
            $stmt->execute();

            // 2. Daftarkan owner sebagai member pertama
            $memberId = uniqid();
            $joinedAt = date('Y-m-d H:i:s');
            $stmt2 = $conn->prepare("INSERT INTO forum_members (id, forum_id, user_id, joined_at) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("ssis", $memberId, $id, $data['user_id'], $joinedAt);
            $stmt2->execute();

            $conn->commit();
            return $id; 

        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }
}
