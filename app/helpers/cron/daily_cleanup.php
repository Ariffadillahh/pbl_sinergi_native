<?php

$rootDir = dirname(__DIR__, 3);
require_once $rootDir . '/app/models/BaseModel.php';

class CronCleanup extends BaseModel
{
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        ob_start();

        echo "========================================================" . PHP_EOL;
        echo "LOG START: " . date('Y-m-d H:i:s') . " WIB" . PHP_EOL;
        echo "========================================================" . PHP_EOL;

        $this->cleanupFiles();
        echo PHP_EOL;
        $this->cleanupNotifications();

        echo PHP_EOL . "=== CLEANUP SELESAI PADA: " . date('Y-m-d H:i:s') . " WIB ===" . PHP_EOL;
        echo "--------------------------------------------------------" . PHP_EOL . PHP_EOL;

        $outputLog = ob_get_contents();
        ob_end_clean();

        echo $outputLog;

        global $rootDir;
        $logPath = $rootDir . '/storage/logs';

        if (!is_dir($logPath)) {
            mkdir($logPath, 0777, true);
        }

        $fileLog = $logPath . '/cron_history.log';
        file_put_contents($fileLog, $outputLog, FILE_APPEND);
    }

    private function cleanupFiles()
    {
        global $rootDir;
        $tempPath = $rootDir . '/storage/users/temp/';
        $expireSeconds = 86400;

        echo "[1/2] Membersihkan Folder Temp..." . PHP_EOL;

        $filesDeleted = 0;

        if (is_dir($tempPath)) {
            $files = glob($tempPath . '*');
            $now = time();

            foreach ($files as $file) {
                if (is_file($file)) {
                    if ($now - filemtime($file) >= $expireSeconds) {

                        $fileName = basename($file);
                        if ($fileName === '.gitignore' || $fileName === '.htaccess') continue;

                        if (unlink($file)) {
                            echo " - File dihapus: $fileName" . PHP_EOL;
                            $filesDeleted++;
                        }
                    }
                }
            }
            echo "Selesai. Total $filesDeleted file dihapus." . PHP_EOL;
        } else {
            echo "Folder temp tidak ditemukan." . PHP_EOL;
        }
    }

    private function cleanupNotifications()
    {
        echo "[2/2] Membersihkan Notifikasi Lama..." . PHP_EOL;

        try {
            $conn = self::getConnection();

            if (!$conn) {
                throw new Exception("Gagal koneksi ke Database Oracle.");
            }

            $sql = "DELETE FROM NOTIFICATIONS 
                    WHERE (IS_READ = 1 AND CREATED_AT < (SYSDATE - 1)) 
                    OR (IS_READ = 0 AND CREATED_AT < (SYSDATE - 7))";


            $stmt = oci_parse($conn, $sql);
            if (!$stmt) {
                $e = oci_error($conn);
                throw new Exception($e['message']);
            }

            $execute = oci_execute($stmt);
            if (!$execute) {
                $e = oci_error($stmt);
                throw new Exception($e['message']);
            }

            $rowCount = oci_num_rows($stmt);
            echo "Selesai. Total $rowCount notifikasi dihapus dari database." . PHP_EOL;

            oci_free_statement($stmt);
        } catch (Exception $e) {
            echo "ERROR Database: " . $e->getMessage() . PHP_EOL;
        }
    }
}

$worker = new CronCleanup();
$worker->run();
