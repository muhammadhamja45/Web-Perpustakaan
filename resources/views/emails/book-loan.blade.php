<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Peminjaman Buku</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2d3748;
        }
        .book-details {
            background-color: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .book-details h2 {
            margin: 0 0 15px 0;
            color: #2d3748;
            font-size: 20px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            width: 140px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #2d3748;
            flex: 1;
        }
        .important-info {
            background-color: #fef5e7;
            border: 1px solid #f6ad55;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .important-info h3 {
            margin: 0 0 10px 0;
            color: #c05621;
            display: flex;
            align-items: center;
        }
        .important-info .warning-icon {
            margin-right: 8px;
            font-size: 18px;
        }
        .return-date {
            background-color: #e6fffa;
            border: 2px solid #38b2ac;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .return-date .date {
            font-size: 20px;
            font-weight: 700;
            color: #234e52;
        }
        .footer {
            background-color: #edf2f7;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 14px;
        }
        .contact-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #cbd5e0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content, .header, .footer {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: auto;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">📚</div>
            <h1>Konfirmasi Peminjaman Buku</h1>
            <p>Sistem Perpustakaan Digital</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $loan->user->name }}</strong>,
            </div>

            <p>Selamat! Peminjaman buku Anda telah berhasil diproses. Berikut adalah detail lengkap peminjaman Anda:</p>

            <!-- Book Details -->
            <div class="book-details">
                <h2>📖 Detail Buku</h2>
                <div class="detail-row">
                    <div class="detail-label">Judul Buku:</div>
                    <div class="detail-value"><strong>{{ $loan->book->title }}</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Penulis:</div>
                    <div class="detail-value">{{ $loan->book->author }}</div>
                </div>
                @if($loan->book->isbn)
                <div class="detail-row">
                    <div class="detail-label">ISBN:</div>
                    <div class="detail-value">{{ $loan->book->isbn }}</div>
                </div>
                @endif
                @if($loan->book->category)
                <div class="detail-row">
                    <div class="detail-label">Kategori:</div>
                    <div class="detail-value">{{ $loan->book->category }}</div>
                </div>
                @endif
                @if($loan->book->published_year)
                <div class="detail-row">
                    <div class="detail-label">Tahun Terbit:</div>
                    <div class="detail-value">{{ $loan->book->published_year }}</div>
                </div>
                @endif
            </div>

            <!-- Loan Details -->
            <div class="book-details">
                <h2>📋 Detail Peminjaman</h2>
                <div class="detail-row">
                    <div class="detail-label">ID Peminjaman:</div>
                    <div class="detail-value"><strong>#{{ $loan->id }}</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal Pinjam:</div>
                    <div class="detail-value">{{ $loan->created_at->format('d F Y, H:i') }} WIB</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value">
                        <span style="background-color: #c6f6d5; color: #22543d; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                            ✅ DIPINJAM
                        </span>
                    </div>
                </div>
            </div>

            <!-- Return Date -->
            <div class="return-date">
                <p style="margin: 0 0 8px 0; color: #234e52; font-weight: 600;">📅 Tanggal Pengembalian:</p>
                <div class="date">{{ $loan->due_date->format('d F Y') }}</div>
                <p style="margin: 8px 0 0 0; color: #4a5568; font-size: 14px;">
                    ({{ $loan->due_date->diffInDays($loan->created_at) }} hari dari sekarang)
                </p>
            </div>

            <!-- Important Information -->
            <div class="important-info">
                <h3><span class="warning-icon">⚠️</span> Penting untuk Diperhatikan</h3>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Pastikan untuk mengembalikan buku tepat waktu untuk menghindari denda keterlambatan</li>
                    <li>Jaga kondisi buku dengan baik selama masa peminjaman</li>
                    <li>Jika ada kerusakan pada buku, segera laporkan ke pustakawan</li>
                    <li>Perpanjangan peminjaman dapat dilakukan maksimal 1 kali jika tidak ada antrian</li>
                    <li>Denda keterlambatan: Rp 1.000 per hari per buku</li>
                </ul>
            </div>

            <p>Jika Anda memiliki pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi kami.</p>

            <center>
                <a href="{{ route('dashboard') }}" class="btn">Lihat Dashboard</a>
            </center>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Terima kasih telah menggunakan layanan perpustakaan kami!</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>

            <div class="contact-info">
                <p><strong>Kontak Perpustakaan:</strong></p>
                <p>📧 Email: perpustakaan@sekolah.ac.id</p>
                <p>📞 Telepon: (021) 123-4567</p>
                <p>🏢 Alamat: Jl. Pendidikan No. 123, Jakarta</p>
                <p>🕒 Jam Operasional: Senin - Jumat, 08:00 - 16:00 WIB</p>
            </div>
        </div>
    </div>
</body>
</html>