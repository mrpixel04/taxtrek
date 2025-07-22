<?php
// Include database connection
include("db_connect.php");

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
$base_url = dirname($base_url) . '/';
?>

<div class="page-card">
    <div class="card-header">
        <h4><i class="fas fa-database me-2"></i>Data Taskforce</h4>
    </div>
    <div class="card-body">
        <!-- Search and Filter -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari data...">
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="BELUM BUAT">Belum Buat</option>
                    <option value="SUDAH BUAT">Sudah Buat</option>
                </select>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="dataTable">
                <thead class="table-dark">
                    <tr>
                        <th>BIL</th>
                        <th>NAMA PEMILIK</th>
                        <th>ALAMAT 1</th>
                        <th>STATUS</th>
                        <th>STATUS KEMASKINI</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($connection !== null) {
                        $sql = "SELECT * FROM TBL_DATA";
                        $result = mysqli_query($connection, $sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $bil = 1;
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $bil . "</td>";
                                echo "<td>" . htmlspecialchars($row['NAMA_PEMILIK'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['ALAMAT_1'] ?? 'N/A') . "</td>";
                                echo "<td>";
                                $status = $row['STATUS_DATA'] ?? 'N/A';
                                $statusClass = ($status == 'SUDAH BUAT') ? 'success' : 'warning';
                                echo "<span class='badge bg-$statusClass'>$status</span>";
                                echo "</td>";
                                echo "<td>";
                                $statusKemaskini = $row['STATUS_KEMASKINI'] ?? 'N/A';
                                $statusKemaskiniClass = ($statusKemaskini == 'SUDAH KEMASKINI') ? 'success' : 'secondary';
                                echo "<span class='badge bg-$statusKemaskiniClass'>$statusKemaskini</span>";
                                echo "</td>";

                                // Action buttons
                                $urlprintnotisbm = $base_url."cetaknotisbm.php?data=".$row['iddata'];
                                $urlprintnotisbi = $base_url."printnotisbi.php?data=".$row['iddata'];
                                $urleditnotis = "?page=editalamat.php&data=".$row['iddata'];

                                echo "<td>";
                                echo "<div class='btn-group btn-group-sm' role='group'>";
                                echo "<button type='button' class='btn btn-outline-primary btn-sm view-btn' data-details='" . htmlspecialchars(json_encode($row)) . "'>";
                                echo "<i class='fas fa-eye me-1'></i>Lihat</button>";
                                echo "<a href='".$urleditnotis."' class='btn btn-outline-warning btn-sm'>";
                                echo "<i class='fas fa-edit me-1'></i>Alamat</a>";
                                echo "<a href='".$urlprintnotisbm."' class='btn btn-outline-success btn-sm' target='_blank'>";
                                echo "<i class='fas fa-print me-1'></i>Notis</a>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                                $bil++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted py-4'>";
                            echo "<i class='fas fa-inbox fa-3x mb-3 d-block'></i>";
                            echo "Tiada data untuk dipaparkan";
                            echo "</td></tr>";
                        }
                        mysqli_close($connection);
                    } else {
                        echo "<tr><td colspan='6' class='text-center text-danger'>Ralat sambungan pangkalan data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" id="paginationNav" style="display: none;">
            <ul class="pagination justify-content-center" id="pagination">
            </ul>
        </nav>
    </div>
</div>

<!-- Modal for View Details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Maklumat Data Cukai Taksiran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-details"></div>
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
/* Custom styles for this page */
.btn-group-sm .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
}

.table td {
    font-size: 0.85rem;
    vertical-align: middle;
}

#modal-details {
    max-height: 400px;
    overflow-y: auto;
}

#modal-details p {
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    border-left: 3px solid var(--primary-color);
}

#modal-details p strong {
    color: var(--primary-color);
}
</style>

<script>
// Pure JavaScript implementation (no jQuery conflicts)
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('dataTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty rows

            const nama = row.cells[1].textContent.toLowerCase();
            const alamat = row.cells[2].textContent.toLowerCase();
            const status = row.cells[3].textContent.trim();

            const matchesSearch = nama.includes(searchTerm) || alamat.includes(searchTerm);
            const matchesStatus = !statusValue || status.includes(statusValue);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // View button functionality
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function() {
            const data = JSON.parse(this.getAttribute('data-details'));
            const modalDetails = document.getElementById('modal-details');
            
            // Clear existing content
            modalDetails.innerHTML = '';
            
            // Create formatted details
            for (const [key, value] of Object.entries(data)) {
                if (key && value) {
                    const p = document.createElement('p');
                    p.innerHTML = `<strong>${key.replace(/_/g, ' ')}:</strong> ${value}`;
                    modalDetails.appendChild(p);
                }
            }
            
            // Show modal using Bootstrap 5
            const modal = new bootstrap.Modal(document.getElementById('viewModal'));
            modal.show();
        });
    });

    console.log('Data Taskforce page initialized without conflicts');
});
</script>

