<?php
// kemaskini_data.php - Edit customer data page
// Session is already checked in main.php

// Include database connection
include("db_connect.php");

$error_message = '';
$success_message = '';
$data_record = null;

// Get the data ID from URL parameter
$data_id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($data_id)) {
    $error_message = "ID data tidak dijumpai.";
} else {
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_data'])) {
        if ($connection !== null) {
            // Get form data with validation
            $namapemilik = mysqli_real_escape_string($connection, trim($_POST['namapemilik'] ?? ''));
            $norumah = mysqli_real_escape_string($connection, trim($_POST['norumah'] ?? ''));
            $alamat1 = mysqli_real_escape_string($connection, trim($_POST['alamat1'] ?? ''));
            $alamat2 = mysqli_real_escape_string($connection, trim($_POST['alamat2'] ?? ''));
            $alamat3 = mysqli_real_escape_string($connection, trim($_POST['alamat3'] ?? ''));
            $alamat4 = mysqli_real_escape_string($connection, trim($_POST['alamat4'] ?? ''));
            $poskod = mysqli_real_escape_string($connection, trim($_POST['poskod'] ?? ''));
            $negeri = mysqli_real_escape_string($connection, trim($_POST['negeri'] ?? ''));
            $bakitunggakan = mysqli_real_escape_string($connection, trim($_POST['bakitunggakan'] ?? '0'));
            
            // Body fields for notice (letter address)
            $namapemilik_body = mysqli_real_escape_string($connection, trim($_POST['namapemilik_body'] ?? ''));
            $norumah_body = mysqli_real_escape_string($connection, trim($_POST['norumah_body'] ?? ''));
            $addr1_body = mysqli_real_escape_string($connection, trim($_POST['addr1_body'] ?? ''));
            $addr2_body = mysqli_real_escape_string($connection, trim($_POST['addr2_body'] ?? ''));
            $postcode_body = mysqli_real_escape_string($connection, trim($_POST['postcode_body'] ?? ''));
            $state_body = mysqli_real_escape_string($connection, trim($_POST['state_body'] ?? ''));
            
            // Status and date fields
            $status_data = mysqli_real_escape_string($connection, trim($_POST['status_data'] ?? ''));
            $tarikh_buat = mysqli_real_escape_string($connection, trim($_POST['tarikh_buat'] ?? ''));

            // Validation
            if (empty($namapemilik) || empty($alamat1)) {
                $error_message = "Nama Pemilik dan Alamat 1 adalah wajib.";
            } else {
                // Update the record with all fields
                $update_sql = "UPDATE TBL_DATA SET 
                    NAMAPEMILIK = '$namapemilik',
                    NORUMAH = '$norumah',
                    ALAMAT1 = '$alamat1',
                    ALAMAT2 = '$alamat2',
                    ALAMAT3 = '$alamat3',
                    ALAMAT4 = '$alamat4',
                    POSKOD = '$poskod',
                    NEGERI = '$negeri',
                    BAKITUNGGAKAN = '$bakitunggakan',
                    NAMAPEMILIK_BODY = '$namapemilik_body',
                    NORUMAH_BODY = '$norumah_body',
                    ADDR1_BODY = '$addr1_body',
                    ADDR2_BODY = '$addr2_body',
                    POSTCODE_BODY = '$postcode_body',
                    STATE_BODY = '$state_body',
                    STATUS_DATA = '$status_data',
                    TARIKH_BUAT = '$tarikh_buat',
                    STATUS_UPDATE = 'YES'
                    WHERE iddata = '$data_id' AND (
                        INSBY = '".$_SESSION['user_id']."' OR 
                        NAMAPEMILIK LIKE '%".$_SESSION['user_fullname']."%'
                    )";
                
                if (mysqli_query($connection, $update_sql)) {
                    if (mysqli_affected_rows($connection) > 0) {
                        $success_message = "Data berjaya dikemaskini!";
                    } else {
                        $error_message = "Tiada perubahan dibuat atau anda tidak mempunyai kebenaran untuk mengedit data ini.";
                    }
                } else {
                    $error_message = "Ralat semasa mengemas kini data: " . mysqli_error($connection);
                }
            }
        } else {
            $error_message = "Ralat sambungan pangkalan data.";
        }
    }

    // Fetch the current data
    if ($connection !== null) {
        $fetch_sql = "SELECT * FROM TBL_DATA WHERE iddata = '$data_id' AND (
            INSBY = '".$_SESSION['user_id']."' OR 
            NAMAPEMILIK LIKE '%".$_SESSION['user_fullname']."%'
        )";
        $result = mysqli_query($connection, $fetch_sql);
        
        if ($result && mysqli_num_rows($result) == 1) {
            $data_record = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Data tidak dijumpai atau anda tidak mempunyai kebenaran untuk mengedit data ini.";
        }
    }
}
?>

<div class="page-card">
    <div class="card-header kemaskini-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4><i class="fas fa-edit me-2"></i>Kemaskini Maklumat Data</h4>
                <p class="mb-0 text-light">Edit alamat dan maklumat amaun untuk <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></p>
            </div>
            <div>
                <a href="?page=customer_data.php" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($data_record): ?>
            <form method="POST" class="kemaskini-form">
                
                <!-- Header Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="info-card">
                            <h6><i class="fas fa-info-circle me-2"></i>Maklumat Data</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">ID Data:</small>
                                    <p class="fw-bold"><?php echo htmlspecialchars($data_record['iddata']); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">No Fail TF:</small>
                                    <p class="fw-bold"><?php echo htmlspecialchars($data_record['NOFAILTF'] ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Status Semasa:</small>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($data_record['STATUS_DATA'] ?? 'N/A'); ?></span>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Tarikh Buat:</small>
                                    <p class="fw-bold"><?php echo htmlspecialchars($data_record['TARIKH_BUAT'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALAMAT PREMIS (Main Address) -->
                <div class="address-section mb-4">
                    <div class="section-header alamat-premis">
                        <h5><i class="fas fa-building me-2"></i>ALAMAT PREMIS</h5>
                        <small>Alamat utama premis/hartanah</small>
                    </div>
                    <div class="address-form-group">
                        <div class="form-row">
                            <label>Nama Pemilik *</label>
                            <input type="text" class="form-control" name="namapemilik" 
                                   value="<?php echo htmlspecialchars($data_record['NAMAPEMILIK'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div class="form-row">
                            <label>No Rumah</label>
                            <input type="text" class="form-control" name="norumah" 
                                   value="<?php echo htmlspecialchars($data_record['NORUMAH'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Alamat 1 *</label>
                            <input type="text" class="form-control" name="alamat1" 
                                   value="<?php echo htmlspecialchars($data_record['ALAMAT1'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div class="form-row">
                            <label>Alamat 2</label>
                            <input type="text" class="form-control" name="alamat2" 
                                   value="<?php echo htmlspecialchars($data_record['ALAMAT2'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Alamat 3</label>
                            <input type="text" class="form-control" name="alamat3" 
                                   value="<?php echo htmlspecialchars($data_record['ALAMAT3'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Alamat 4</label>
                            <input type="text" class="form-control" name="alamat4" 
                                   value="<?php echo htmlspecialchars($data_record['ALAMAT4'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Poskod</label>
                            <input type="text" class="form-control" name="poskod" 
                                   value="<?php echo htmlspecialchars($data_record['POSKOD'] ?? ''); ?>" 
                                   maxlength="5">
                        </div>
                        <div class="form-row">
                            <label>Negeri</label>
                            <select class="form-select" name="negeri">
                                <option value="">Pilih Negeri</option>
                                <?php
                                $states = ['Selangor', 'Kuala Lumpur', 'Johor', 'Penang', 'Perak', 'Kedah', 'Kelantan', 'Terengganu', 'Pahang', 'Negeri Sembilan', 'Melaka', 'Perlis', 'Sabah', 'Sarawak'];
                                $current_negeri = $data_record['NEGERI'] ?? '';
                                foreach ($states as $state) {
                                    $selected = ($current_negeri == $state) ? 'selected' : '';
                                    echo "<option value=\"$state\" $selected>$state</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ALAMAT SURAT MENYURAT (Letter Address) -->
                <div class="address-section mb-4">
                    <div class="section-header alamat-surat">
                        <h5><i class="fas fa-envelope me-2"></i>ALAMAT SURAT MENYURAT</h5>
                        <small>Alamat untuk notis dan surat-menyurat rasmi</small>
                        <button type="button" id="copyToNotice" class="btn btn-outline-light btn-sm ms-auto">
                            <i class="fas fa-copy me-1"></i>Salin dari Alamat Premis
                        </button>
                    </div>
                    <div class="address-form-group">
                        <div class="form-row">
                            <label>Nama Pemilik (Surat)</label>
                            <input type="text" class="form-control" name="namapemilik_body" 
                                   value="<?php echo htmlspecialchars($data_record['NAMAPEMILIK_BODY'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>No Rumah (Surat)</label>
                            <input type="text" class="form-control" name="norumah_body" 
                                   value="<?php echo htmlspecialchars($data_record['NORUMAH_BODY'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Alamat 1 (Surat)</label>
                            <input type="text" class="form-control" name="addr1_body" 
                                   value="<?php echo htmlspecialchars($data_record['ADDR1_BODY'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Alamat 2 (Surat)</label>
                            <input type="text" class="form-control" name="addr2_body" 
                                   value="<?php echo htmlspecialchars($data_record['ADDR2_BODY'] ?? ''); ?>" 
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-row">
                            <label>Poskod (Surat)</label>
                            <input type="text" class="form-control" name="postcode_body" 
                                   value="<?php echo htmlspecialchars($data_record['POSTCODE_BODY'] ?? ''); ?>" 
                                   maxlength="5">
                        </div>
                        <div class="form-row">
                            <label>Negeri (Surat)</label>
                            <select class="form-select" name="state_body">
                                <option value="">Pilih Negeri</option>
                                <?php
                                $current_state_body = $data_record['STATE_BODY'] ?? '';
                                foreach ($states as $state) {
                                    $selected = ($current_state_body == $state) ? 'selected' : '';
                                    echo "<option value=\"$state\" $selected>$state</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- MAKLUMAT KEWANGAN & STATUS -->
                <div class="address-section mb-4">
                    <div class="section-header maklumat-lain">
                        <h5><i class="fas fa-cog me-2"></i>MAKLUMAT KEWANGAN & STATUS</h5>
                        <small>Amaun tunggakan dan status data</small>
                    </div>
                    <div class="address-form-group">
                        <div class="form-row">
                            <label>Baki Tunggakan (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" class="form-control" name="bakitunggakan" 
                                       value="<?php echo htmlspecialchars($data_record['BAKITUNGGAKAN'] ?? '0'); ?>" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-row">
                            <label>Status Data</label>
                            <select class="form-select" name="status_data">
                                <option value="">Pilih Status</option>
                                <option value="BELUM BUAT" <?php echo ($data_record['STATUS_DATA'] == 'BELUM BUAT') ? 'selected' : ''; ?>>Belum Buat</option>
                                <option value="SUDAH BUAT" <?php echo ($data_record['STATUS_DATA'] == 'SUDAH BUAT') ? 'selected' : ''; ?>>Sudah Buat</option>
                                <option value="DALAM PROSES" <?php echo ($data_record['STATUS_DATA'] == 'DALAM PROSES') ? 'selected' : ''; ?>>Dalam Proses</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Tarikh Buat</label>
                            <input type="date" class="form-control" name="tarikh_buat" 
                                   value="<?php echo htmlspecialchars($data_record['TARIKH_BUAT'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <div class="d-flex justify-content-between">
                        <a href="?page=customer_data.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" name="update_data" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Kemaskini
                        </button>
                    </div>
                </div>

            </form>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>Data Tidak Dijumpai</h5>
                <p>Data yang diminta tidak dijumpai atau anda tidak mempunyai kebenaran untuk mengaksesnya.</p>
                <a href="?page=customer_data.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Data Saya
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Kemaskini Data Page Styles */
.kemaskini-header {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white;
}

.info-card {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #f39c12;
}

/* Address Section Styling */
.address-section {
    background-color: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

.section-header {
    padding: 1.2rem 1.5rem;
    color: white;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.section-header h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
}

.section-header small {
    display: block;
    margin-top: 0.3rem;
    opacity: 0.9;
    font-size: 0.85rem;
}

/* Different colors for different sections */
.alamat-premis {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
}

.alamat-surat {
    background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
}

.maklumat-lain {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
}

.address-form-group {
    padding: 1.5rem;
}

.form-row {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
}

.form-row label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-row label::after {
    content: "";
}

.form-row label:contains("*")::after {
    content: " *";
    color: #e74c3c;
    font-weight: bold;
}

.form-row .form-control,
.form-row .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-row .form-control:focus,
.form-row .form-select:focus {
    border-color: #f39c12;
    box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
    outline: none;
}

.form-row .input-group .input-group-text {
    background-color: #f39c12;
    color: white;
    border-color: #f39c12;
    font-weight: 600;
}

/* Copy button styling */
#copyToNotice {
    border: 2px solid rgba(255, 255, 255, 0.7);
    color: white;
    transition: all 0.3s ease;
}

#copyToNotice:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: white;
    color: white;
}

.action-buttons {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem 1.5rem;
    border-radius: 0 0 15px 15px;
    margin-top: 2rem;
}

.btn-success {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    border: none;
    border-radius: 10px;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
}

.btn-secondary {
    border-radius: 10px;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    border: 2px solid #6c757d;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .kemaskini-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .section-header {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .address-form-group {
        padding: 1rem;
    }
    
    .action-buttons {
        padding: 1.5rem 1rem;
    }
    
    .action-buttons .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
    }
    
    #copyToNotice {
        margin-top: 0.5rem;
        width: 100%;
    }
}

/* Form validation styling */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #e74c3c;
    box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
}

.form-control.is-valid,
.form-select.is-valid {
    border-color: #27ae60;
    box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
}

/* Address section animations */
.address-section {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced info card */
.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Kemaskini data page loaded');
    
    // Auto-copy main address to notice address
    const copyButton = document.getElementById('copyToNotice');
    if (copyButton) {
        copyButton.addEventListener('click', function() {
            // Get values from main address (premis)
            const namaPemilik = document.querySelector('input[name="namapemilik"]').value;
            const noRumah = document.querySelector('input[name="norumah"]').value;
            const alamat1 = document.querySelector('input[name="alamat1"]').value;
            const alamat2 = document.querySelector('input[name="alamat2"]').value;
            const poskod = document.querySelector('input[name="poskod"]').value;
            const negeri = document.querySelector('select[name="negeri"]').value;
            
            // Set values to letter address (surat)
            document.querySelector('input[name="namapemilik_body"]').value = namaPemilik;
            document.querySelector('input[name="norumah_body"]').value = noRumah;
            document.querySelector('input[name="addr1_body"]').value = alamat1;
            document.querySelector('input[name="addr2_body"]').value = alamat2;
            document.querySelector('input[name="postcode_body"]').value = poskod;
            document.querySelector('select[name="state_body"]').value = negeri;
            
            // Show feedback
            this.innerHTML = '<i class="fas fa-check me-1"></i>Disalin!';
            this.classList.add('btn-success');
            this.classList.remove('btn-outline-light');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy me-1"></i>Salin dari Alamat Premis';
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-light');
            }, 2000);
        });
    }

    // Form validation
    const kemaskiniForm = document.querySelector('.kemaskini-form');
    if (kemaskiniForm) {
        kemaskiniForm.addEventListener('submit', function(e) {
            const namaPemilik = document.querySelector('input[name="namapemilik"]').value.trim();
            const alamat1 = document.querySelector('input[name="alamat1"]').value.trim();
            
            // Clear previous validation states
            document.querySelectorAll('.form-control, .form-select').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            
            let isValid = true;
            
            // Validate required fields
            if (!namaPemilik) {
                document.querySelector('input[name="namapemilik"]').classList.add('is-invalid');
                isValid = false;
            } else {
                document.querySelector('input[name="namapemilik"]').classList.add('is-valid');
            }
            
            if (!alamat1) {
                document.querySelector('input[name="alamat1"]').classList.add('is-invalid');
                isValid = false;
            } else {
                document.querySelector('input[name="alamat1"]').classList.add('is-valid');
            }
            
            if (!isValid) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Maklumat Tidak Lengkap',
                        text: 'Nama Pemilik dan Alamat 1 adalah wajib!',
                        icon: 'warning',
                        confirmButtonColor: '#f39c12'
                    });
                } else {
                    alert('Nama Pemilik dan Alamat 1 adalah wajib!');
                }
                
                // Focus on first invalid field
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                submitBtn.disabled = true;
            }
            
            return true;
        });
    }

    // Amount validation
    const bakiTunggakan = document.querySelector('input[name="bakitunggakan"]');
    if (bakiTunggakan) {
        bakiTunggakan.addEventListener('input', function() {
            let value = parseFloat(this.value);
            if (value < 0) {
                this.value = 0;
            }
        });
    }

    // Auto uppercase transformation for all address fields
    const addressFields = document.querySelectorAll('input[oninput*="toUpperCase"]');
    addressFields.forEach(field => {
        field.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    // Enhanced form interaction feedback
    document.querySelectorAll('.form-control, .form-select').forEach(field => {
        field.addEventListener('focus', function() {
            this.closest('.form-row').style.transform = 'scale(1.02)';
            this.closest('.form-row').style.transition = 'all 0.2s ease';
        });
        
        field.addEventListener('blur', function() {
            this.closest('.form-row').style.transform = 'scale(1)';
        });
    });

    // Show success message with SweetAlert if available
    <?php if (!empty($success_message)): ?>
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Berjaya Dikemaskini!',
            text: '<?php echo addslashes($success_message); ?>',
            icon: 'success',
            confirmButtonColor: '#27ae60',
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            // Optional: redirect or refresh
            // window.location.href = '?page=customer_data.php';
        });
    }
    <?php endif; ?>

    // Animate sections on load
    const sections = document.querySelectorAll('.address-section');
    sections.forEach((section, index) => {
        section.style.animationDelay = `${index * 0.1}s`;
    });

    console.log('Kemaskini data page initialization complete');
});
</script> 