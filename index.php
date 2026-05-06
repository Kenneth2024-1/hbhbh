<?php
// index.php - Home / Landing page

require_once 'includes/config.php';
include 'includes/header.php';

// Flash messages
$success_msg = $_SESSION['success'] ?? '';
$error_msg   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Optional location filter
$selected_location = isset($_GET['location']) ? trim($_GET['location']) : '';

// Helper: fetch providers by category
function getProvidersByCategory($pdo, $category, $limit = 4) {
    $sql = "SELECT p.*, u.full_name, u.location, u.rating 
            FROM provider_profiles p 
            JOIN users u ON p.user_id = u.id 
            WHERE u.role = 'provider' AND p.category = ?
            ORDER BY u.rating DESC, RAND()
            LIMIT ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Define categories and subcategories
$categories = [
    'Event & Personal Services' => [
        'subcategories' => [
            'Catering Services', 'Photography & Videography', 'Event Planning', 
            'Makeup Artist Services', 'DJ Services'
        ],
        'icon' => 'fa-calendar-alt'
    ],
    'Moving & Logistics' => [
        'subcategories' => [
            'Moving Services', 'Courier & Delivery Services', 'Transport Services'
        ],
        'icon' => 'fa-truck'
    ],
    'Automotive Services' => [
        'subcategories' => [
            'Car Repair / Mechanic', 'Car Wash Services', 'Car Towing Services', 'Vehicle Inspection'
        ],
        'icon' => 'fa-car'
    ],
    'Technical & IT Services' => [
        'subcategories' => [
            'Computer Repair & Maintenance', 'Network Installation', 'Website Development',
            'Graphic Design', 'Software Installation', 'Cybersecurity Services', 'Phone Repair'
        ],
        'icon' => 'fa-laptop-code'
    ],
    'Home & Property Services' => [
        'subcategories' => [
            'CCTV & Alarm System Installation', 'Borehole Drilling & Water Tank Installation',
            'Plumbing', 'Carpet Cleaning', 'Internet Installers and Maintenance', 'Appliance Installation'
        ],
        'icon' => 'fa-home'
    ],
    'Skilled Labor Services' => [
        'subcategories' => [
            'Welding Services', 'Aluminum & Glass Fitting', 'Furniture Making', 'Tailoring Services'
        ],
        'icon' => 'fa-tools'
    ]
];

// Top providers (random for now)
$top_sql = "SELECT p.*, u.full_name, u.location 
            FROM provider_profiles p 
            JOIN users u ON p.user_id = u.id 
            WHERE u.role = 'provider' 
            ORDER BY RAND() LIMIT 4";
$top_stmt = $pdo->prepare($top_sql);

$top_providers = $top_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero py-5 bg-light rounded-4 shadow-sm mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold">
                        Connect with trusted <span class="text-primary">service providers</span> in your area
                    </h1>
                    <p class="lead mt-3">
                        Find plumbers, electricians, cleaners, carpenters, and more – all verified and rated by real customers.
                    </p>
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-primary btn-lg me-3">Get Started</a>
                        <a href="#how-it-works" class="btn btn-outline-primary btn-lg">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                         alt="Technicians working together" class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Why Choose Us?</h2>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body py-5">
                            <i class="fas fa-check-circle text-primary fa-4x mb-3"></i>
                            <h5 class="card-title">Verified Professionals</h5>
                            <p class="card-text">All providers undergo checks and are rated by real users.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body py-5">
                            <i class="fas fa-clock text-primary fa-4x mb-3"></i>
                            <h5 class="card-title">Quick & Easy Booking</h5>
                            <p class="card-text">Find and book services in just a few clicks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body py-5">
                            <i class="fas fa-shield-alt text-primary fa-4x mb-3"></i>
                            <h5 class="card-title">Secure Transactions</h5>
                            <p class="card-text">Payments held safely until job completion.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Providers -->
    <section id="providers" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Meet Our Top Service Providers</h2>
            <?php if (count($top_providers) > 0): ?>
                <div class="row g-4">
                    <?php foreach ($top_providers as $provider): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm text-center">
                                <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a6b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=300&q=80" 
                                     class="card-img-top rounded-circle mx-auto mt-4" 
                                     style="width: 120px; height: 120px; object-fit: cover;" 
                                     alt="Provider">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($provider['full_name']) ?></h5>
                                    <p class="text-primary fw-bold"><?= htmlspecialchars($provider['skills'] ?? 'Handyman') ?></p>
                                    <p class="small">
                                        <i class="fas fa-map-marker-alt me-1"></i> 
                                        <?= htmlspecialchars($provider['location'] ?? 'Nairobi') ?>
                                    </p>
                                    <div class="mb-2">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                        <span class="ms-1 small">(4.5)</span>
                                    </div>
                                    <a href="<?= BASE_URL ?>provider/profile.php?id=<?= $provider['user_id'] ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center lead">No providers available yet. <a href="<?= BASE_URL ?>auth/register.php">Become one!</a></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ========== Category Sections ========== -->
    <?php foreach ($categories as $groupName => $group): ?>
        <section class="py-5 category-section">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="fw-bold">
                        <i class="fas <?= $group['icon'] ?> text-primary me-2"></i> 
                        <?= $groupName ?>
                    </h2>
                    <a href="<?= BASE_URL ?>services.php?category=<?= urlencode($groupName) ?>" class="btn btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="row g-4">
                    <?php 
                    $hasAny = false;
                    foreach ($group['subcategories'] as $subCat):
                        $providersInSub = getProvidersByCategory($pdo, $subCat, 3);
                        if (count($providersInSub) > 0):
                            $hasAny = true;
                    ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-white border-0 pt-3">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        <?= htmlspecialchars($subCat) ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($providersInSub as $provider): ?>
                                        <div class="d-flex align-items-start mb-3">
                                            <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a6b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=60&h=60&q=80" 
                                                 class="rounded-circle me-2" style="width: 45px; height: 45px; object-fit: cover;">
                                            <div>
                                                <a href="<?= BASE_URL ?>provider/profile.php?id=<?= $provider['user_id'] ?>" 
                                                   class="text-decoration-none fw-bold">
                                                    <?= htmlspecialchars($provider['full_name']) ?>
                                                </a>
                                                <div class="small text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i> 
                                                    <?= htmlspecialchars($provider['location'] ?? 'Nairobi') ?>
                                                </div>
                                                <div class="small">
                                                    <i class="fas fa-star text-warning"></i> 
                                                    <?= number_format($provider['rating'] ?? 0, 1) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (count($providersInSub) < 3): ?>
                                        <p class="text-muted small">More providers coming soon</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-white border-0 pb-3">
                                    <a href="<?= BASE_URL ?>services.php?category=<?= urlencode($subCat) ?>" class="btn btn-sm btn-outline-primary w-100">
                                        Browse <?= $subCat ?> <i class="fas fa-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endif;
                    endforeach;
                    if (!$hasAny):
                    ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i> 
                                No providers yet in <?= $groupName ?>. <a href="<?= BASE_URL ?>auth/register.php?role=provider">Become a provider</a>!
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <!-- How It Works -->
    <section id="how-it-works" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">How It Works</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="How it works" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <div class="d-flex mb-4">
                        <span class="badge bg-primary rounded-circle p-3 me-3 fs-5">1</span>
                        <div><h5>Create an account</h5><p>Sign up as client or provider quickly.</p></div>
                    </div>
                    <div class="d-flex mb-4">
                        <span class="badge bg-primary rounded-circle p-3 me-3 fs-5">2</span>
                        <div><h5>Search or list services</h5><p>Clients browse; providers showcase skills.</p></div>
                    </div>
                    <div class="d-flex mb-4">
                        <span class="badge bg-primary rounded-circle p-3 me-3 fs-5">3</span>
                        <div><h5>Book & complete the job</h5><p>Agree, work, review, and pay securely.</p></div>
                    </div>
                    <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-primary btn-lg mt-3">Join Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-5 bg-primary text-white text-center rounded-4">
        <div class="container">
            <h2 class="mb-3">Ready to get started?</h2>
            <p class="lead mb-4">Join satisfied customers and trusted providers today.</p>
            <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-light btn-lg me-3">
                <i class="fas fa-user-plus me-1"></i> Sign Up Now
            </a>
            <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
        </div>
    </section>

</div>

<?php include 'includes/footer.php'; ?>