<?php

require_once __DIR__ . '/../models/Admin/overviewCount.php';

class DashboardOverview
{
    private $overviewCountModel;

    public function __construct()
    {
        $this->overviewCountModel = new overviewCount();
    }

    public function countAnggota()
    {
        $count = $this->overviewCountModel->countAnggota();
        return $count;
    }

    public function countPost()
    {
        $count = $this->overviewCountModel->countPost();
        return $count;
    }

    public function countForum()
    {
        $count = $this->overviewCountModel->countForum();
        return $count;
    }

    public function countLaporan()
    {
        $count = $this->overviewCountModel->countLaporan();
        return $count;
    }

    public function getForumsActivityData()
    {
        $rawActivityTrend = $this->overviewCountModel->getMonthlyActivityTrend();
        $activityChart = $this->formatChartData($rawActivityTrend, 'ACTIVITY_MONTH', 'TOTAL_ACTIVITY');
        $activityStats = $this->overviewCountModel->getActivityStats();

        return [
            'summary' => $activityStats,
            'chart' => $activityChart
        ];
    }

    public function getContentEngagementData()
    {
        $postStats = $this->overviewCountModel->getPostStats();
        $rawPostTrend = $this->overviewCountModel->getMonthlyPostTrend();
        $postChart = $this->formatChartData($rawPostTrend, 'POST_MONTH', 'TOTAL_POSTS');

        return [
            'summary' => $postStats,
            'chart' => $postChart
        ];
    }

    public function getDashboardDataApi()
    {
        header('Content-Type: application/json');

        try {
            $overviewCounts = [
                'anggota' => $this->countAnggota(),
                'posts' => $this->countPost(),
                'forums' => $this->countForum(),
                'laporan' => $this->countLaporan()
            ];

            $forumData = $this->getForumsActivityData();

            $postData = $this->getContentEngagementData();

            $output = [
                'success' => true,
                'overview_counts' => $overviewCounts,
                'platform_activity' => $forumData,
                'content_engagement' => $postData
            ];

            echo json_encode($output, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function formatChartData($rawData, $monthKey, $countKey)
    {
        $months = [
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0
        ];

        $monthMap = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'
        ];

        foreach ($rawData as $row) {
            if (isset($row[$monthKey]) && isset($monthMap[$row[$monthKey]])) {
                $monthName = $monthMap[$row[$monthKey]];
                $months[$monthName] = (int)$row[$countKey];
            }
        }

      
        return [
            'labels' => array_keys($months), 
            'data' => array_values($months)  
        ];
    }
}
