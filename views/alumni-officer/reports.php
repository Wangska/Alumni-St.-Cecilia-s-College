<!-- Key Statistics Overview -->
<div class="row g-4 mb-4">
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
            <div class="icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-user-clock"></i>
            </div>
            <h3><?= number_format($stats['pending_alumni']) ?></h3>
            <p>Pending Accounts</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <i class="fas fa-calendar"></i>
            </div>
            <h3><?= number_format($stats['total_events']) ?></h3>
            <p>Total Events</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                <i class="fas fa-bullhorn"></i>
            </div>
            <h3><?= number_format($stats['total_news']) ?></h3>
            <p>Announcements</p>
        </div>
    </div>
</div>

<!-- Section Divider -->
<div class="section-divider">
    <h4 style="color: #dc2626; font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0;">
        <i class="fas fa-bullhorn"></i>
        Announcements Reports
    </h4>
</div>

<!-- Announcements Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #dc2626;">
            <div class="stat-icon-small" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div>
                <h4 style="color: #dc2626; font-weight: 700; margin-bottom: 5px;"><?= number_format($announcementStats['total']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Total Announcements</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #059669;">
            <div class="stat-icon-small" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h4 style="color: #059669; font-weight: 700; margin-bottom: 5px;"><?= number_format($announcementStats['recent']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Posted (Last 30 Days)</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="height: 180px;">
            <h6 class="mb-3" style="color: #111827; font-weight: 600;">Announcements Trend (6 Months)</h6>
            <canvas id="announcementChart" style="height: 120px;"></canvas>
        </div>
    </div>
</div>

<!-- Recent Announcements Table -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="stat-card">
            <h6 class="mb-3" style="color: #111827; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-list" style="color: #dc2626;"></i>
                Recent Announcements
            </h6>
            <?php if (!empty($announcementStats['recentList'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #fef2f2;">
                            <tr>
                                <th style="color: #dc2626; font-weight: 600;">Title</th>
                                <th style="color: #dc2626; font-weight: 600;">Date Posted</th>
                                <th style="color: #dc2626; font-weight: 600; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($announcementStats['recentList'] as $announcement): ?>
                                <tr>
                                    <td style="color: #111827;"><?= htmlspecialchars($announcement['title']) ?></td>
                                    <td style="color: #6b7280;"><?= date('M d, Y', strtotime($announcement['date_created'])) ?></td>
                                    <td style="text-align: center;">
                                        <a href="/scratch/alumni-officer.php?page=announcements" class="btn btn-sm" style="background: #dc2626; color: white; border-radius: 6px; padding: 4px 12px;">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-3">No announcements found</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Section Divider -->
<div class="section-divider">
    <h4 style="color: #dc2626; font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0;">
        <i class="fas fa-calendar-alt"></i>
        Events Reports
    </h4>
</div>

<!-- Events Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #dc2626;">
            <div class="stat-icon-small" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <i class="fas fa-calendar"></i>
            </div>
            <div>
                <h4 style="color: #dc2626; font-weight: 700; margin-bottom: 5px;"><?= number_format($eventStats['total']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Total Events</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #059669;">
            <div class="stat-icon-small" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h4 style="color: #059669; font-weight: 700; margin-bottom: 5px;"><?= number_format($eventStats['upcoming']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Upcoming Events</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #6b7280;">
            <div class="stat-icon-small" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <h4 style="color: #6b7280; font-weight: 700; margin-bottom: 5px;"><?= number_format($eventStats['past']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Past Events</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #f59e0b;">
            <div class="stat-icon-small" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h4 style="color: #f59e0b; font-weight: 700; margin-bottom: 5px;"><?= number_format($eventStats['totalParticipants']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Total Participants</p>
            </div>
        </div>
    </div>
</div>

<!-- Events Charts and Top Events -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="height: 300px;">
            <h6 class="mb-3" style="color: #111827; font-weight: 600;">Events Trend (6 Months)</h6>
            <canvas id="eventsChart" style="height: 220px;"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="height: 300px; overflow-y: auto;">
            <h6 class="mb-3" style="color: #111827; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                Top Attended Events
            </h6>
            <?php if (!empty($eventStats['topEvents'])): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($eventStats['topEvents'] as $index => $event): ?>
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge" style="background: <?= $index === 0 ? '#f59e0b' : '#dc2626' ?>; font-size: 10px; padding: 2px 8px;">
                                            #<?= $index + 1 ?>
                                        </span>
                                        <strong style="color: #111827; font-size: 14px;">
                                            <?= htmlspecialchars($event['title']) ?>
                                        </strong>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?= date('M d, Y', strtotime($event['schedule'])) ?>
                                    </small>
                                </div>
                                <span class="badge bg-success"><?= $event['participant_count'] ?> participants</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-3">No events found</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Section Divider -->
<div class="section-divider">
    <h4 style="color: #dc2626; font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0;">
        <i class="fas fa-comments"></i>
        Forum Reports
    </h4>
</div>

<!-- Forum Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #dc2626;">
            <div class="stat-icon-small" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <i class="fas fa-comments"></i>
            </div>
            <div>
                <h4 style="color: #dc2626; font-weight: 700; margin-bottom: 5px;"><?= number_format($forumStats['totalTopics']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Total Topics</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #059669;">
            <div class="stat-icon-small" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
                <i class="fas fa-comment"></i>
            </div>
            <div>
                <h4 style="color: #059669; font-weight: 700; margin-bottom: 5px;"><?= number_format($forumStats['totalComments']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Total Comments</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #3b82f6;">
            <div class="stat-icon-small" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="fas fa-fire"></i>
            </div>
            <div>
                <h4 style="color: #3b82f6; font-weight: 700; margin-bottom: 5px;"><?= number_format($forumStats['recentTopics']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Recent Topics</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-small" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-icon-small" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i class="fas fa-user-friends"></i>
            </div>
            <div>
                <h4 style="color: #8b5cf6; font-weight: 700; margin-bottom: 5px;"><?= number_format($forumStats['activeUsers']) ?></h4>
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Active Users</p>
            </div>
        </div>
    </div>
</div>

<!-- Most Active Forum Topics -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="stat-card">
            <h6 class="mb-3" style="color: #111827; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-fire" style="color: #f59e0b;"></i>
                Most Active Forum Topics
            </h6>
            <?php if (!empty($forumStats['activeTopics'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #fef2f2;">
                            <tr>
                                <th style="color: #dc2626; font-weight: 600;">Topic ID</th>
                                <th style="color: #dc2626; font-weight: 600;">Author</th>
                                <th style="color: #dc2626; font-weight: 600; text-align: center;">Comments</th>
                                <th style="color: #dc2626; font-weight: 600; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($forumStats['activeTopics'] as $topic): ?>
                                <tr>
                                    <td style="color: #111827;">Topic #<?= $topic['id'] ?></td>
                                    <td style="color: #6b7280;"><?= htmlspecialchars($topic['author_name'] ?? 'Unknown') ?></td>
                                    <td style="text-align: center;">
                                        <span class="badge" style="background: #059669;"><?= $topic['comment_count'] ?> comments</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="/scratch/alumni-officer.php?page=forum-view&id=<?= $topic['id'] ?>" class="btn btn-sm" style="background: #dc2626; color: white; border-radius: 6px; padding: 4px 12px;">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-3">No forum topics found</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Additional Styling -->
<style>
.section-divider {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    padding: 20px 24px;
    border-radius: 12px;
    margin: 40px 0 30px 0;
    border-left: 5px solid #dc2626;
}

.stat-card-small {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.stat-card-small:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.stat-icon-small {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
</style>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="stat-card" style="height: 450px;">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-line" style="color: #3b82f6;"></i>
                Alumni Registration Trend
            </h5>
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card" style="height: 450px;">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-pie" style="color: #8b5cf6;"></i>
                Course Distribution
            </h5>
            <canvas id="courseChart"></canvas>
        </div>
    </div>
</div>

<!-- Batch Distribution -->
<div class="row g-4">
    <div class="col-md-12">
        <div class="stat-card" style="max-height: 400px; overflow-y: auto;">
            <h5 class="mb-4" style="color: #dc2626; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-bar" style="color: #dc2626;"></i>
                Alumni by Batch Year
            </h5>
            <div style="height: 300px; position: relative;">
                <canvas id="batchChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Announcements Trend Chart
    const announcementCtx = document.getElementById('announcementChart');
    if (announcementCtx) {
        const announcementData = <?= json_encode($announcementStats['monthly']) ?>;
        
        const announcementLabels = announcementData.map(item => {
            const date = new Date(item.month + '-01');
            return date.toLocaleDateString('en-US', { month: 'short' });
        });
        const announcementCounts = announcementData.map(item => parseInt(item.count));
        
        new Chart(announcementCtx, {
            type: 'line',
            data: {
                labels: announcementLabels,
                datasets: [{
                    label: 'Announcements',
                    data: announcementCounts,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#dc2626',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 5,
                        right: 5,
                        top: 5,
                        bottom: 5
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            padding: 5
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            padding: 5
                        }
                    }
                }
            }
        });
    }
    
    // Events Trend Chart
    const eventsCtx = document.getElementById('eventsChart');
    if (eventsCtx) {
        const eventsData = <?= json_encode($eventStats['monthly']) ?>;
        
        const eventsLabels = eventsData.map(item => {
            const date = new Date(item.month + '-01');
            return date.toLocaleDateString('en-US', { month: 'short' });
        });
        const eventsCounts = eventsData.map(item => parseInt(item.count));
        
        new Chart(eventsCtx, {
            type: 'bar',
            data: {
                labels: eventsLabels,
                datasets: [{
                    label: 'Events',
                    data: eventsCounts,
                    backgroundColor: 'rgba(220, 38, 38, 0.8)',
                    borderColor: '#dc2626',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 5,
                        right: 5,
                        top: 5,
                        bottom: 5
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            padding: 5
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            padding: 5
                        }
                    }
                }
            }
        });
    }
    
    // Monthly Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const monthlyData = <?= json_encode(array_reverse($stats['monthly_data'])) ?>;
    
    const trendLabels = monthlyData.map(item => {
        const date = new Date(item.month + '-01');
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });
    const trendData = monthlyData.map(item => item.count);
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Registrations',
                data: trendData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // Course Distribution Chart
    const courseCtx = document.getElementById('courseChart').getContext('2d');
    const courseData = <?= json_encode($stats['course_distribution']) ?>;
    
    const courseLabels = courseData.map(item => item.course || 'Unknown');
    const courseCounts = courseData.map(item => item.count);
    
    new Chart(courseCtx, {
        type: 'doughnut',
        data: {
            labels: courseLabels,
            datasets: [{
                data: courseCounts,
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#8b5cf6',
                    '#ef4444',
                    '#06b6d4',
                    '#ec4899',
                    '#14b8a6',
                ],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                }
            }
        }
    });
    
    // Batch Distribution Chart
    const batchCtx = document.getElementById('batchChart');
    if (batchCtx) {
        const batchData = <?= json_encode($stats['batch_distribution']) ?>;
        
        // Limit to top 15 batches to prevent lag
        const sortedBatchData = batchData.sort((a, b) => b.count - a.count).slice(0, 15);
        
        const batchLabels = sortedBatchData.map(item => item.batch || 'Unknown');
        const batchCounts = sortedBatchData.map(item => parseInt(item.count));
        
        new Chart(batchCtx, {
            type: 'bar',
            data: {
                labels: batchLabels,
                datasets: [{
                    label: 'Alumni Count',
                    data: batchCounts,
                    backgroundColor: 'rgba(220, 38, 38, 0.8)',
                    borderColor: '#dc2626',
                    borderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 60,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Horizontal bars
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.x + ' alumni';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

