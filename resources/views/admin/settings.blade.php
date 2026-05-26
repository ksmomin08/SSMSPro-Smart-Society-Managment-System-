@extends('layouts.admin')

@section('title', 'System Settings — Smart Society Management')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h1 class="page-title">
            <i class="bx bx-cog"></i> System Configurations
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Site Settings</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row animate-fadeInUp" style="animation-delay: 0.1s;">
    <div class="col-12">
        <div class="nav-align-top mb-4">
            <!-- Settings Tabs Header -->
            <ul class="nav nav-tabs" role="tablist" style="border-bottom: 1px solid rgba(0,0,0,0.08); font-family: 'Poppins', sans-serif;">
                <li class="nav-item">
                    <button type="button" class="nav-link active fw-semibold" role="tab" data-bs-toggle="tab" data-bs-target="#tab-general" aria-controls="tab-general" aria-selected="true">
                        <i class="bx bx-slider-alt me-1"></i> General Branding
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link fw-semibold" role="tab" data-bs-toggle="tab" data-bs-target="#tab-theme" aria-controls="tab-theme" aria-selected="false">
                        <i class="bx bx-palette me-1"></i> Style Theme Colors
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link fw-semibold" role="tab" data-bs-toggle="tab" data-bs-target="#tab-smtp" aria-controls="tab-smtp" aria-selected="false">
                        <i class="bx bx-envelope me-1"></i> SMTP Email Server
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link fw-semibold" role="tab" data-bs-toggle="tab" data-bs-target="#tab-announcement" aria-controls="tab-announcement" aria-selected="false">
                        <i class="bx bx-broadcast me-1"></i> Alert Broadcast
                    </button>
                </li>
            </ul>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                @csrf
                <div class="tab-content card border-0 shadow-none p-0 mt-3">
                    
                    <!-- 1. GENERAL BRANDING TAB -->
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent d-flex align-items-center">
                                <h5 class="card-title text-primary"><i class="bx bx-building me-1"></i> Society Corporate Branding</h5>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold" for="website_title">Website / Portal Title <span class="text-danger">*</span></label>
                                        <input type="text" id="website_title" name="website_title" class="form-control" value="{{ $settings->website_title }}" required />
                                        <div class="form-text">This name appears in your browser title bar, sidebars, and alerts.</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="contact_email">Support / Contact Email Address</label>
                                        <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ $settings->contact_email }}" placeholder="support@yoursociety.com" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="contact_phone">Society Helpline Number</label>
                                        <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="{{ $settings->contact_phone }}" placeholder="+91 98765 43210" />
                                    </div>

                                    <!-- Logo Upload -->
                                    <div class="col-md-6 mt-4">
                                        <label class="form-label fw-semibold">Corporate Logo</label>
                                        <div class="d-flex align-items-center gap-3 p-3 border rounded" style="background-color: #fcfcfd;">
                                            <div style="width: 80px; height: 80px; background: #eaedf1; border-radius: 8px; display: inline-flex; align-items:center; justify-content:center; overflow:hidden; border: 1px solid #dcdcdc;">
                                                @if($settings->logo)
                                                    <img id="logoPreview" src="{{ asset('storage/' . $settings->logo) }}" style="width:100%; height:100%; object-fit:cover;" />
                                                @else
                                                    <i id="logoIcon" class="bx bx-image fs-1 text-muted"></i>
                                                    <img id="logoPreview" style="width:100%; height:100%; object-fit:cover; display:none;" />
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" id="logo" name="logo" class="form-control mb-2" accept="image/*" onchange="previewFile('logo')" />
                                                <small class="text-muted d-block">Dimensions: 300x300px recommended. Max: 2MB.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Favicon Upload -->
                                    <div class="col-md-6 mt-4">
                                        <label class="form-label fw-semibold">Browser Favicon</label>
                                        <div class="d-flex align-items-center gap-3 p-3 border rounded" style="background-color: #fcfcfd;">
                                            <div style="width: 80px; height: 80px; background: #eaedf1; border-radius: 8px; display: inline-flex; align-items:center; justify-content:center; overflow:hidden; border: 1px solid #dcdcdc;">
                                                @if($settings->favicon)
                                                    <img id="faviconPreview" src="{{ asset('storage/' . $settings->favicon) }}" style="width:100%; height:100%; object-fit:cover;" />
                                                @else
                                                    <i id="faviconIcon" class="bx bx-globe fs-1 text-muted"></i>
                                                    <img id="faviconPreview" style="width:100%; height:100%; object-fit:cover; display:none;" />
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" id="favicon" name="favicon" class="form-control mb-2" accept="image/*,.ico" onchange="previewFile('favicon')" />
                                                <small class="text-muted d-block">Standard size: 16x16px or 32x32px. Max: 1MB.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. STYLE THEME COLORS TAB -->
                    <div class="tab-pane fade" id="tab-theme" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent d-flex align-items-center">
                                <h5 class="card-title text-primary"><i class="bx bx-palette me-1"></i> Dynamic Accent Color Engine</h5>
                            </div>
                            <div class="card-body pt-3">
                                <p class="text-muted mb-4" style="font-size: 0.875rem;">
                                    🎨 Adjust the primary color themes of your society dashboard in real-time. Buttons, alerts, shadows, active sidebars, and loaders will automatically re-shade!
                                </p>
                                <div class="row g-4">
                                    <!-- Primary Theme -->
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded" style="background: #f8fafc;">
                                            <label class="form-label fw-semibold d-block mb-2">Primary Palette Accent</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="{{ $settings->primary_color }}" style="width:60px; height:45px; border-radius:6px; cursor:pointer;" />
                                                <input type="text" class="form-control" id="primary_color_text" value="{{ $settings->primary_color }}" style="font-family: monospace;" oninput="syncColor('primary_color')" />
                                            </div>
                                            <small class="text-muted mt-2 d-block">Used for active tags, CTA buttons, and icons.</small>
                                        </div>
                                    </div>

                                    <!-- Secondary Theme -->
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded" style="background: #f8fafc;">
                                            <label class="form-label fw-semibold d-block mb-2">Secondary Accent color</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="{{ $settings->secondary_color }}" style="width:60px; height:45px; border-radius:6px; cursor:pointer;" />
                                                <input type="text" class="form-control" id="secondary_color_text" value="{{ $settings->secondary_color }}" style="font-family: monospace;" oninput="syncColor('secondary_color')" />
                                            </div>
                                            <small class="text-muted mt-2 d-block">Used for secondary badges and border frames.</small>
                                        </div>
                                    </div>

                                    <!-- Sidebar BG -->
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded" style="background: #f8fafc;">
                                            <label class="form-label fw-semibold d-block mb-2">Luxury Sidebar Base Background</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color" id="sidebar_bg_color" name="sidebar_bg_color" value="{{ $settings->sidebar_bg_color }}" style="width:60px; height:45px; border-radius:6px; cursor:pointer;" />
                                                <input type="text" class="form-control" id="sidebar_bg_color_text" value="{{ $settings->sidebar_bg_color }}" style="font-family: monospace;" oninput="syncColor('sidebar_bg_color')" />
                                            </div>
                                            <small class="text-muted mt-2 d-block">Customizes the vertical aside menus background.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. SMTP EMAIL SERVER TAB -->
                    <div class="tab-pane fade" id="tab-smtp" role="tabpanel">
                        <div class="row g-4">
                            <!-- Credentials Card -->
                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent d-flex align-items-center">
                                        <h5 class="card-title text-primary"><i class="bx bx-server me-1"></i> Mail SMTP Configuration</h5>
                                    </div>
                                    <div class="card-body pt-3">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label fw-semibold" for="mail_host">SMTP Server Hostname</label>
                                                <input type="text" id="mail_host" name="mail_host" class="form-control" value="{{ $settings->mail_host }}" placeholder="smtp.gmail.com" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold" for="mail_port">Server Port</label>
                                                <input type="text" id="mail_port" name="mail_port" class="form-control" value="{{ $settings->mail_port }}" placeholder="587" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="mail_username">SMTP Username</label>
                                                <input type="text" id="mail_username" name="mail_username" class="form-control" value="{{ $settings->mail_username }}" placeholder="username@gmail.com" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="mail_password">SMTP Password</label>
                                                <input type="password" id="mail_password" name="mail_password" class="form-control" value="{{ $settings->mail_password }}" placeholder="••••••••••••" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="mail_encryption">Secure Encryption</label>
                                                <select id="mail_encryption" name="mail_encryption" class="form-select">
                                                    <option value="tls" {{ $settings->mail_encryption == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                                                    <option value="ssl" {{ $settings->mail_encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                    <option value="none" {{ $settings->mail_encryption == 'none' ? 'selected' : '' }}>None</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="mail_from_address">Sender Email (From)</label>
                                                <input type="email" id="mail_from_address" name="mail_from_address" class="form-control" value="{{ $settings->mail_from_address }}" placeholder="noreply@yoursociety.com" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Testing Connection Console -->
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, rgba(105, 108, 255, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);">
                                    <div class="card-header bg-transparent d-flex align-items-center">
                                        <h5 class="card-title text-primary"><i class="bx bx-mail-send me-1"></i> Real-Time SMTP Test</h5>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between pt-2">
                                        <p class="text-muted" style="font-size: 0.8125rem;">
                                            Verify your mail configuration before deploying it! Enter a test recipient address to verify that your mail server responds immediately.
                                        </p>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="test_email_recipient">Test Recipient Address</label>
                                            <input type="email" id="test_email_recipient" class="form-control" placeholder="recipient@gmail.com" style="background:#fff;" />
                                        </div>

                                        <button type="button" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-1 py-2 fw-semibold" onclick="testSmtpConnection()">
                                            <i class="bx bx-wifi"></i> Test Server Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. ALERT BROADCAST TAB -->
                    <div class="tab-pane fade" id="tab-announcement" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                                <h5 class="card-title text-primary"><i class="bx bx-broadcast me-1"></i> Society Broadcasting Alert Board</h5>
                                <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" id="announcement_status" name="announcement_status" style="width: 50px; height: 24px; cursor:pointer;" {{ $settings->announcement_status ? 'checked' : '' }} />
                                    <label class="form-check-label fw-bold text-dark" for="announcement_status" style="cursor:pointer; font-size:0.875rem;">Status</label>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold" for="announcement_text">Active Alert/Notice Message</label>
                                        <textarea id="announcement_text" name="announcement_text" class="form-control" rows="4" placeholder="Type an emergency alert, meeting announcement, or general rule to display at the top of the user dashboards...">{{ $settings->announcement_text }}</textarea>
                                        <div class="form-text mt-2">
                                            💡 When status is enabled, this message scrolls continuously inside a high-visibility alert ribbon at the top of Super Admin, Resident, Guard, and Society Admin interfaces.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Global Form Actions -->
                <div class="mt-4 pt-3 d-flex align-items-center justify-content-end border-top gap-2 animate-fadeInUp">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4 py-2"><i class="bx bx-x"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 py-2"><i class="bx bx-save"></i> Save & Deploy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview logo and favicon image fields upon select
    function previewFile(type) {
        const fileInput = document.getElementById(type);
        const preview = document.getElementById(type + 'Preview');
        const icon = document.getElementById(type + 'Icon');
        
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (icon) icon.style.display = 'none';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    // Color pickers sync
    function syncColor(type) {
        const picker = document.getElementById(type);
        const text = document.getElementById(type + '_text');
        
        picker.value = text.value;
    }

    // Dynamic color picker link
    document.getElementById('primary_color').addEventListener('input', function() {
        document.getElementById('primary_color_text').value = this.value;
    });
    document.getElementById('secondary_color').addEventListener('input', function() {
        document.getElementById('secondary_color_text').value = this.value;
    });
    document.getElementById('sidebar_bg_color').addEventListener('input', function() {
        document.getElementById('sidebar_bg_color_text').value = this.value;
    });

    // Test SMTP Mail Server Connectivity
    function testSmtpConnection() {
        const recipient = $('#test_email_recipient').val();
        if (!recipient) {
            Swal.fire({
                icon: 'warning',
                title: 'Recipient Required',
                text: 'Please input a valid test receiver email address.',
                confirmButtonColor: '#696cff'
            });
            return;
        }

        // Show live loader popup
        Swal.fire({
            title: 'Verifying Server...',
            text: 'Overriding transport config and establishing handshake with ' + $('#mail_host').val() + '...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fire post connection check request
        $.ajax({
            url: '{{ route("admin.settings.test-email") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                mail_host: $('#mail_host').val(),
                mail_port: $('#mail_port').val(),
                mail_username: $('#mail_username').val(),
                mail_password: $('#mail_password').val(),
                mail_encryption: $('#mail_encryption').val(),
                mail_from_address: $('#mail_from_address').val(),
                test_email_recipient: recipient
            },
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Connection Success!',
                    text: res.message,
                    confirmButtonColor: '#71dd37'
                });
            },
            error: function(err) {
                const msg = err.responseJSON ? err.responseJSON.message : 'Timeout or invalid parameters.';
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Refused',
                    text: msg,
                    confirmButtonColor: '#ff3e1d'
                });
            }
        });
    }
</script>
@endsection
