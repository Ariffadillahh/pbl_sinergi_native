<?php

require_once "app/models/BaseModel.php";

class ForumMember extends BaseModel
{
    public static function findByForumId($forumId)
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT fm.*, a.nama, a.email FROM forum_members fm JOIN anggota a ON fm.user_id = a.nomor WHERE fm.forum_id = ?");
        $stmt->bind_param("s", $forumId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
