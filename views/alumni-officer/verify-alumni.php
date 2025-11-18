<!-- Pending Alumni Accounts -->
<div class="stat-card mb-4">
    <h5 class="mb-4" style="color: #7f1d1d; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-user-clock" style="color: #f59e0b;"></i>
        Pending Alumni Accounts (<?= count($pendingAlumni) ?>)
    </h5>
    
    <?php if (empty($pendingAlumni)): ?>
        <div class="text-center py-5">
            <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 15px;"></i>
            <p class="text-muted mb-0">No pending accounts to verify</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover" style="border-radius: 12px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white;">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>User ID</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingAlumni as $alumni): ?>
                        <tr>
                            <td style="font-weight: 600; color: #7f1d1d;">
                                <?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>
                            </td>
                            <td><?= htmlspecialchars($alumni['username']) ?></td>
                            <td><?= htmlspecialchars($alumni['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['course'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['batch'] ?? 'N/A') ?></td>
                            <td>User #<?= $alumni['id'] ?></td>
                            <td class="text-center">
                                <form method="post" style="display: inline-block;" onsubmit="return confirm('Approve this alumni account?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="id" value="<?= $alumni['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px; padding: 6px 16px;">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form method="post" style="display: inline-block;" onsubmit="return confirm('Reject and delete this account?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="id" value="<?= $alumni['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; padding: 6px 16px;">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Verified Alumni -->
<div class="stat-card">
    <h5 class="mb-4" style="color: #7f1d1d; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-user-check" style="color: #10b981;"></i>
        Verified Alumni (<?= count($verifiedAlumni) ?>)
    </h5>
    
    <?php if (empty($verifiedAlumni)): ?>
        <div class="text-center py-5">
            <i class="fas fa-users" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
            <p class="text-muted mb-0">No verified alumni yet</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover" style="border-radius: 12px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>User ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifiedAlumni as $alumni): ?>
                        <tr>
                            <td style="font-weight: 600; color: #7f1d1d;">
                                <?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>
                            </td>
                            <td><?= htmlspecialchars($alumni['username']) ?></td>
                            <td><?= htmlspecialchars($alumni['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['course'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['batch'] ?? 'N/A') ?></td>
                            <td>User #<?= $alumni['id'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.table {
    font-size: 14px;
}
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 14px;
}
.table tbody td {
    padding: 14px;
    vertical-align: middle;
}
.table tbody tr {
    transition: all 0.3s ease;
}
.table tbody tr:hover {
    background-color: rgba(220, 38, 38, 0.05);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
</style>

