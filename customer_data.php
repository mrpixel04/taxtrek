<?php
// customer_data.php - Customer-specific data view
// Session is already checked in main.php, no need to redirect here

// Include database connection
include("db_connect.php");

$customer_data = [];
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
$base_url = dirname($base_url) . '/';

if ($connection !== null) {
    $user_id = $_SESSION['user_id'];
    $user_no_gaji = $_SESSION['user_no_gaji'];
    $user_fullname = $_SESSION['user_fullname'];
    
    // Get customer's data using correct field names from TBL_DATA
    $sql = "SELECT * FROM TBL_DATA WHERE 
            INSBY = '$user_id' OR 
            NAMAPEMILIK LIKE '%$user_fullname%' OR 
            NAMAPEMILIK_BODY LIKE '%$user_fullname%' 
            ORDER BY TARIKH_BUAT DESC";
    $result = mysqli_query($connection, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $customer_data[] = $row;
        }
    }
}
?>

<div class="page-card">
    <div class="card-header customer-data-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4><i class="fas fa-file-alt me-2"></i>Data Saya</h4>
                <p class="mb-0 text-light">Data dan rekod yang berkaitan dengan <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></p>
            </div>
            <div class="customer-badge">
                <span class="badge bg-light text-dark">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['user_no_gaji']); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Important Notice Section -->
        <div class="info-notice-top mb-4">
            <div class="notice-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="notice-content">
                <h5>📋 Maklumat Penting</h5>
                <p><strong>Tarikh Buat</strong> adalah tarikh turun di site untuk hantar Notis Tuntutan Tunggakan.</p>
            </div>
        </div>

        <!-- Bulk Date Update Section -->
        <div class="bulk-date-update-section mb-4">
            <div class="update-card">
                <div class="update-header">
                    <div class="update-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="update-title">
                        <h5>Kemaskini Tarikh Buat - Semua Rekod</h5>
                        <p class="mb-0">Pilih tarikh untuk kemaskini TARIKH_BUAT pada semua rekod data anda</p>
                    </div>
                </div>
                <div class="update-body">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="bulkDateUpdate" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Tarikh Baru:
                            </label>
                            <input type="date" 
                                   class="form-control modern-date-input" 
                                   id="bulkDateUpdate" 
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Kesan:</label>
                            <div class="impact-info">
                                <span class="impact-count" id="recordCount"><?php echo count($customer_data); ?></span>
                                <span class="impact-text">rekod akan dikemaskini</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <button type="button" 
                                    class="btn-update-all" 
                                    id="bulkUpdateDateBtn"
                                    onclick="updateAllDates()">
                                <i class="fas fa-sync-alt me-2"></i>
                                Kemaskini Semua Tarikh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Search Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-filters-container">
                    <h6 class="mb-3"><i class="fas fa-search me-2"></i>Carian Data</h6>
                    
                    <!-- Search Input Row -->
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="searchName" class="form-label">Cari Nama:</label>
                            <input type="text" class="form-control" id="searchName" placeholder="Taip nama pemilik (carian automatik)...">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="searchAddress" class="form-label">Cari Alamat:</label>
                            <input type="text" class="form-control" id="searchAddress" placeholder="Masukkan alamat, jalan, taman...">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="searchLocation" class="form-label">Cari Lokasi:</label>
                            <input type="text" class="form-control" id="searchLocation" placeholder="Poskod, bandar, negeri...">
                        </div>
                    </div>
                    
                    <!-- Filter Row -->
                    <div class="row g-3 mt-2">
                        <div class="col-lg-3 col-md-6">
                            <label for="filterStatus" class="form-label">Status Data:</label>
                            <select class="form-select" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="BELUM BUAT">Belum Buat</option>
                                <option value="SUDAH BUAT">Sudah Buat</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="filterUpdate" class="form-label">Status Kemaskini:</label>
                            <select class="form-select" id="filterUpdate">
                                <option value="">Semua</option>
                                <option value="YES">Sudah Kemaskini</option>
                                <option value="NO">Belum Kemaskini</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="filterState" class="form-label">Negeri:</label>
                            <select class="form-select" id="filterState">
                                <option value="">Semua Negeri</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Johor">Johor</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-primary" id="applyFilters">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                                    <i class="fas fa-times me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Filter Buttons -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="quick-filters">
                                <span class="me-2 text-muted">Carian Pantas:</span>
                                <button type="button" class="btn btn-sm btn-outline-warning me-2 quick-filter-btn" data-filter="status" data-value="BELUM BUAT">
                                    <i class="fas fa-clock me-1"></i>Belum Buat
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success me-2 quick-filter-btn" data-filter="status" data-value="SUDAH BUAT">
                                    <i class="fas fa-check-circle me-1"></i>Sudah Buat
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info me-2 quick-filter-btn" data-filter="update" data-value="NO">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Belum Kemaskini
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary quick-filter-btn" data-filter="location" data-value="Selangor">
                                    <i class="fas fa-map-marker-alt me-1"></i>Selangor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="resultsInfo" class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Menunjukkan <span id="visibleCount"><?php echo count($customer_data); ?></span> 
                        daripada <span id="totalCount"><?php echo count($customer_data); ?></span> rekod
                        <span id="activeFiltersIndicator" class="badge bg-primary ms-2" style="display: none;">
                            <i class="fas fa-filter me-1"></i>Penapisan Aktif
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="entriesPerPage" class="form-label me-2 mb-0">Papar:</label>
                        <select class="form-select form-select-sm" id="entriesPerPage" style="width: auto;">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="ms-2 text-muted">rekod</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Results Message (Initially Hidden) -->
        <div id="noResultsMessage" class="alert alert-info text-center" style="display: none;">
            <i class="fas fa-search fa-2x mb-3 text-muted"></i>
            <h6>Tiada Hasil Carian</h6>
            <p class="mb-2">Tiada data yang sepadan dengan kriteria carian anda.</p>
            <small class="text-muted">
                Sila cuba:
                <ul class="list-unstyled mt-2">
                    <li>• Gunakan kata kunci yang lebih pendek</li>
                    <li>• Periksa ejaan kata kunci</li>
                    <li>• Gunakan carian yang lebih umum</li>
                </ul>
            </small>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="clearAllFilters()">
                <i class="fas fa-times me-1"></i>Reset Semua Carian
            </button>
        </div>

        <!-- Legacy Status Filter (keeping for compatibility) -->
        <div class="row mb-3" style="display: none;">
            <div class="col-md-4">
                <select class="form-select" id="customerStatusFilter">
                    <option value="">Semua Status</option>
                    <option value="BELUM BUAT">Belum Buat</option>
                    <option value="SUDAH BUAT">Sudah Buat</option>
                    <option value="BAYAR">Telah Bayar</option>
                </select>
            </div>
        </div>

        <!-- Customer Data Table -->
        <div class="table-responsive customer-table">
            <?php if (!empty($customer_data)): ?>
                <!-- Bulk Actions Bar -->
                <div class="bulk-actions-bar mb-3" id="bulkActionsBar" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <div>
                            <span class="fw-bold text-primary">
                                <i class="fas fa-check-square me-2"></i>
                                <span id="selectedCount">0</span> rekod dipilih
                            </span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success me-2" id="bulkPrintBtn">
                                <i class="fas fa-print me-2"></i>Cetak PDF Terpilih
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clearSelectionBtn">
                                <i class="fas fa-times me-2"></i>Kosongkan Pilihan
                            </button>
                        </div>
                    </div>
                </div>

                <table class="table table-hover" id="customerDataTable">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox" title="Pilih semua">
                                    <label class="form-check-label" for="selectAllCheckbox">
                                        <i class="fas fa-check-square"></i>
                                    </label>
                                </div>
                            </th>
                            <th>BIL</th>
                            <th>NAMA PEMILIK</th>
                            <th>ALAMAT</th>
                            <th>STATUS</th>
                            <th>STATUS KEMASKINI</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="customerDataTableBody">
                        <?php 
                        $bil = 1;
                        foreach($customer_data as $row): 
                        ?>
                            <tr class="customer-data-row">
                                <td>
                                    <?php if (!empty($row['iddata'])): ?>
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox" type="checkbox" 
                                                   value="<?php echo htmlspecialchars($row['iddata']); ?>" 
                                                   id="checkbox_<?php echo $row['iddata']; ?>"
                                                   title="Pilih untuk cetak PDF">
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $bil; ?></td>
                                <td>
                                    <div class="customer-name">
                                        <strong><?php echo htmlspecialchars($row['NAMAPEMILIK'] ?? 'N/A'); ?></strong>
                                        <?php if (!empty($row['NAMAPEMILIK_BODY'])): ?>
                                            <small class="text-muted d-block"><?php echo htmlspecialchars($row['NAMAPEMILIK_BODY']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-address">
                                        <?php echo htmlspecialchars($row['ALAMAT1'] ?? 'N/A'); ?>
                                        <?php if (!empty($row['ALAMAT2'])): ?>
                                            <small class="text-muted d-block"><?php echo htmlspecialchars($row['ALAMAT2']); ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($row['ALAMAT3'])): ?>
                                            <small class="text-muted d-block"><?php echo htmlspecialchars($row['ALAMAT3']); ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($row['POSKOD']) || !empty($row['NEGERI'])): ?>
                                            <small class="text-muted d-block">
                                                <?php echo htmlspecialchars($row['POSKOD'] ?? ''); ?> 
                                                <?php echo htmlspecialchars($row['NEGERI'] ?? ''); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $status = $row['STATUS_DATA'] ?? 'N/A';
                                    $statusClass = 'secondary';
                                    $statusIcon = 'question';
                                    
                                    switch($status) {
                                        case 'SUDAH BUAT':
                                            $statusClass = 'success';
                                            $statusIcon = 'check-circle';
                                            break;
                                        case 'BELUM BUAT':
                                            $statusClass = 'warning';
                                            $statusIcon = 'clock';
                                            break;
                                        case 'BAYAR':
                                            $statusClass = 'primary';
                                            $statusIcon = 'credit-card';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                        <i class="fas fa-<?php echo $statusIcon; ?> me-1"></i><?php echo $status; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusKemaskini = $row['STATUS_UPDATE'] ?? 'NO';
                                    $kemaskiniClass = ($statusKemaskini == 'YES') ? 'success' : 'secondary';
                                    $kemaskiniIcon = ($statusKemaskini == 'YES') ? 'check' : 'times';
                                    $kemaskiniText = ($statusKemaskini == 'YES') ? 'SUDAH KEMASKINI' : 'BELUM KEMASKINI';
                                    ?>
                                    <span class="badge bg-<?php echo $kemaskiniClass; ?>">
                                        <i class="fas fa-<?php echo $kemaskiniIcon; ?> me-1"></i><?php echo $kemaskiniText; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm customer-view-btn" 
                                                data-details='<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>' 
                                                title="Lihat butiran lengkap">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </button>
                                        <?php if (!empty($row['iddata'])): ?>
                                            <a href="?page=kemaskini_data.php&id=<?php echo urlencode($row['iddata']); ?>" 
                                               class="btn btn-outline-warning btn-sm" 
                                               title="Edit maklumat alamat dan amaun">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <?php $printUrl = $base_url."cetaknotisbm.php?data=".$row['iddata']; ?>
                                            <a href="<?php echo $printUrl; ?>" 
                                               class="btn btn-outline-success btn-sm" 
                                               target="_blank" 
                                               title="Cetak notis">
                                                <i class="fas fa-print me-1"></i>Cetak
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                        $bil++;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
                
                <!-- Pagination Info for Customers -->
                <div class="customer-pagination-info mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Menunjukkan <span id="customerShowingCount"><?php echo count($customer_data); ?></span> daripada <?php echo count($customer_data); ?> rekod anda
                    </small>
                </div>
                
            <?php else: ?>
                <div class="text-center py-5 customer-empty-state">
                    <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                    <h5>Tiada Data Dijumpai</h5>
                    <p class="text-muted">Anda belum mempunyai sebarang data dalam sistem, atau data sedang diproses.</p>
                    <div class="mt-4">
                        <button class="btn btn-primary" onclick="showContactSupport()">
                            <i class="fas fa-phone me-2"></i>Hubungi Sokongan
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal for Customer Data Details -->
<div class="modal fade" id="customerDataModal" tabindex="-1" aria-labelledby="customerDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header customer-modal-header">
                <h5 class="modal-title" id="customerDataModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Butiran Data Saya
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="customerModalDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Customer Data Page Styles */
.customer-data-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.customer-badge .badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

.customer-search .form-control {
    border-radius: 10px 0 0 10px;
}

.customer-search .input-group-text {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.customer-table {
    max-height: 600px;
    overflow-y: auto;
}

.customer-data-row:hover {
    background-color: rgba(102, 126, 234, 0.1) !important;
    transform: scale(1.01);
    transition: all 0.2s ease;
    cursor: pointer;
}

.customer-name strong {
    color: var(--primary-color);
}

.customer-address {
    max-width: 200px;
}

.customer-modal-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.customer-empty-state {
    padding: 3rem 1rem;
}

.customer-pagination-info {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
}

/* Enhanced button styles for customers */
.customer-view-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

/* Bulk Actions Styling */
.bulk-actions-bar {
    animation: slideDown 0.3s ease-out;
    border: 2px solid #007bff;
    background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bulk-actions-bar .btn {
    font-weight: 600;
    padding: 0.5rem 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.bulk-actions-bar .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

#bulkPrintBtn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

#bulkPrintBtn:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
}

/* Checkbox Styling */
.form-check-input {
    width: 1.2rem;
    height: 1.2rem;
    border-radius: 4px;
    border: 2px solid #6c757d;
    transition: all 0.2s ease;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
    transform: scale(1.1);
}

.form-check-input:hover {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

/* Row selection highlighting */
.customer-data-row.selected {
    background-color: rgba(0, 123, 255, 0.1) !important;
    border-left: 4px solid #007bff;
}

.customer-data-row.selected:hover {
    background-color: rgba(0, 123, 255, 0.15) !important;
}

/* Select all checkbox styling */
#selectAllCheckbox {
    transform: scale(1.1);
}

#selectAllCheckbox:checked {
    background-color: #ffc107;
    border-color: #ffc107;
}

/* Modal details formatting */
#customerModalDetails {
    max-height: 400px;
    overflow-y: auto;
}

#customerModalDetails .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--primary-color);
}

#customerModalDetails .detail-label {
    font-weight: 600;
    color: #6c757d;
    min-width: 150px;
}

#customerModalDetails .detail-value {
    flex: 1;
    text-align: right;
    font-weight: 500;
}

/* Enhanced Search Filters Styling */
.search-filters-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-radius: 15px;
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

.search-filters-container h6 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
}

.search-filters-container .form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.3rem;
}

.search-filters-container .form-control,
.search-filters-container .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
}

.search-filters-container .form-control:focus,
.search-filters-container .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.search-filters-container .form-control.has-value {
    border-color: #28a745;
    background-color: #f8fff9;
}

.search-filters-container .form-select.has-value {
    border-color: #28a745;
    background-color: #f8fff9;
}

#noResultsMessage ul {
    display: inline-block;
    text-align: left;
}

.search-active-indicator {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #28a745;
    font-size: 0.8rem;
}

.quick-filters {
    padding: 1rem;
    background-color: white;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.quick-filter-btn {
    border-radius: 20px;
    transition: all 0.3s ease;
}

.quick-filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.quick-filter-btn.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

#resultsInfo {
    font-size: 0.9rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .customer-data-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    /* Mobile optimizations for new sections */
    .info-notice-top {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .update-header {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .update-body {
        padding: 1rem;
    }
    
    .update-body .row {
        gap: 1rem;
    }
    
    .btn-update-all {
        margin-top: 1rem;
    }
    
    .btn-group-sm .btn {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
    }
    
    .customer-address {
        max-width: 150px;
    }
    
    .quick-filters {
        text-align: center;
    }
    
    .quick-filter-btn {
        margin-bottom: 0.5rem;
    }
    
    /* Bulk actions responsive */
    .bulk-actions-bar .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .bulk-actions-bar .btn {
        width: 100%;
        margin: 0 !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Table responsive adjustments */
    #customerDataTable th:first-child,
    #customerDataTable td:first-child {
        width: 40px;
        padding: 0.5rem 0.25rem;
    }
    
    .form-check-input {
        width: 1rem;
        height: 1rem;
    }
}

/* New Sections Styling */
.info-notice-top {
    background: linear-gradient(135deg, #E8F4FD 0%, #F0F8FF 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-notice-top .notice-icon {
    width: 40px;
    height: 40px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.info-notice-top .notice-content h5 {
    color: #2C3E50;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.3rem 0;
}

.info-notice-top .notice-content p {
    color: #5A6C7D;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Bulk Date Update Section */
.bulk-date-update-section {
    background: var(--warm-white);
    border: 2px solid var(--hazel-light);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.update-card {
    width: 100%;
}

.update-header {
    background: linear-gradient(135deg, var(--hazel) 0%, var(--hazel-light) 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.update-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.update-title h5 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 0.3rem 0;
}

.update-title p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
}

.update-body {
    padding: 2rem;
    background: var(--warm-white);
}

.modern-date-input {
    border: 2px solid #E5E7EB;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.modern-date-input:focus {
    border-color: var(--hazel);
    box-shadow: 0 0 0 3px rgba(166, 124, 82, 0.1);
    outline: none;
}

.impact-info {
    background: #F8F9FA;
    border: 1px solid #E9ECEF;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    text-align: center;
}

.impact-count {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--hazel);
    line-height: 1;
}

.impact-text {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.btn-update-all {
    background: linear-gradient(135deg, var(--hazel) 0%, var(--hazel-light) 100%);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    box-shadow: 0 3px 10px rgba(166, 124, 82, 0.3);
}

.btn-update-all:hover {
    background: linear-gradient(135deg, var(--hazel-dark) 0%, var(--hazel) 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(166, 124, 82, 0.4);
}

.btn-update-all:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(166, 124, 82, 0.3);
}

/* Print styles (hide checkboxes when printing) */
@media print {
    .form-check,
    .bulk-actions-bar,
    .btn-group,
    .bulk-date-update-section,
    .info-notice-top {
        display: none !important;
    }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Customer data page initializing with jQuery...');
    
    // Initialize variables
    let allRows = $('#customerDataTableBody tr');
    let currentFilters = {
        name: '',
        address: '',
        location: '',
        status: '',
        update: '',
        state: ''
    };
    
    console.log('Total rows found:', allRows.length);
    
    // Initialize the page
    updateResultsCount();
    initializeEventHandlers();
    initializeBulkSelection();
    
    // Initialize all event handlers
    function initializeEventHandlers() {
        console.log('Setting up event handlers...');
        
        // Search inputs with real-time filtering and visual feedback
        $('#searchName').on('input', debounce(function() {
            const value = $(this).val().trim();
            currentFilters.name = value.toLowerCase();
            console.log('Name search activated:', currentFilters.name);
            
            // Add visual feedback
            if (value.length > 0) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
            
            applyFilters();
        }, 300));
        
        $('#searchAddress').on('input', debounce(function() {
            const value = $(this).val().trim();
            currentFilters.address = value.toLowerCase();
            console.log('Address search:', currentFilters.address);
            
            // Add visual feedback
            if (value.length > 0) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
            
            applyFilters();
        }, 300));
        
        $('#searchLocation').on('input', debounce(function() {
            const value = $(this).val().trim();
            currentFilters.location = value.toLowerCase();
            console.log('Location search:', currentFilters.location);
            
            // Add visual feedback
            if (value.length > 0) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
            
            applyFilters();
        }, 300));
        
        // Dropdown filters with visual feedback
        $('#filterStatus').on('change', function() {
            currentFilters.status = $(this).val();
            console.log('Status filter:', currentFilters.status);
            
            // Add visual feedback
            if ($(this).val().length > 0) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
            
            applyFilters();
        });
        
        $('#filterUpdate').on('change', function() {
            currentFilters.update = $(this).val();
            console.log('Update filter:', currentFilters.update);
            
            // Add visual feedback
            if ($(this).val().length > 0) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
            
            applyFilters();
        });
        
        $('#filterState').on('change', function() {
            currentFilters.state = $(this).val();
            console.log('State filter:', currentFilters.state);
            
            // Add visual feedback
            if ($(this).val().length > 0) {
                $(this).addClass('has-value');
            } else {
                $(this).removeClass('has-value');
            }
            
            applyFilters();
        });
        
        // Apply and Clear buttons
        $('#applyFilters').on('click', function() {
            console.log('Apply filters button clicked');
            // Manually get all current filter values from the inputs
            currentFilters.name = $('#searchName').val().trim().toLowerCase();
            currentFilters.address = $('#searchAddress').val().trim().toLowerCase();
            currentFilters.location = $('#searchLocation').val().trim().toLowerCase();
            currentFilters.status = $('#filterStatus').val();
            currentFilters.update = $('#filterUpdate').val();
            currentFilters.state = $('#filterState').val();
            
            console.log('Apply filters clicked - Updated filters:', currentFilters);
            applyFilters();
        });
        
        $('#clearFilters').on('click', function() {
            console.log('Clear filters clicked');
            clearAllFilters();
        });
        
        // Quick filter buttons
        $('.quick-filter-btn').on('click', function() {
            const filterType = $(this).data('filter');
            const filterValue = $(this).data('value');
            console.log('Quick filter clicked:', filterType, '=', filterValue);
            handleQuickFilter($(this));
        });
        
        // Entries per page
        $('#entriesPerPage').on('change', function() {
            updateResultsCount();
            applyPagination();
        });
        
        // View detail buttons
        $('.customer-view-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('View button clicked');
            
            try {
                const dataString = $(this).attr('data-details');
                if (dataString) {
                    const data = JSON.parse(dataString);
                    showCustomerDataDetails(data);
                } else {
                    console.error('No data-details attribute found');
                }
            } catch (error) {
                console.error('Error parsing data details:', error);
                alert('Error loading data details');
            }
        });
        
        console.log('Event handlers set up successfully');
    }
    
    // Bulk selection functionality
    function initializeBulkSelection() {
        console.log('Initializing bulk selection functionality...');
        
        // Select All checkbox handler
        $('#selectAllCheckbox').on('change', function() {
            const isChecked = $(this).is(':checked');
            const visibleCheckboxes = $('.row-checkbox').filter(':visible');
            
            visibleCheckboxes.prop('checked', isChecked);
            visibleCheckboxes.each(function() {
                const row = $(this).closest('tr');
                if (isChecked) {
                    row.addClass('selected');
                } else {
                    row.removeClass('selected');
                }
            });
            
            updateBulkActionsBar();
            updateSelectedCount();
        });
        
        // Individual row checkbox handlers
        $(document).on('change', '.row-checkbox', function() {
            const row = $(this).closest('tr');
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                row.addClass('selected');
            } else {
                row.removeClass('selected');
            }
            
            // Update select all checkbox state
            const totalVisible = $('.row-checkbox:visible').length;
            const totalChecked = $('.row-checkbox:visible:checked').length;
            
            $('#selectAllCheckbox').prop('indeterminate', totalChecked > 0 && totalChecked < totalVisible);
            $('#selectAllCheckbox').prop('checked', totalChecked === totalVisible && totalVisible > 0);
            
            updateBulkActionsBar();
            updateSelectedCount();
        });
        
        // Bulk print button handler
        $('#bulkPrintBtn').on('click', function() {
            const selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Tiada Pilihan',
                        text: 'Sila pilih sekurang-kurangnya satu rekod untuk dicetak.',
                        icon: 'warning',
                        confirmButtonColor: '#007bff'
                    });
                } else {
                    alert('Sila pilih sekurang-kurangnya satu rekod untuk dicetak.');
                }
                return;
            }
            
            // Confirm bulk print
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Cetak PDF Terpilih?',
                    text: `Anda akan mencetak ${selectedIds.length} PDF. Ini akan membuka beberapa tab baru.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-print me-2"></i>Ya, Cetak Semua',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkPrint(selectedIds);
                    }
                });
            } else {
                if (confirm(`Anda akan mencetak ${selectedIds.length} PDF. Ini akan membuka beberapa tab baru. Teruskan?`)) {
                    performBulkPrint(selectedIds);
                }
            }
        });
        
        // Clear selection button handler
        $('#clearSelectionBtn').on('click', function() {
            $('.row-checkbox').prop('checked', false);
            $('#selectAllCheckbox').prop('checked', false).prop('indeterminate', false);
            $('.customer-data-row').removeClass('selected');
            updateBulkActionsBar();
            updateSelectedCount();
        });
    }
    
    // Perform bulk PDF printing
    function performBulkPrint(selectedIds) {
        console.log('Starting bulk print for IDs:', selectedIds);
        
        if (selectedIds.length === 0) {
            return;
        }
        
        // Show progress indicator
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Menjana PDF...',
                html: `Mencipta PDF tunggal dengan ${selectedIds.length} rekod.<br><small>Sila tunggu sebentar...</small>`,
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        // Create bulk PDF URL with all IDs
        const baseUrl = '<?php echo $base_url; ?>bulk_cetaknotisbm.php?ids=';
        const idsString = selectedIds.join(',');
        const bulkPdfUrl = baseUrl + encodeURIComponent(idsString);
        
        console.log('Opening bulk PDF:', bulkPdfUrl);
        
        // Small delay to show the loading message
        setTimeout(() => {
            // Open the bulk PDF in a new tab
            const newWindow = window.open(bulkPdfUrl, '_blank');
            
            if (!newWindow) {
                // Popup was blocked
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Popup Disekat!',
                        html: `
                            <p>Browser anda menyekat popup. Sila:</p>
                            <ol class="text-start">
                                <li>Klik pada ikon popup di bar alamat</li>
                                <li>Pilih "Always allow popups from this site"</li>
                                <li>Cuba sekali lagi</li>
                            </ol>
                            <hr>
                            <p><small>Atau klik butang di bawah untuk buka manual:</small></p>
                            <a href="${bulkPdfUrl}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>Buka PDF Manual
                            </a>
                        `,
                        icon: 'warning',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Popup disekat! Sila benarkan popup untuk laman ini dan cuba lagi.');
                    // Fallback: redirect to PDF
                    window.location.href = bulkPdfUrl;
                }
            } else {
                // PDF opened successfully
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'PDF Berjaya Dijana!',
                        html: `
                            <div class="text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <p>PDF dengan <strong>${selectedIds.length} rekod</strong> telah dibuka di tab baru.</p>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Semua rekod digabungkan dalam satu dokumen PDF yang berterusan.
                                </small>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 4000,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup'
                    });
                }
            }
        }, 800);
    }
    
    // Update bulk actions bar visibility
    function updateBulkActionsBar() {
        const checkedCount = $('.row-checkbox:checked').length;
        const bulkActionsBar = $('#bulkActionsBar');
        
        if (checkedCount > 0) {
            bulkActionsBar.show();
        } else {
            bulkActionsBar.hide();
        }
    }
    
    // Update selected count display
    function updateSelectedCount() {
        const checkedCount = $('.row-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
    }
    
    // Enhanced apply filters to handle checkboxes
    const originalApplyFilters = applyFilters;
    applyFilters = function() {
        originalApplyFilters();
        
        // Reset bulk selections when filters change
        $('.row-checkbox').prop('checked', false);
        $('#selectAllCheckbox').prop('checked', false).prop('indeterminate', false);
        $('.customer-data-row').removeClass('selected');
        updateBulkActionsBar();
        updateSelectedCount();
    };
    
    // Improved apply filters function
    function applyFilters() {
        console.log('=== APPLYING FILTERS ===');
        console.log('Current filters:', currentFilters);
        
        let visibleCount = 0;
        let totalProcessed = 0;
        
        allRows.each(function(index) {
            totalProcessed++;
            const row = $(this);
            const cells = row.find('td');
            
            if (cells.length < 6) {
                console.log('Row', index, 'has insufficient cells:', cells.length);
                row.hide();
                return;
            }
            
            // Get cell content with improved text extraction - handle nested HTML structure
            const nameCell = cells.eq(2);
            const nameText = nameCell.find('strong').length > 0 ? 
                nameCell.find('strong').text().toLowerCase().trim() : 
                nameCell.text().toLowerCase().trim(); // Fixed: NAMA PEMILIK is in column 2, not 1
            const addressContent = cells.eq(3); // Get the whole address cell (moved from 2 to 3)
            const addressText = addressContent.text().toLowerCase().trim();
            
            // Extract status badge text more precisely
            const statusBadge = cells.eq(4).find('.badge'); // Fixed: STATUS is in column 4, not 3
            const statusText = statusBadge.length > 0 ? statusBadge.text().trim() : cells.eq(4).text().trim();
            
            const updateBadge = cells.eq(5).find('.badge'); // Fixed: STATUS KEMASKINI is in column 5, not 4
            const updateText = updateBadge.length > 0 ? updateBadge.text().trim() : cells.eq(5).text().trim();
            
            // Debug info for first few rows
            if (index < 3) {
                console.log(`Row ${index} data:`, {
                    name: nameText,
                    address: addressText.substring(0, 50) + '...',
                    status: statusText,
                    update: updateText
                });
            }
            
            let showRow = true;
            let failedFilters = [];
            
            // Apply name filter
            if (currentFilters.name && currentFilters.name.length > 0) {
                if (!nameText.includes(currentFilters.name)) {
                    showRow = false;
                    failedFilters.push('name');
                }
                // Debug logging for name search
                if (index < 5) {
                    console.log(`Row ${index} name check: "${nameText}" includes "${currentFilters.name}" = ${nameText.includes(currentFilters.name)}`);
                }
            }
            
            // Apply address filter (search in full address text)
            if (currentFilters.address && currentFilters.address.length > 0) {
                if (!addressText.includes(currentFilters.address)) {
                    showRow = false;
                    failedFilters.push('address');
                }
            }
            
            // Apply location filter (also search in address text)
            if (currentFilters.location && currentFilters.location.length > 0) {
                if (!addressText.includes(currentFilters.location)) {
                    showRow = false;
                    failedFilters.push('location');
                }
            }
            
            // Apply status filter (exact match on status badge text)
            if (currentFilters.status && currentFilters.status.length > 0) {
                if (!statusText.includes(currentFilters.status)) {
                    showRow = false;
                    failedFilters.push('status');
                }
            }
            
            // Apply update filter (check if it contains the expected text)
            if (currentFilters.update && currentFilters.update.length > 0) {
                const hasKemaskini = updateText.includes('SUDAH KEMASKINI') || updateText.includes('SUDAH');
                if (currentFilters.update === 'YES' && !hasKemaskini) {
                    showRow = false;
                    failedFilters.push('update-yes');
                }
                if (currentFilters.update === 'NO' && hasKemaskini) {
                    showRow = false;
                    failedFilters.push('update-no');
                }
            }
            
            // Apply state filter (search in address for state name)
            if (currentFilters.state && currentFilters.state.length > 0) {
                if (!addressText.includes(currentFilters.state.toLowerCase())) {
                    showRow = false;
                    failedFilters.push('state');
                }
            }
            
            // Show or hide row
            if (showRow) {
                row.show();
                visibleCount++;
                if (index < 3) {
                    console.log(`Row ${index}: SHOWING`);
                }
            } else {
                row.hide();
                if (index < 3) {
                    console.log(`Row ${index}: HIDING - Failed filters:`, failedFilters);
                }
            }
        });
        
        // Update results count
        $('#visibleCount').text(visibleCount);
        $('#recordCount').text(visibleCount); // Update the bulk update record count
        console.log(`Filter results: ${visibleCount} visible out of ${totalProcessed} total rows`);
        
        // Show/hide table and no results message
        const customerTable = $('.customer-table');
        const noResultsMessage = $('#noResultsMessage');
        const activeFiltersIndicator = $('#activeFiltersIndicator');
        
        if (visibleCount === 0 && totalProcessed > 0) {
            console.log('No rows match current filters');
            customerTable.hide();
            noResultsMessage.show();
        } else {
            customerTable.show();
            noResultsMessage.hide();
        }
        
        // Show active filters indicator
        const hasActiveFilters = Object.values(currentFilters).some(filter => filter && filter.length > 0);
        if (hasActiveFilters) {
            activeFiltersIndicator.show();
        } else {
            activeFiltersIndicator.hide();
        }
        
        console.log('=== FILTERS APPLIED ===');
    }
    
    // Clear all filters
    function clearAllFilters() {
        console.log('=== CLEARING ALL FILTERS ===');
        
        // Reset filter object
        currentFilters = {
            name: '',
            address: '',
            location: '',
            status: '',
            update: '',
            state: ''
        };
        
        // Clear form inputs and remove visual styling
        $('#searchName, #searchAddress, #searchLocation').val('').removeClass('has-value');
        $('#filterStatus, #filterUpdate, #filterState').val('').removeClass('has-value');
        
        // Remove active class from quick filter buttons
        $('.quick-filter-btn').removeClass('active');
        
        // Show all rows
        allRows.show();
        
        // Hide no results message and show table
        $('#noResultsMessage').hide();
        $('.customer-table').show();
        $('#activeFiltersIndicator').hide();
        
        // Update results count
        updateResultsCount();
        
        // Focus on first input
        $('#searchName').focus();
        
        console.log('All filters cleared - showing all', allRows.length, 'rows');
    }
    
    // Handle quick filter buttons
    function handleQuickFilter(button) {
        const filterType = button.data('filter');
        const filterValue = button.data('value');
        
        console.log('=== QUICK FILTER ===');
        console.log('Type:', filterType, 'Value:', filterValue);
        
        // Remove active class from all quick filter buttons of the same type
        $('.quick-filter-btn').removeClass('active');
        
        // Add active class to clicked button
        button.addClass('active');
        
        // Clear other filters but keep current type
        $('#searchName, #searchAddress, #searchLocation').val('');
        
        // Apply the specific quick filter
        switch(filterType) {
            case 'status':
                currentFilters.status = filterValue;
                $('#filterStatus').val(filterValue);
                // Clear other filters
                currentFilters.name = currentFilters.address = currentFilters.location = '';
                currentFilters.update = currentFilters.state = '';
                $('#filterUpdate, #filterState').val('');
                break;
            case 'update':
                currentFilters.update = filterValue;
                $('#filterUpdate').val(filterValue);
                // Clear other filters
                currentFilters.name = currentFilters.address = currentFilters.location = '';
                currentFilters.status = currentFilters.state = '';
                $('#filterStatus, #filterState').val('');
                break;
            case 'location':
                currentFilters.state = filterValue;
                $('#filterState').val(filterValue);
                // Clear other filters
                currentFilters.name = currentFilters.address = currentFilters.location = '';
                currentFilters.status = currentFilters.update = '';
                $('#filterStatus, #filterUpdate').val('');
                break;
        }
        
        console.log('Quick filter applied:', currentFilters);
        applyFilters();
    }
    
    // Update results count
    function updateResultsCount() {
        const totalCount = allRows.length;
        const visibleCount = allRows.filter(':visible').length;
        
        $('#visibleCount').text(visibleCount);
        $('#totalCount').text(totalCount);
        $('#recordCount').text(visibleCount); // Update bulk update count
        
        console.log('Results count updated:', visibleCount, '/', totalCount);
    }
    
    // Simple pagination (for future enhancement)
    function applyPagination() {
        // For now, just update the count
        updateResultsCount();
    }
    
    // Show customer data details in modal
    function showCustomerDataDetails(data) {
        console.log('Showing customer data details:', data);
        
        const modalDetails = $('#customerModalDetails');
        if (modalDetails.length === 0) {
            console.error('Modal details container not found');
            return;
        }
        
        modalDetails.empty();

        // Define field labels in Malay
        const fieldLabels = {
            'iddata': 'ID Data',
            'BIL': 'Bil',
            'NOFAILTF': 'No Fail TF',
            'NOAKAUNDREAMS': 'No Akaun Dreams',
            'NAMAPEMILIK': 'Nama Pemilik',
            'NORUMAH': 'No Rumah',
            'ALAMAT1': 'Alamat 1',
            'ALAMAT2': 'Alamat 2',
            'ALAMAT3': 'Alamat 3',
            'ALAMAT4': 'Alamat 4',
            'POSKOD': 'Poskod',
            'NEGERI': 'Negeri',
            'NAMAPEMILIK_BODY': 'Nama Pemilik (Body)',
            'NORUMAH_BODY': 'No Rumah (Body)',
            'ADDR1_BODY': 'Alamat 1 (Body)',
            'ADDR2_BODY': 'Alamat 2 (Body)',
            'POSTCODE_BODY': 'Poskod (Body)',
            'STATE_BODY': 'Negeri (Body)',
            'BAKITUNGGAKAN': 'Baki Tunggakan',
            'TARIKH_BUAT': 'Tarikh Dibuat',
            'STATUS_DATA': 'Status Data',
            'STATUS_UPDATE': 'Status Kemaskini',
            'INSBY': 'Dimasukkan Oleh'
        };

        // Create formatted details
        $.each(data, function(key, value) {
            if (value && value !== 'N/A' && value !== null && value !== '') {
                const label = fieldLabels[key] || key.replace(/_/g, ' ');
                
                // Format specific fields
                let displayValue = value;
                if (key === 'BAKITUNGGAKAN' && !isNaN(value)) {
                    displayValue = 'RM ' + parseFloat(value).toFixed(2);
                } else if (key === 'TARIKH_BUAT') {
                    displayValue = new Date(value).toLocaleString('ms-MY');
                }
                
                const detailRow = $(`
                    <div class="detail-row">
                        <span class="detail-label">${label}:</span>
                        <span class="detail-value">${displayValue}</span>
                    </div>
                `);
                
                modalDetails.append(detailRow);
            }
        });

        // Show modal using Bootstrap
        $('#customerDataModal').modal('show');
        console.log('Modal displayed');
    }
    
    // Test search functionality
    function testSearchFunctionality() {
        console.log('=== TESTING SEARCH FUNCTIONALITY ===');
        
        // Test name search
        console.log('Testing name search...');
        currentFilters.name = 'test';
        applyFilters();
        
        // Reset
        setTimeout(() => {
            clearAllFilters();
            
            // Test address search
            console.log('Testing address search...');
            currentFilters.address = 'jalan';
            applyFilters();
            
            // Reset
            setTimeout(() => {
                clearAllFilters();
                console.log('Search functionality test completed');
            }, 1000);
        }, 1000);
    }
    
    // Make functions globally available
    window.showCustomerDataDetails = showCustomerDataDetails;
    window.testSearchFunctionality = testSearchFunctionality;
    window.clearAllFilters = clearAllFilters;
    
    // Debounce function to limit rapid firing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Add a test button for debugging (temporary)
    if (window.location.search.includes('debug=1')) {
        $('body').append(`
            <div style="position: fixed; top: 10px; right: 10px; z-index: 9999;">
                <button onclick="testSearchFunctionality()" class="btn btn-warning btn-sm">Test Search</button>
                <button onclick="console.log('Current filters:', window.currentFilters || 'undefined')" class="btn btn-info btn-sm">Log Filters</button>
            </div>
        `);
        window.currentFilters = currentFilters;
    }
    
    console.log('Customer data page initialization complete with jQuery');
    console.log('Available for testing: Add ?debug=1 to URL to show test buttons');
    
    // Test the search functionality immediately after page load
    setTimeout(function() {
        console.log('=== TESTING NAMA PEMILIK SEARCH FUNCTIONALITY ===');
        
        // Test first 5 rows
        const testRows = $('#customerDataTableBody tr').slice(0, 5);
        testRows.each(function(index) {
            const cells = $(this).find('td');
            if (cells.length >= 6) {
                const nameCell = cells.eq(2);
                const nameText = nameCell.find('strong').length > 0 ? 
                    nameCell.find('strong').text().toLowerCase().trim() : 
                    nameCell.text().toLowerCase().trim();
                console.log(`Row ${index + 1} - Name text extracted: "${nameText}"`);
            }
        });
        
        // Look for MICHELE LIM YEE YEAN specifically
        console.log('=== SEARCHING FOR MICHELE LIM YEE YEAN ===');
        let foundMichele = false;
        $('#customerDataTableBody tr').each(function(index) {
            const cells = $(this).find('td');
            if (cells.length >= 6) {
                const nameCell = cells.eq(2);
                const nameText = nameCell.find('strong').length > 0 ? 
                    nameCell.find('strong').text().toLowerCase().trim() : 
                    nameCell.text().toLowerCase().trim();
                
                if (nameText.includes('michele') || nameText.includes('lim yee yean')) {
                    console.log(`FOUND MICHELE at Row ${index + 1}: "${nameText}"`);
                    foundMichele = true;
                }
            }
        });
        
        if (!foundMichele) {
            console.log('MICHELE LIM YEE YEAN not found in the current data');
        }
        
        console.log('=== SEARCH TEST COMPLETE - You can now search by name! ===');
        console.log('💡 TIP: Search works as you type in the "Cari Nama" field, or click "Cari" button');
    }, 1000);
});

// Bulk Date Update Function
function updateAllDates() {
    const selectedDate = $('#bulkDateUpdate').val();
    const recordCount = $('.customer-data-row:visible').length;
    
    if (!selectedDate) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Tarikh Diperlukan',
                text: 'Sila pilih tarikh terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#A67C52'
            });
        } else {
            alert('Sila pilih tarikh terlebih dahulu.');
        }
        return;
    }
    
    // Confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Kemaskini Tarikh Buat?',
            html: `
                <div class="text-center">
                    <p>Anda akan mengemas kini <strong>${recordCount} rekod</strong> dengan tarikh:</p>
                    <p class="fs-5 fw-bold text-primary">${formatDate(selectedDate)}</p>
                    <small class="text-muted">Tindakan ini tidak boleh dibatalkan.</small>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#A67C52',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sync-alt me-2"></i>Ya, Kemaskini',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performBulkDateUpdate(selectedDate, recordCount);
            }
        });
    } else {
        if (confirm(`Kemaskini ${recordCount} rekod dengan tarikh ${formatDate(selectedDate)}?`)) {
            performBulkDateUpdate(selectedDate, recordCount);
        }
    }
}

function performBulkDateUpdate(selectedDate, recordCount) {
    // Show loading
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Mengemas kini...',
            html: `Sedang mengemas kini ${recordCount} rekod.<br><small>Sila tunggu sebentar...</small>`,
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    // Get all visible record IDs
    const recordIds = [];
    $('.customer-data-row:visible').each(function() {
        const checkbox = $(this).find('.row-checkbox');
        if (checkbox.length > 0) {
            recordIds.push(checkbox.val());
        }
    });
    
    // Perform AJAX request
    $.ajax({
        url: 'update_bulk_date.php', // You'll need to create this file
        method: 'POST',
        data: {
            date: selectedDate,
            record_ids: recordIds
        },
        dataType: 'json'
    }).done(function(response) {
        if (response.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Berjaya!',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p><strong>${response.updated_count} rekod</strong> telah berjaya dikemas kini.</p>
                            <small class="text-muted">Tarikh baru: ${formatDate(selectedDate)}</small>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#27AE60',
                    timer: 3000,
                    showConfirmButton: true,
                    confirmButtonText: 'Tutup'
                }).then(() => {
                    // Reload the page to show updated data
                    window.location.reload();
                });
            } else {
                alert(`Berjaya mengemas kini ${response.updated_count} rekod.`);
                window.location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Ralat!',
                    text: response.message || 'Gagal mengemas kini rekod.',
                    icon: 'error',
                    confirmButtonColor: '#E67E22'
                });
            } else {
                alert('Ralat: ' + (response.message || 'Gagal mengemas kini rekod.'));
            }
        }
    }).fail(function(xhr, status, error) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Ralat Sambungan!',
                text: 'Gagal menyambung ke server. Sila cuba lagi.',
                icon: 'error',
                confirmButtonColor: '#E67E22'
            });
        } else {
            alert('Ralat sambungan: Sila cuba lagi.');
        }
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('ms-MY', options);
}

// Contact support function (outside document ready)
function showContactSupport() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hubungi Sokongan',
            html: `
                <div class="text-start">
                    <p class="mb-3">Jika anda memerlukan bantuan atau mempunyai pertanyaan mengenai data anda:</p>
                    <p><i class="fas fa-phone me-2 text-primary"></i><strong>Telefon:</strong> +603-1234-5678</p>
                    <p><i class="fas fa-envelope me-2 text-primary"></i><strong>Email:</strong> support@taxtrek.com</p>
                    <p><i class="fas fa-clock me-2 text-primary"></i><strong>Waktu Operasi:</strong> 9:00 AM - 6:00 PM</p>
                    <hr>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-2"></i>
                        Sila nyatakan No Gaji anda (<strong><?php echo $_SESSION['user_no_gaji']; ?></strong>) semasa menghubungi sokongan.
                    </p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#007bff'
        });
    } else {
        alert('Hubungi sokongan: support@taxtrek.com atau +603-1234-5678');
    }
}
</script> 