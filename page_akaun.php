<?php
// Start session and check if user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user information from session
$user_fullname = $_SESSION['user_fullname'];
$user_no_gaji = $_SESSION['user_no_gaji'];
$user_level = $_SESSION['user_level'];
$user_ispaid = $_SESSION['user_ispaid'];
$user_id = $_SESSION['user_id'];
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user-circle me-3"></i>Akaun Saya
                </h2>
                <a href="main.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <!-- User Profile Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>Maklumat Profil
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Profile Image Placeholder -->
                        <div class="col-md-3 text-center mb-4">
                            <div class="profile-image-placeholder mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #e9ecef;">
                                <i class="fas fa-user" style="font-size: 3rem; color: #6c757d;"></i>
                            </div>
                            <h6 class="text-muted">Gambar Profil</h6>
                            <small class="text-muted">Tiada gambar</small>
                        </div>
                        
                        <!-- Profile Details -->
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Nama Penuh:</label>
                                    <div class="p-2 bg-light rounded">
                                        <?php echo htmlspecialchars($user_fullname); ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">No. Gaji:</label>
                                    <div class="p-2 bg-light rounded">
                                        <?php echo htmlspecialchars($user_no_gaji); ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Tahap Pengguna:</label>
                                    <div class="p-2 bg-light rounded">
                                        <span class="badge bg-<?php echo $user_level === 'ADMIN' ? 'danger' : 'primary'; ?> fs-6">
                                            <?php echo htmlspecialchars($user_level); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Status Bayaran:</label>
                                    <div class="p-2 bg-light rounded">
                                        <span class="badge bg-<?php echo $user_ispaid === 'PAID' ? 'success' : 'warning'; ?> fs-6">
                                            <?php echo htmlspecialchars($user_ispaid); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">ID Pengguna:</label>
                                    <div class="p-2 bg-light rounded">
                                        <?php echo htmlspecialchars($user_id); ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Tarikh Log Masuk:</label>
                                    <div class="p-2 bg-light rounded">
                                        <?php echo date('d/m/Y H:i:s'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Tindakan Akaun
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-primary btn-lg" disabled>
                                    <i class="fas fa-edit me-2"></i>Kemaskini Profil
                                    <br><small class="text-muted">Akan datang</small>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-secondary btn-lg" disabled>
                                    <i class="fas fa-key me-2"></i>Tukar Kata Laluan
                                    <br><small class="text-muted">Akan datang</small>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Logout Section -->
                    <div class="text-center">
                        <h6 class="text-muted mb-3">Keluar dari sistem</h6>
                        <button onclick="confirmLogout()" class="btn btn-danger btn-lg px-5">
                            <i class="fas fa-sign-out-alt me-2"></i>Log Keluar
                        </button>
                        <br>
                        <small class="text-muted mt-2 d-block">Klik untuk keluar dari akaun anda dengan selamat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Adakah anda pasti?',
        text: 'Anda akan dilog keluar dari sistem',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Log Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Sila tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Redirect to logout
            window.location.href = 'index.php?logout=1';
        }
    });
}
</script>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn:disabled {
    opacity: 0.6;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.profile-image-placeholder {
    transition: all 0.3s ease;
}

.profile-image-placeholder:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style> 