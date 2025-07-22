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
        <!-- Enhanced Search Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-filters-container">
                    <h6 class="mb-3"><i class="fas fa-search me-2"></i>Carian Data</h6>
                    
                    <!-- Search Input Row -->
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="searchName" class="form-label">Cari Nama:</label>
                            <input type="text" class="form-control" id="searchName" placeholder="Masukkan nama pemilik...">
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
                <table class="table table-hover" id="customerDataTable">
                    <thead class="table-dark">
                        <tr>
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
    
    // Initialize the page
    updateResultsCount();
    initializeEventHandlers();
    
    // Initialize all event handlers
    function initializeEventHandlers() {
        console.log('Setting up event handlers...');
        
        // Search inputs with real-time filtering
        $('#searchName').on('input', debounce(function() {
            currentFilters.name = $(this).val().toLowerCase().trim();
            applyFilters();
        }, 300));
        
        $('#searchAddress').on('input', debounce(function() {
            currentFilters.address = $(this).val().toLowerCase().trim();
            applyFilters();
        }, 300));
        
        $('#searchLocation').on('input', debounce(function() {
            currentFilters.location = $(this).val().toLowerCase().trim();
            applyFilters();
        }, 300));
        
        // Dropdown filters
        $('#filterStatus').on('change', function() {
            currentFilters.status = $(this).val();
            applyFilters();
        });
        
        $('#filterUpdate').on('change', function() {
            currentFilters.update = $(this).val();
            applyFilters();
        });
        
        $('#filterState').on('change', function() {
            currentFilters.state = $(this).val();
            applyFilters();
        });
        
        // Apply and Clear buttons
        $('#applyFilters').on('click', function() {
            console.log('Apply filters clicked');
            applyFilters();
        });
        
        $('#clearFilters').on('click', function() {
            console.log('Clear filters clicked');
            clearAllFilters();
        });
        
        // Quick filter buttons
        $('.quick-filter-btn').on('click', function() {
            console.log('Quick filter clicked:', $(this).data('filter'));
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
    
    // Apply all filters
    function applyFilters() {
        console.log('Applying filters:', currentFilters);
        let visibleCount = 0;
        
        allRows.each(function() {
            const row = $(this);
            const cells = row.find('td');
            
            if (cells.length < 5) {
                row.hide();
                return;
            }
            
            // Get cell content
            const nameText = cells.eq(1).text().toLowerCase();
            const addressText = cells.eq(2).text().toLowerCase();
            const statusText = cells.eq(3).text().trim();
            const updateText = cells.eq(4).text().trim();
            
            let showRow = true;
            
            // Apply filters
            if (currentFilters.name && !nameText.includes(currentFilters.name)) {
                showRow = false;
            }
            
            if (currentFilters.address && !addressText.includes(currentFilters.address)) {
                showRow = false;
            }
            
            if (currentFilters.location && !addressText.includes(currentFilters.location)) {
                showRow = false;
            }
            
            if (currentFilters.status && !statusText.includes(currentFilters.status)) {
                showRow = false;
            }
            
            if (currentFilters.update) {
                const hasUpdate = updateText.includes('SUDAH KEMASKINI');
                if (currentFilters.update === 'YES' && !hasUpdate) showRow = false;
                if (currentFilters.update === 'NO' && hasUpdate) showRow = false;
            }
            
            if (currentFilters.state && !addressText.includes(currentFilters.state.toLowerCase())) {
                showRow = false;
            }
            
            // Show or hide row
            if (showRow) {
                row.show();
                visibleCount++;
            } else {
                row.hide();
            }
        });
        
        // Update results count
        $('#visibleCount').text(visibleCount);
        console.log('Filters applied, visible rows:', visibleCount);
    }
    
    // Clear all filters
    function clearAllFilters() {
        console.log('Clearing all filters');
        
        // Reset filter object
        currentFilters = {
            name: '',
            address: '',
            location: '',
            status: '',
            update: '',
            state: ''
        };
        
        // Clear form inputs
        $('#searchName, #searchAddress, #searchLocation').val('');
        $('#filterStatus, #filterUpdate, #filterState').val('');
        
        // Remove active class from quick filter buttons
        $('.quick-filter-btn').removeClass('active');
        
        // Show all rows
        allRows.show();
        
        // Update results count
        updateResultsCount();
        
        // Focus on first input
        $('#searchName').focus();
        
        console.log('All filters cleared');
    }
    
    // Handle quick filter buttons
    function handleQuickFilter(button) {
        const filterType = button.data('filter');
        const filterValue = button.data('value');
        
        console.log('Quick filter:', filterType, filterValue);
        
        // Remove active class from all quick filter buttons
        $('.quick-filter-btn').removeClass('active');
        
        // Add active class to clicked button
        button.addClass('active');
        
        // Clear all other filters first
        clearAllFilters();
        
        // Apply the specific quick filter
        switch(filterType) {
            case 'status':
                currentFilters.status = filterValue;
                $('#filterStatus').val(filterValue);
                break;
            case 'update':
                currentFilters.update = filterValue;
                $('#filterUpdate').val(filterValue);
                break;
            case 'location':
                currentFilters.state = filterValue;
                $('#filterState').val(filterValue);
                break;
        }
        
        applyFilters();
    }
    
    // Update results count
    function updateResultsCount() {
        const totalCount = allRows.length;
        const visibleCount = allRows.filter(':visible').length;
        
        $('#visibleCount').text(visibleCount);
        $('#totalCount').text(totalCount);
        
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
    
    // Make functions globally available
    window.showCustomerDataDetails = showCustomerDataDetails;
    
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
    
    console.log('Customer data page initialization complete with jQuery');
});

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