<div class="card">
    <div class="card-body" style="padding: 44px 28px;">
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:24px;">
            <div style="flex:0 0 140px;">
                <div style="
                    width:140px;
                    height:140px;
                    border-radius:28px;
                    background:linear-gradient(135deg, #dbeafe, #e0f2fe, #dcfce7);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:54px;
                    font-weight:800;
                    color:#2563eb;
                    box-shadow:0 16px 30px rgba(59,130,246,.12);
                ">
                    404
                </div>
            </div>

            <div style="flex:1; min-width:260px;">
                <div style="font-size:30px; font-weight:800; color:inherit; margin-bottom:10px;">
                    Halaman tidak ditemukan
                </div>
                <div class="muted" style="font-size:15px; line-height:1.8; margin-bottom:18px;">
                    Maaf, halaman yang kamu cari tidak tersedia, sudah dipindahkan, atau URL yang dimasukkan tidak benar.
                    Silakan kembali ke dashboard atau periksa kembali alamat halaman.
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:12px;">
                    <a href="<?= e(base_url('dashboard')) ?>" style="
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        padding:12px 18px;
                        border-radius:14px;
                        background:linear-gradient(90deg,#3b82f6,#06b6d4);
                        color:#fff;
                        font-weight:700;
                        box-shadow:0 12px 22px rgba(59,130,246,.18);
                    ">
                        Kembali ke Dashboard
                    </a>

                    <a href="<?= e(base_url('login')) ?>" style="
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        padding:12px 18px;
                        border-radius:14px;
                        background:rgba(255,255,255,.7);
                        color:inherit;
                        font-weight:700;
                        border:1px solid rgba(219,234,254,.7);
                    ">
                        Ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>