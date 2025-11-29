<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sinergi</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Members</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/userCount.svg" class="size-10">
            </div>
            <h1 id="total-anggota" class="text-xl font-bold mt-4"><?= $anggotaCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#2563EB] line-clamp-1">Across All Roles</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Posts</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/postAcctive.svg" class="size-10">
            </div>
            <h1 id="total-posts" class="text-xl font-bold mt-4"><?= $postCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#4338CA] line-clamp-1">From All Users</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Forums</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/forums.svg" class="size-10">
            </div>
            <h1 id="total-forums" class="text-xl font-bold mt-4"><?= $forumCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#7C3AED] line-clamp-1">Created</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Total Grop</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/group.svg" class="size-10">
            </div>
            <h1 id="total-forums" class="text-xl font-bold mt-4"><?= $groupCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#C163A6] line-clamp-1">Created</p>
        </div>
        <div class="bg-white drop-shadow rounded-xl p-4">
            <div class="flex justify-between items-center">
                <p class="text-lg font-medium text-gray-700">Reports</p>
                <img src="<?php echo BASEURL ?>/src/asset/icons/reportCount.svg" class="size-10">
            </div>
            <h1 id="total-laporan" class="text-xl font-bold mt-4"><?= $laporanCount ?? 0 ?></h1>
            <p class="text-sm md:text-md font-medium text-[#EF4444] line-clamp-1">Received</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:mt-10 mt-4">
        <div class="bg-white drop-shadow rounded-xl p-4 w-full">
            <p class="text-gray-700">Forum Engagement</p>
            <h1 id="activity-total" class="text-2xl font-bold mt-2">
                <span class="loading-skeleton">...</span>
            </h1>
            <p class="text-md text-gray-500 mb-4">
                This Month
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
                This Month
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
                const response = await fetch('<?php echo BASEURL ?>/getDashboardDataApi');

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                console.log('Data from API:', data);

                if (data.success) {
                    // Update overview counts
                    updateOverviewCounts(data.overview_counts);

                    // Update Forum Engagement Chart (Biru)
                    updateActivityChart(data.forum_engagement);

                    // Update Posts Engagement Chart (Pink/Red)
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

        // Update Forum Engagement Chart
        function updateActivityChart(activityData) {
            if (!activityData || !activityData.summary || !activityData.chart) {
                console.error('Invalid activity data:', activityData);
                return;
            }

            const summary = activityData.summary;
            const chartData = activityData.chart;

            // Log untuk debugging
            console.log('Forum Summary:', summary);

            // Update angka dan persentase (dengan default value)
            const totalThisMonth = summary.total_this_month ?? 0;
            document.getElementById('activity-total').textContent = formatNumber(totalThisMonth) + ' Forums';

            const percentageEl = document.getElementById('activity-percentage');
            const percentage = summary.percentage_change ?? 0;

            // Format persentase dengan + atau - dan "vs last month"
            if (percentage > 0) {
                percentageEl.textContent = `+${percentage}% vs last month`;
                percentageEl.className = 'text-green-500 font-medium';
            } else if (percentage < 0) {
                percentageEl.textContent = `${percentage}% vs last month`;
                percentageEl.className = 'text-red-500 font-medium';
            } else {
                percentageEl.textContent = `${percentage}% vs last month`;
                percentageEl.className = 'text-gray-500 font-medium';
            }

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
                        label: 'Forum Engagement',
                        data: chartData.data,
                        borderColor: Utils.CHART_COLORS.blue,
                        backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.6),
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
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

        // Update Posts Engagement Chart
        function updateContentChart(contentData) {
            if (!contentData || !contentData.summary || !contentData.chart) {
                console.error('Invalid content data:', contentData);
                return;
            }

            const summary = contentData.summary;
            const chartData = contentData.chart;

            // Log untuk debugging
            console.log('Posts Summary:', summary);

            // Update angka dan persentase (dengan default value)
            const totalThisMonth = summary.total_this_month ?? 0;
            document.getElementById('content-total').textContent = formatNumber(totalThisMonth) + ' Posts';

            const percentageEl = document.getElementById('content-percentage');
            const percentage = summary.percentage_change ?? 0;

            // Format persentase dengan + atau - dan "vs last month"
            if (percentage > 0) {
                percentageEl.textContent = `+${percentage}% vs last month`;
                percentageEl.className = 'text-green-500 font-medium';
            } else if (percentage < 0) {
                percentageEl.textContent = `${percentage}% vs last month`;
                percentageEl.className = 'text-red-500 font-medium';
            } else {
                percentageEl.textContent = `${percentage}% vs last month`;
                percentageEl.className = 'text-gray-500 font-medium';
            }

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
                        label: 'Posts Engagement',
                        data: chartData.data,
                        backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.6),
                        borderColor: Utils.CHART_COLORS.red,
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
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
            // Handle undefined, null, atau bukan angka
            if (num === undefined || num === null || isNaN(num)) {
                return '0';
            }
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Show error message
        function showError(message) {
            console.error(message);
        }

        // Panggil saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();

            // Optional: Refresh data setiap 5 menit
            setInterval(loadDashboardData, 300000);
        });
    </script>
</body>

</html>