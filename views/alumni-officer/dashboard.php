<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-user-clock"></i>
            </div>
            <h3><?= number_format($stats['pending_alumni']) ?></h3>
            <p>Pending Verifications</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-users"></i>
            </div>
            <h3><?= number_format($stats['total_alumni']) ?></h3>
            <p>Total Alumni</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3><?= number_format($stats['upcoming_events']) ?></h3>
            <p>Upcoming Events</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);">
                <i class="fas fa-comments"></i>
            </div>
            <h3><?= number_format($stats['forum_posts']) ?></h3>
            <p>Forum Topics</p>
        </div>
    </div>
</div>

<!-- Charts and Recent Activity -->
<div class="row g-4">
    <!-- Monthly Registrations Chart -->
    <div class="col-md-8">
        <div class="stat-card" style="height: 450px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0" style="color: #111827; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-line" style="color: #dc2626;"></i>
                    Alumni Statistics
                </h5>
            </div>
            <?php if (!empty($stats['monthly_registrations'])): ?>
                <canvas id="registrationsChart"></canvas>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-chart-line" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
                    <p class="text-muted">No registration data available yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-md-4">
        <div class="stat-card" style="height: 450px;">
            <h5 class="mb-4" style="color: #111827; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clock" style="color: #dc2626;"></i>
                Recent Activity
            </h5>
            <div style="max-height: 360px; overflow-y: auto;">
                <?php if (empty($stats['recent_activities'])): ?>
                    <p class="text-muted text-center py-4">No recent activities</p>
                <?php else: ?>
                    <?php foreach ($stats['recent_activities'] as $activity): ?>
                        <div class="d-flex align-items-start gap-3 mb-3 p-3" style="background: #fef2f2; border-radius: 8px; border-left: 3px solid #dc2626;">
                            <div>
                                <div style="font-weight: 600; color: #dc2626; font-size: 13px;"><?= htmlspecialchars($activity['activity']) ?></div>
                                <div style="color: #6b7280; font-size: 12px; margin-top: 2px;"><?= htmlspecialchars($activity['detail']) ?></div>
                                <div style="color: #9ca3af; font-size: 11px; margin-top: 4px;">
                                    <i class="far fa-clock"></i> <?= isset($activity['sort_date']) ? date('M d, Y', strtotime($activity['sort_date'])) : 'Recently' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="stat-card">
            <h5 class="mb-4" style="color: #111827; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-bolt" style="color: #dc2626;"></i>
                Quick Actions
            </h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="/scratch/alumni-officer.php?page=verify-alumni" class="btn btn-lg w-100" style="background: white; color: #dc2626; border: 2px solid #dc2626; border-radius: 10px; padding: 20px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-user-check d-block mb-2" style="font-size: 28px;"></i>
                        Verify Alumni
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/scratch/alumni-officer.php?page=announcements" class="btn btn-lg w-100" style="background: #dc2626; color: white; border: 2px solid #dc2626; border-radius: 10px; padding: 20px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-bullhorn d-block mb-2" style="font-size: 28px;"></i>
                        Post Announcement
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/scratch/alumni-officer.php?page=events" class="btn btn-lg w-100" style="background: white; color: #dc2626; border: 2px solid #dc2626; border-radius: 10px; padding: 20px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-calendar-plus d-block mb-2" style="font-size: 28px;"></i>
                        Create Event
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/scratch/alumni-officer.php?page=reports" class="btn btn-lg w-100" style="background: #dc2626; color: white; border: 2px solid #dc2626; border-radius: 10px; padding: 20px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-chart-bar d-block mb-2" style="font-size: 28px;"></i>
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25) !important;
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($stats['monthly_registrations'])): ?>
    // Monthly Registrations Chart
    const ctx = document.getElementById('registrationsChart');
    if (ctx) {
        const chartCtx = ctx.getContext('2d');
        const monthlyData = <?= json_encode(array_reverse($stats['monthly_registrations'])) ?>;
        
        const labels = monthlyData.map(item => {
            if (item.month) {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }
            return 'N/A';
        });
        const data = monthlyData.map(item => item.count || 0);
        
        new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Alumni Count',
                    data: data,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#dc2626',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    <?php endif; ?>
});
</script>
