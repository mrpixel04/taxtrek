<?php
// Include database connection
include("db_connect.php");

// Get users for display
$users = [];
if ($connection !== null) {
    $sql = "SELECT * FROM TBL_USERS ORDER BY fullname ASC";
    $result = mysqli_query($connection, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
}
?>

<div class="page-card">
    <div class="card-header">
        <h4><i class="fas fa-users-cog me-2"></i>Pengurusan Pengguna</h4>
        <p class="mb-0 text-muted">Urus dan tambah pengguna sistem</p>
    </div>
    <div class="card-body">
        <!-- Toast Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="toastMessage">Pengguna baru telah ditambah!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Users Table -->
            <div class="col-lg-7 col-md-6">
                <div class="users-table-card">
                    <div class="table-header">
                        <h5><i class="fas fa-list me-2"></i>Senarai Pengguna</h5>
                        
                        <!-- Search Bar -->
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchUsers" placeholder="Cari nama, email, telefon atau no gaji...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>No Gaji</th>
                                    <th>Nama Penuh</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Bayaran</th>
                                    <th>Hubungi</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr class="user-row" style="cursor: pointer;" data-user='<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8'); ?>' title="Klik untuk lihat butiran pengguna">
                                            <td>
                                                <div class="user-info">
                                                    <i class="fas fa-id-card me-2 text-primary"></i>
                                                    <strong><?php echo htmlspecialchars($user['no_gaji']); ?></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="user-details">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($user['fullname']); ?></div>
                                                    <?php if (!empty($user['email'])): ?>
                                                        <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['userlevel'] === 'ADMIN' ? 'danger' : 'primary'; ?>">
                                                    <i class="fas fa-<?php echo $user['userlevel'] === 'ADMIN' ? 'shield-alt' : 'user'; ?> me-1"></i>
                                                    <?php echo $user['userlevel']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['isactive'] === 'ACTIVE' ? 'success' : 'secondary'; ?>">
                                                    <i class="fas fa-<?php echo $user['isactive'] === 'ACTIVE' ? 'check' : 'times'; ?> me-1"></i>
                                                    <?php echo $user['isactive']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['ispaid'] === 'PAID' ? 'success' : 'warning'; ?>">
                                                    <i class="fas fa-<?php echo $user['ispaid'] === 'PAID' ? 'credit-card' : 'clock'; ?> me-1"></i>
                                                    <?php echo $user['ispaid']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($user['hpno'])): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i>
                                                        <?php echo htmlspecialchars($user['hpno']); ?>
                                                    </small>
                                                <?php else: ?>
                                                    <small class="text-muted">-</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted">Tiada pengguna dijumpai</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Jumlah pengguna: <span id="userCount"><?php echo count($users); ?></span>
                            </small>
                            <small class="text-muted" id="pageInfo">
                                <i class="fas fa-file-alt me-1"></i>
                                Menunjukkan <span id="showingStart">1</span> - <span id="showingEnd">10</span> daripada <span id="totalRows"><?php echo count($users); ?></span>
                            </small>
                        </div>
                        
                        <!-- Custom Pagination -->
                        <div class="pagination-container" id="paginationContainer">
                            <nav aria-label="User pagination">
                                <ul class="pagination pagination-sm justify-content-center mb-0 mt-3">
                                    <li class="page-item" id="prevPage">
                                        <button class="page-link" type="button">
                                            <i class="fas fa-chevron-left me-1"></i>Sebelum
                                        </button>
                                    </li>
                                    <li class="page-item active" id="currentPageDisplay">
                                        <span class="page-link">
                                            Halaman <span id="currentPageNum">1</span> daripada <span id="totalPages">1</span>
                                        </span>
                                    </li>
                                    <li class="page-item" id="nextPage">
                                        <button class="page-link" type="button">
                                            Seterusnya<i class="fas fa-chevron-right ms-1"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Add User Form -->
            <div class="col-lg-5 col-md-6">
                <div class="add-user-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru</h5>
                        <p class="mb-0 text-muted">Lengkapkan maklumat untuk menambah pengguna</p>
                    </div>

                    <form id="addUserForm" class="needs-validation" novalidate>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="no_gaji" name="no_gaji" placeholder="No Gaji" required>
                            <label for="no_gaji"><i class="fas fa-id-badge me-2"></i>No Gaji</label>
                            <div class="invalid-feedback">Sila masukkan No Gaji</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nama Penuh" required>
                            <label for="fullname"><i class="fas fa-user me-2"></i>Nama Penuh</label>
                            <div class="invalid-feedback">Sila masukkan Nama Penuh</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                            <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control" id="hpno" name="hpno" placeholder="No Telefon">
                            <label for="hpno"><i class="fas fa-phone me-2"></i>No Telefon</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-floating">
                                    <select class="form-select" id="userlevel" name="userlevel" required>
                                        <option value="CUSTOMER">CUSTOMER</option>
                                        <option value="ADMIN">ADMIN</option>
                                    </select>
                                    <label for="userlevel"><i class="fas fa-shield-alt me-2"></i>Level</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <select class="form-select" id="isactive" name="isactive" required>
                                        <option value="ACTIVE">ACTIVE</option>
                                        <option value="NOT ACTIVE">NOT ACTIVE</option>
                                    </select>
                                    <label for="isactive"><i class="fas fa-toggle-on me-2"></i>Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="ispaid" name="ispaid" required>
                                <option value="NOT PAID">NOT PAID</option>
                                <option value="PAID">PAID</option>
                            </select>
                            <label for="ispaid"><i class="fas fa-credit-card me-2"></i>Status Bayaran</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="katalaluan" name="katalaluan" placeholder="Katalaluan" required>
                            <label for="katalaluan"><i class="fas fa-lock me-2"></i>Katalaluan</label>
                            <div class="invalid-feedback">Sila masukkan Katalaluan</div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span id="submitText">Tambah Pengguna</span>
                            <div class="spinner-border spinner-border-sm ms-2 d-none" id="submitSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for User Details -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title" id="userDetailsModalLabel">
                    <i class="fas fa-user-circle me-2"></i>Butiran Pengguna
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- User Info Column -->
                    <div class="col-md-8">
                        <div class="user-details-section">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>Maklumat Peribadi
                            </h6>
                            <div class="detail-item">
                                <label>No Gaji:</label>
                                <span id="detail-no-gaji" class="fw-bold text-primary"></span>
                            </div>
                            <div class="detail-item">
                                <label>Nama Penuh:</label>
                                <span id="detail-fullname" class="fw-bold"></span>
                            </div>
                            <div class="detail-item">
                                <label>Email:</label>
                                <span id="detail-email"></span>
                            </div>
                            <div class="detail-item">
                                <label>No Telefon:</label>
                                <span id="detail-hpno"></span>
                            </div>
                        </div>

                        <div class="user-details-section">
                            <h6 class="section-title">
                                <i class="fas fa-cogs me-2"></i>Status Akaun
                            </h6>
                            <div class="detail-item">
                                <label>Level Pengguna:</label>
                                <span id="detail-userlevel" class="badge"></span>
                            </div>
                            <div class="detail-item">
                                <label>Status Aktif:</label>
                                <span id="detail-isactive" class="badge"></span>
                            </div>
                            <div class="detail-item">
                                <label>Status Bayaran:</label>
                                <span id="detail-ispaid" class="badge"></span>
                            </div>
                            <div class="detail-item">
                                <label>Log Masuk Terakhir:</label>
                                <span id="detail-last-login"></span>
                            </div>
                            <div class="detail-item">
                                <label>Tarikh Daftar:</label>
                                <span id="detail-created-at"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Management Column -->
                    <div class="col-md-4">
                        <div class="password-section">
                            <h6 class="section-title text-warning">
                                <i class="fas fa-key me-2"></i>Pengurusan Katalaluan
                            </h6>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Katalaluan disulitkan untuk keselamatan</small>
                            </div>
                            
                            <div class="password-actions">
                                <button class="btn btn-warning btn-sm w-100 mb-2" id="resetPasswordBtn">
                                    <i class="fas fa-sync-alt me-2"></i>Reset Katalaluan
                                </button>
                                <button class="btn btn-success btn-sm w-100" id="generateTempPasswordBtn">
                                    <i class="fas fa-magic me-2"></i>Jana Katalaluan Sementara
                                </button>
                            </div>

                            <div id="tempPasswordDisplay" class="mt-3 d-none">
                                <div class="alert alert-success">
                                    <strong>Katalaluan Baru:</strong>
                                    <div class="temp-password-box">
                                        <code id="tempPasswordText"></code>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" id="copyPasswordBtn" title="Salin">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <small class="d-block mt-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Sila beritahu pengguna
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary" id="editUserBtn">
                    <i class="fas fa-edit me-2"></i>Edit Pengguna
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for user management page */
.users-table-card, .add-user-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.5rem;
    margin: -1px -1px 0 -1px;
}

.table-header h5 {
    margin: 0 0 1rem 0;
    font-weight: 600;
}

.search-container {
    max-width: 400px;
}

.card-header-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.5rem;
    margin: -1px -1px 0 -1px;
}

.card-header-custom h5 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.add-user-card form {
    padding: 1.5rem;
}

.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    border-bottom: 2px solid #dee2e6;
    background-color: var(--bs-dark) !important;
}

.table td {
    font-size: 0.85rem;
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-details .fw-bold {
    color: var(--primary-color);
    font-size: 0.9rem;
}

.table-footer {
    padding: 1rem 1.5rem;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.form-floating > label {
    font-weight: 500;
    color: #6c757d;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select ~ label {
    color: var(--primary-color);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
}

.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
}

.toast {
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.search-animation {
    transition: all 0.3s ease;
}

.search-animation:focus {
    transform: scale(1.02);
    box-shadow: 0 0 20px rgba(0, 123, 255, 0.2);
}

/* Custom Pagination Styles */
.pagination-container {
    margin-top: 1rem;
}

.pagination {
    --bs-pagination-padding-x: 0.75rem;
    --bs-pagination-padding-y: 0.5rem;
    --bs-pagination-font-size: 0.85rem;
    --bs-pagination-color: var(--primary-color);
    --bs-pagination-bg: rgba(255, 255, 255, 0.8);
    --bs-pagination-border-width: 1px;
    --bs-pagination-border-color: rgba(0, 123, 255, 0.2);
    --bs-pagination-border-radius: 8px;
    --bs-pagination-hover-color: white;
    --bs-pagination-hover-bg: var(--primary-color);
    --bs-pagination-hover-border-color: var(--primary-color);
    --bs-pagination-focus-color: white;
    --bs-pagination-focus-bg: var(--primary-color);
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    --bs-pagination-active-color: white;
    --bs-pagination-active-bg: var(--primary-color);
    --bs-pagination-active-border-color: var(--primary-color);
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-bg: rgba(255, 255, 255, 0.5);
    --bs-pagination-disabled-border-color: rgba(0, 0, 0, 0.1);
}

.pagination .page-link {
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 8px !important;
    margin: 0 2px;
}

.pagination .page-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

#pageInfo {
    font-weight: 500;
}

/* Hide pagination when not needed */
.pagination-container.d-none {
    display: none !important;
}

/* User Details Modal Styles */
.user-row:hover {
    background-color: rgba(0, 123, 255, 0.1) !important;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.user-details-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

.section-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(0, 123, 255, 0.1);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item label {
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 0;
    min-width: 120px;
}

.detail-item span {
    flex: 1;
    text-align: right;
}

.password-section {
    background-color: #fff3cd;
    padding: 1rem;
    border-radius: 10px;
    border: 2px solid #ffc107;
}

.password-actions button {
    font-weight: 500;
    border-radius: 8px;
}

.temp-password-box {
    display: flex;
    align-items: center;
    background-color: white;
    padding: 0.5rem;
    border-radius: 5px;
    border: 1px solid #dee2e6;
    margin-top: 0.5rem;
}

.temp-password-box code {
    background: none;
    color: #198754;
    font-weight: bold;
    font-size: 1.1rem;
    flex: 1;
}

.modal-header.bg-gradient {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
}

/* Additional modal enhancements */
.modal-dialog {
    max-width: 900px;
}

.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 15px 15px;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .col-lg-7, .col-lg-5 {
        margin-bottom: 1.5rem;
    }
    
    .table-responsive {
        max-height: 400px;
    }
}

@media (max-width: 768px) {
    .table-header, .card-header-custom {
        padding: 1rem;
    }
    
    .add-user-card form {
        padding: 1rem;
    }
    
    .row.mb-3 .col-6 {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addUserForm = document.getElementById('addUserForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const searchInput = document.getElementById('searchUsers');
    const clearSearchBtn = document.getElementById('clearSearch');
    const usersTableBody = document.getElementById('usersTableBody');
    const userCountSpan = document.getElementById('userCount');

    // Pagination variables
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredRows = [];
    let allRows = [];

    // Get all rows initially
    function initializeRows() {
        allRows = Array.from(usersTableBody.querySelectorAll('tr')).filter(row => row.cells.length > 1);
        filteredRows = [...allRows];
        updatePagination();
    }

    // Search and filter functionality
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        
        filteredRows = allRows.filter(row => {
            const noGaji = row.cells[0].textContent.toLowerCase();
            const nama = row.cells[1].textContent.toLowerCase();
            const email = row.cells[1].querySelector('small')?.textContent.toLowerCase() || '';
            const telefon = row.cells[5].textContent.toLowerCase();

            return noGaji.includes(searchTerm) || 
                   nama.includes(searchTerm) || 
                   email.includes(searchTerm) || 
                   telefon.includes(searchTerm);
        });

        currentPage = 1; // Reset to first page when searching
        updatePagination();
        displayCurrentPage();
    }

    // Display current page rows
    function displayCurrentPage() {
        // Hide all rows first
        allRows.forEach(row => row.style.display = 'none');

        // Calculate start and end indices
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        
        // Show only current page rows
        const currentPageRows = filteredRows.slice(startIndex, endIndex);
        currentPageRows.forEach(row => row.style.display = '');

        // Update counters
        userCountSpan.textContent = filteredRows.length;
        
        // Update pagination info
        updatePaginationInfo();
    }

    // Update pagination controls
    function updatePagination() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        const paginationContainer = document.getElementById('paginationContainer');
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const currentPageNum = document.getElementById('currentPageNum');
        const totalPagesSpan = document.getElementById('totalPages');

        // Update page numbers
        currentPageNum.textContent = currentPage;
        totalPagesSpan.textContent = totalPages;

        // Enable/disable navigation buttons
        prevBtn.classList.toggle('disabled', currentPage === 1);
        nextBtn.classList.toggle('disabled', currentPage === totalPages || totalPages === 0);

        // Show/hide pagination if needed
        if (totalPages <= 1) {
            paginationContainer.classList.add('d-none');
        } else {
            paginationContainer.classList.remove('d-none');
        }
    }

    // Update pagination info text
    function updatePaginationInfo() {
        const startNum = filteredRows.length === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
        const endNum = Math.min(currentPage * rowsPerPage, filteredRows.length);
        const totalNum = filteredRows.length;

        document.getElementById('showingStart').textContent = startNum;
        document.getElementById('showingEnd').textContent = endNum;
        document.getElementById('totalRows').textContent = totalNum;
    }

    // Pagination event listeners
    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            displayCurrentPage();
        }
    });

    document.getElementById('nextPage').addEventListener('click', function() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
            displayCurrentPage();
        }
    });

    // Search event listeners
    searchInput.addEventListener('input', filterUsers);
    searchInput.addEventListener('focus', function() {
        this.classList.add('search-animation');
    });
    searchInput.addEventListener('blur', function() {
        this.classList.remove('search-animation');
    });

    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterUsers();
        searchInput.focus();
    });

    // Form submission
    addUserForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        // Show loading state
        submitText.textContent = 'Menambah...';
        submitSpinner.classList.remove('d-none');
        submitBtn.disabled = true;

        // Prepare form data
        const formData = new FormData(this);

        // Send AJAX request to dedicated handler
        fetch('add_user_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success toast
                showToast(data.message, 'success');
                
                // Reset form
                this.reset();
                this.classList.remove('was-validated');
                
                // Reload page to show new user and reinitialize
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
                
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Ralat sistem berlaku. Sila cuba lagi.', 'error');
        })
        .finally(() => {
            // Reset button state
            submitText.textContent = 'Tambah Pengguna';
            submitSpinner.classList.add('d-none');
            submitBtn.disabled = false;
        });
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.getElementById('successToast');
        const toastMessage = document.getElementById('toastMessage');
        
        // Update message
        toastMessage.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}`;
        
        // Update colors
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        
        // Show toast with animation
        const bsToast = new bootstrap.Toast(toast, {
            animation: true,
            autohide: true,
            delay: 4000
        });
        bsToast.show();
    }

    // User row click functionality
    function setupUserRowClicks() {
        document.querySelectorAll('.user-row').forEach(row => {
            row.addEventListener('click', function() {
                const userData = JSON.parse(this.getAttribute('data-user'));
                showUserDetails(userData);
            });
        });
    }

    // Show user details in modal
    function showUserDetails(user) {
        // Personal Information
        document.getElementById('detail-no-gaji').textContent = user.no_gaji || 'N/A';
        document.getElementById('detail-fullname').textContent = user.fullname || 'N/A';
        document.getElementById('detail-email').textContent = user.email || 'Tiada email';
        document.getElementById('detail-hpno').textContent = user.hpno || 'Tiada telefon';

        // Account Status
        const userLevelBadge = document.getElementById('detail-userlevel');
        userLevelBadge.textContent = user.userlevel;
        userLevelBadge.className = `badge bg-${user.userlevel === 'ADMIN' ? 'danger' : 'primary'}`;

        const statusBadge = document.getElementById('detail-isactive');
        statusBadge.textContent = user.isactive;
        statusBadge.className = `badge bg-${user.isactive === 'ACTIVE' ? 'success' : 'secondary'}`;

        const paymentBadge = document.getElementById('detail-ispaid');
        paymentBadge.textContent = user.ispaid;
        paymentBadge.className = `badge bg-${user.ispaid === 'PAID' ? 'success' : 'warning'}`;

        // Format dates
        const lastLogin = user.last_login_datetime ? 
            new Date(user.last_login_datetime).toLocaleString('ms-MY') : 'Belum pernah log masuk';
        document.getElementById('detail-last-login').textContent = lastLogin;

        const createdAt = user.created_at ? 
            new Date(user.created_at).toLocaleString('ms-MY') : 'Tidak diketahui';
        document.getElementById('detail-created-at').textContent = createdAt;

        // Store current user ID for password operations
        document.getElementById('userDetailsModal').setAttribute('data-user-id', user.id);
        document.getElementById('userDetailsModal').setAttribute('data-user-no-gaji', user.no_gaji);

        // Reset password display
        document.getElementById('tempPasswordDisplay').classList.add('d-none');

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
        modal.show();
    }

    // Password management functionality
    document.getElementById('generateTempPasswordBtn').addEventListener('click', function() {
        const userId = document.getElementById('userDetailsModal').getAttribute('data-user-id');
        const userNoGaji = document.getElementById('userDetailsModal').getAttribute('data-user-no-gaji');
        
        if (!userId) {
            showToast('Ralat: Pengguna tidak dijumpai', 'error');
            return;
        }

        // Generate temporary password
        const tempPassword = generateRandomPassword();
        
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menjana...';
        this.disabled = true;

        // Send request to update password
        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('new_password', tempPassword);

        fetch('reset_password_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show the temporary password
                document.getElementById('tempPasswordText').textContent = tempPassword;
                document.getElementById('tempPasswordDisplay').classList.remove('d-none');
                showToast(`Katalaluan baru untuk ${userNoGaji} telah dijana!`, 'success');
            } else {
                showToast(data.message || 'Ralat menjana katalaluan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Ralat sistem berlaku', 'error');
        })
        .finally(() => {
            // Reset button
            this.innerHTML = '<i class="fas fa-magic me-2"></i>Jana Katalaluan Sementara';
            this.disabled = false;
        });
    });

    // Reset password button (prompts for new password)
    document.getElementById('resetPasswordBtn').addEventListener('click', function() {
        const userId = document.getElementById('userDetailsModal').getAttribute('data-user-id');
        const userNoGaji = document.getElementById('userDetailsModal').getAttribute('data-user-no-gaji');
        
        if (!userId) {
            showToast('Ralat: Pengguna tidak dijumpai', 'error');
            return;
        }

        // Use SweetAlert for password input
        Swal.fire({
            title: 'Reset Katalaluan',
            text: `Masukkan katalaluan baru untuk ${userNoGaji}:`,
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off',
                placeholder: 'Katalaluan baru...'
            },
            showCancelButton: true,
            confirmButtonText: 'Reset',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#007bff',
            inputValidator: (value) => {
                if (!value || value.length < 6) {
                    return 'Katalaluan mestilah sekurang-kurangnya 6 aksara!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const newPassword = result.value;
                
                // Send request to update password
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('new_password', newPassword);

                fetch('reset_password_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(`Katalaluan untuk ${userNoGaji} telah dikemaskini!`, 'success');
                    } else {
                        showToast(data.message || 'Ralat mengemas kini katalaluan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Ralat sistem berlaku', 'error');
                });
            }
        });
    });

    // Copy password functionality
    document.getElementById('copyPasswordBtn').addEventListener('click', function() {
        const passwordText = document.getElementById('tempPasswordText').textContent;
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(passwordText).then(() => {
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-copy"></i>';
                }, 2000);
                showToast('Katalaluan telah disalin!', 'success');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = passwordText;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
            showToast('Katalaluan telah disalin!', 'success');
        }
    });

    // Generate random password function
    function generateRandomPassword(length = 8) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        return password;
    }

    // Initialize pagination and user clicks on page load
    initializeRows();
    displayCurrentPage();
    setupUserRowClicks();

    console.log('User management with details modal initialized successfully');
});
</script> 