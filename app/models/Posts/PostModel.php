<?php

require_once __DIR__ . '/../BaseModel.php';

class PostModel extends BaseModel
{
    public function createPost($userId, $content, $mediaPaths)
    {
        $conn = self::getConnection();

        $postId = uniqid();

        $sqlPost = "
            INSERT INTO POSTS (ID, USER_ID, CONTENT, CREATED_AT)
            VALUES (:id, :user_id, :content, CURRENT_TIMESTAMP)
        ";
        $stmt = oci_parse($conn, $sqlPost);
        oci_bind_by_name($stmt, ":id", $postId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":content", $content);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            error_log("Gagal insert POSTS: " . $e['message']);
            return false;
        }

        foreach ($mediaPaths as $path) {
            $mediaId = uniqid('media_');
            $mediaType = 'IMAGE';

            $sqlMedia = "
                INSERT INTO POST_MEDIA (ID, POST_ID, MEDIA_PATH, MEDIA_TYPE)
                VALUES (:id, :post_id, :media_path, :media_type)
            ";
            $stmtMedia = oci_parse($conn, $sqlMedia);
            oci_bind_by_name($stmtMedia, ":id", $mediaId);
            oci_bind_by_name($stmtMedia, ":post_id", $postId);
            oci_bind_by_name($stmtMedia, ":media_path", $path);
            oci_bind_by_name($stmtMedia, ":media_type", $mediaType);

            if (!oci_execute($stmtMedia)) {
                $e = oci_error($stmtMedia);
                error_log("Gagal insert POST_MEDIA: " . $e['message']);
                return false;
            }
        }

        return true;
    }

public function getAllPosts()
{
    $conn = self::getConnection();
    $sql = "
        SELECT 
            P.ID AS POST_ID,
            P.CONTENT,
            TO_CHAR(P.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
            P.USER_ID,
            U.USERNAME,
            U.FULL_NAME,
            U.PATH_PHOTO,
            PM.MEDIA_PATH
        FROM POSTS P
        JOIN USERS U ON P.USER_ID = U.ID
        LEFT JOIN POST_MEDIA PM ON P.ID = PM.POST_ID
        ORDER BY P.CREATED_AT DESC
    ";

    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    $posts = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $postId = $row['POST_ID'];
        if (!isset($posts[$postId])) {
            $posts[$postId] = [
                'POST_ID' => $postId,
                'CONTENT' => $row['CONTENT'],
                'CREATED_AT' => $row['CREATED_AT'],
                'USER_ID' => $row['USER_ID'], // tambahkan ini
                'USERNAME' => $row['USERNAME'],
                'FULL_NAME' => $row['FULL_NAME'],
                'PATH_PHOTO' => $row['PATH_PHOTO'],
                'MEDIA' => []
            ];
        }
        if (!empty($row['MEDIA_PATH'])) {
            $posts[$postId]['MEDIA'][] = $row['MEDIA_PATH'];
        }
    }

    return array_values($posts);
}

   public function updatePost($postId, $userId, $newContent, $newMediaPaths = [], $mediaToDelete = [])
{
    $conn = self::getConnection();

    // 1. Cek kepemilikan
    $checkSql = "SELECT USER_ID FROM POSTS WHERE ID = :id";
    $checkStmt = oci_parse($conn, $checkSql);
    oci_bind_by_name($checkStmt, ":id", $postId);
    oci_execute($checkStmt);
    $row = oci_fetch_assoc($checkStmt);

    if (!$row || $row['USER_ID'] !== $userId) {
        return false;
    }

    // 2. Update caption
    $sqlUpdate = "UPDATE POSTS SET CONTENT = :content WHERE ID = :id";
    $stmt = oci_parse($conn, $sqlUpdate);
    oci_bind_by_name($stmt, ":content", $newContent);
    oci_bind_by_name($stmt, ":id", $postId);

    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        error_log("Gagal update POSTS: " . $e['message']);
        return false;
    }

    // 3. Hapus media yang dipilih untuk dihapus
    if (!empty($mediaToDelete)) {
        $deleteSql = "DELETE FROM POST_MEDIA WHERE POST_ID = :post_id AND MEDIA_PATH = :media_path";
        foreach ($mediaToDelete as $path) {
            $stmtDel = oci_parse($conn, $deleteSql);
            oci_bind_by_name($stmtDel, ":post_id", $postId);
            oci_bind_by_name($stmtDel, ":media_path", $path);
            if (!oci_execute($stmtDel)) {
                $e = oci_error($stmtDel);
                error_log("Gagal hapus POST_MEDIA: " . $e['message']);
                return false;
            }
        }
    }

    // 4. Tambahkan media baru
    foreach ($newMediaPaths as $path) {
        $mediaId = uniqid('media_');
        $mediaType = 'IMAGE';

        $sqlMedia = "
            INSERT INTO POST_MEDIA (ID, POST_ID, MEDIA_PATH, MEDIA_TYPE)
            VALUES (:id, :post_id, :media_path, :media_type)
        ";
        $stmtMedia = oci_parse($conn, $sqlMedia);
        oci_bind_by_name($stmtMedia, ":id", $mediaId);
        oci_bind_by_name($stmtMedia, ":post_id", $postId);
        oci_bind_by_name($stmtMedia, ":media_path", $path);
        oci_bind_by_name($stmtMedia, ":media_type", $mediaType);

        if (!oci_execute($stmtMedia)) {
            $e = oci_error($stmtMedia);
            error_log("Gagal update POST_MEDIA: " . $e['message']);
            return false;
        }
    }

    return true;
}

public function deletePost($postId, $userId)
{
    $conn = self::getConnection();

    // 1. Cek kepemilikan post
    $checkSql = "SELECT USER_ID FROM POSTS WHERE ID = :id";
    $checkStmt = oci_parse($conn, $checkSql);
    oci_bind_by_name($checkStmt, ":id", $postId);
    oci_execute($checkStmt);
    $row = oci_fetch_assoc($checkStmt);

    if (!$row || $row['USER_ID'] !== $userId) {
        return false;
    }

    // 2. Ambil semua media path terkait post
    $mediaSql = "SELECT MEDIA_PATH FROM POST_MEDIA WHERE POST_ID = :post_id";
    $stmtMedia = oci_parse($conn, $mediaSql);
    oci_bind_by_name($stmtMedia, ":post_id", $postId);
    oci_execute($stmtMedia);

    $mediaPaths = [];
    while ($mediaRow = oci_fetch_assoc($stmtMedia)) {
        $mediaPaths[] = $mediaRow['MEDIA_PATH'];
    }

    // 3. Hapus file fisik
    foreach ($mediaPaths as $path) {
        $filePath = __DIR__ . '/../../../' . $path;
        if (file_exists($filePath)) {
            @unlink($filePath); // @ untuk menekan warning jika gagal
        }
    }

    // 4. Hapus record media dari DB
    $deleteMediaSql = "DELETE FROM POST_MEDIA WHERE POST_ID = :post_id";
    $stmtDelMedia = oci_parse($conn, $deleteMediaSql);
    oci_bind_by_name($stmtDelMedia, ":post_id", $postId);
    oci_execute($stmtDelMedia);

    // 5. Hapus record post
    $deletePostSql = "DELETE FROM POSTS WHERE ID = :id";
    $stmtDelPost = oci_parse($conn, $deletePostSql);
    oci_bind_by_name($stmtDelPost, ":id", $postId);

    if (!oci_execute($stmtDelPost)) {
        $e = oci_error($stmtDelPost);
        error_log("Gagal delete POSTS: " . $e['message']);
        return false;
    }

    return true;
}

}
