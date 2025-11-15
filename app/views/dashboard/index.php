<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sinergi</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="grid grid-cols-2 md:grid-cols-4 md:gap-[34px] gap-3">
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Anggota</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/userCount.svg" class="size-10">
            </div>
            <h1 id="total-anggota" class="text-xl font-bold mt-4"><?= $anggotaCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#2563EB] line-clamp-1">Untuk Semua Role</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Postingan</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/postAcctive.svg" class="size-10">
            </div>
            <h1 id="total-posts" class="text-xl font-bold mt-4"><?= $postCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#4338CA] line-clamp-1">Dari Semua Pengguna</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Forum</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/forums.svg" class="size-10">
            </div>
            <h1 id="total-forums" class="text-xl font-bold mt-4"><?= $forumCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#7C3AED] line-clamp-1">Telah Dibuat</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Laporan Masuk</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/reportCount.svg" class="size-10">
            </div>
            <h1 id="total-laporan" class="text-xl font-bold mt-4"><?= $laporanCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#EF4444] line-clamp-1">Telah Diterima</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:mt-10 mt-4">
        <div class="bg-white drop-shadow rounded-xl p-4 w-full">
            <p class="text-gray-700">Forums Activity Trends</p>
            <h1 id="activity-total" class="text-2xl font-bold mt-2">
                <span class="loading-skeleton">...</span>
            </h1>
            <p class="text-md text-gray-500 mb-4">
                Last 30 Days
                <span id="activity-percentage" class="text-green-500">
                    <span class="loading-skeleton">...</span>
                </span>
            </p>
            <canvas id="activityChart1" height="150"></canvas>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4 w-full">
            <p class="text-gray-700">Posts Engagement</p>
            <h1 id="content-total" class="text-2xl font-bold mt-2">
                <span class="loading-skeleton">...</span>
            </h1>
            <p class="text-md text-gray-500 mb-4">
                Last 30 Days
                <span id="content-percentage" class="text-red-500">
                    <span class="loading-skeleton">...</span>
                </span>
            </p>
            <canvas id="activityChart2" height="150"></canvas>
        </div>
    </div>


    <script>
        // Chart instances global
        let activityChart = null;
        let contentChart = null;

        // ====== Utils ======
        const Utils = {
            CHART_COLORS: {
                blue: 'rgb(54, 162, 235)',
                red: 'rgb(255, 99, 132)',
            },
            transparentize(color, opacity) {
                const alpha = 1 - opacity;
                return color.replace('rgb', 'rgba').replace(')', `, ${alpha})`);
            }
        };

        // ====== Fetch Data dari API ======
        async function loadDashboardData() {
            try {
                // Ganti dengan URL API Anda yang sebenarnya
                const response = await fetch('<?php echo BASEURL ?>/getDashboardDataApi');

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                console.log('Data from API:', data); // Untuk debugging

                if (data.success) {
                    // Update overview counts
                    updateOverviewCounts(data.overview_counts);

                    // Update Platform Activity Chart (Biru)
                    updateActivityChart(data.platform_activity);

                    // Update Content Engagement Chart (Pink/Red)
                    updateContentChart(data.content_engagement);
                } else {
                    console.error('API Error:', data.error);
                    showError('Failed to load dashboard data');
                }

            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showError('Failed to connect to server');
            }
        }

        // Update angka overview
        function updateOverviewCounts(counts) {
            if (counts) {
                document.getElementById('total-anggota').textContent = formatNumber(counts.anggota || 0);
                document.getElementById('total-posts').textContent = formatNumber(counts.posts || 0);
                document.getElementById('total-forums').textContent = formatNumber(counts.forums || 0);
                document.getElementById('total-laporan').textContent = formatNumber(counts.laporan || 0);
            }
        }

        // Update Platform Activity Chart
        function updateActivityChart(activityData) {
            if (!activityData || !activityData.summary || !activityData.chart) {
                console.error('Invalid activity data');
                return;
            }

            const summary = activityData.summary;
            const chartData = activityData.chart;

            // Update angka dan persentase
            document.getElementById('activity-total').textContent = formatNumber(summary.total_last_30_days);

            const percentageEl = document.getElementById('activity-percentage');
            const percentage = summary.percentage_change;
            percentageEl.textContent = `${percentage > 0 ? '+' : ''}${percentage}%`;
            percentageEl.className = percentage >= 0 ? 'text-green-500' : 'text-red-500';

            // Destroy previous chart if exists
            if (activityChart) {
                activityChart.destroy();
            }

            // Render Chart
            const ctx = document.getElementById('activityChart1').getContext('2d');
            activityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Platform Activity',
                        data: chartData.data,
                        borderColor: Utils.CHART_COLORS.blue,
                        backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.6),
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    // maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Update Content Engagement Chart
        function updateContentChart(contentData) {
            if (!contentData || !contentData.summary || !contentData.chart) {
                console.error('Invalid content data');
                return;
            }

            const summary = contentData.summary;
            const chartData = contentData.chart;

            // Update angka dan persentase
            document.getElementById('content-total').textContent = formatNumber(summary.total_last_30_days) + ' Posts';

            const percentageEl = document.getElementById('content-percentage');
            const percentage = summary.percentage_change;
            percentageEl.textContent = `${percentage > 0 ? '+' : ''}${percentage}%`;
            percentageEl.className = percentage >= 0 ? 'text-green-500' : 'text-red-500';

            // Destroy previous chart if exists
            if (contentChart) {
                contentChart.destroy();
            }

            // Render Chart
            const ctx = document.getElementById('activityChart2').getContext('2d');
            contentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Content Engagement',
                        data: chartData.data,
                        backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.6),
                        borderColor: Utils.CHART_COLORS.red,
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    // maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Helper function untuk format angka dengan koma
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Show error message
        function showError(message) {
            console.error(message);
            // Anda bisa tambahkan notifikasi UI di sini
        }

        // Panggil saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();

            // Optional: Refresh data setiap 5 menit
            setInterval(loadDashboardData, 300000); // 300000ms = 5 menit
        });
    </script>
</body>

</html>