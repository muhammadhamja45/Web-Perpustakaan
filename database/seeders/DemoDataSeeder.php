<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo admin
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@perpustakaan.smk.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_approved' => true,
            'approved_by' => null,
            'approved_at' => now(),
        ]);

        // Create demo students
        $students = [
            [
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki@student.smk.id',
                'password' => Hash::make('student123'),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.smk.id',
                'password' => Hash::make('student123'),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.smk.id',
                'password' => Hash::make('student123'),
            ],
            [
                'name' => 'Rini Sartika',
                'email' => 'rini.sartika@student.smk.id',
                'password' => Hash::make('student123'),
            ],
            [
                'name' => 'Dika Nugraha',
                'email' => 'dika.nugraha@student.smk.id',
                'password' => Hash::make('student123'),
            ],
        ];

        $studentUsers = [];
        foreach ($students as $student) {
            $studentUsers[] = User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'email_verified_at' => now(),
                'password' => $student['password'],
                'role' => 'student',
                'is_approved' => true,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);
        }

        // Create demo books
        $books = [
            [
                'title' => 'Pemrograman Web dengan Laravel 11',
                'author' => 'Andi Prasetyo',
                'isbn' => '978-623-001-001-1',
                'published_year' => 2024,
                'quantity' => 5,
                'available_quantity' => 3,
                'category' => 'Programming',
                'description' => 'Panduan lengkap pengembangan web modern menggunakan Laravel 11 dengan fitur terbaru dan best practices.'
            ],
            [
                'title' => 'Database MySQL untuk Pemula',
                'author' => 'Sari Indah Permata',
                'isbn' => '978-623-001-002-8',
                'published_year' => 2023,
                'quantity' => 4,
                'available_quantity' => 2,
                'category' => 'Database',
                'description' => 'Belajar database MySQL dari dasar hingga mahir dengan contoh praktis dan studi kasus.'
            ],
            [
                'title' => 'Algoritma dan Struktur Data',
                'author' => 'Dr. Budi Raharjo',
                'isbn' => '978-623-001-003-5',
                'published_year' => 2023,
                'quantity' => 6,
                'available_quantity' => 4,
                'category' => 'Computer Science',
                'description' => 'Konsep fundamental algoritma dan struktur data dengan implementasi dalam berbagai bahasa pemrograman.'
            ],
            [
                'title' => 'Jaringan Komputer Dasar',
                'author' => 'Eko Nugroho, S.Kom',
                'isbn' => '978-623-001-004-2',
                'published_year' => 2022,
                'quantity' => 3,
                'available_quantity' => 1,
                'category' => 'Networking',
                'description' => 'Pengenalan jaringan komputer, protokol, dan infrastruktur IT untuk siswa SMK.'
            ],
            [
                'title' => 'HTML CSS JavaScript Modern',
                'author' => 'Rini Sartika',
                'isbn' => '978-623-001-005-9',
                'published_year' => 2024,
                'quantity' => 7,
                'available_quantity' => 5,
                'category' => 'Web Development',
                'description' => 'Pengembangan frontend modern dengan HTML5, CSS3, dan JavaScript ES6+ untuk web responsif.'
            ],
            [
                'title' => 'Python untuk Data Science',
                'author' => 'Ahmad Rahman',
                'isbn' => '978-623-001-006-6',
                'published_year' => 2024,
                'quantity' => 4,
                'available_quantity' => 3,
                'category' => 'Data Science',
                'description' => 'Analisis data dan machine learning menggunakan Python dengan library populer seperti Pandas dan NumPy.'
            ],
            [
                'title' => 'Mobile App Development',
                'author' => 'Sinta Dewi',
                'isbn' => '978-623-001-007-3',
                'published_year' => 2023,
                'quantity' => 3,
                'available_quantity' => 2,
                'category' => 'Mobile Development',
                'description' => 'Pengembangan aplikasi mobile native dan cross-platform dengan React Native dan Flutter.'
            ],
            [
                'title' => 'Cyber Security Fundamentals',
                'author' => 'Arief Budiman',
                'isbn' => '978-623-001-008-0',
                'published_year' => 2024,
                'quantity' => 5,
                'available_quantity' => 3,
                'category' => 'Security',
                'description' => 'Dasar-dasar keamanan siber, ethical hacking, dan perlindungan sistem informasi.'
            ],
            [
                'title' => 'UI/UX Design Principles',
                'author' => 'Maya Sari',
                'isbn' => '978-623-001-009-7',
                'published_year' => 2023,
                'quantity' => 4,
                'available_quantity' => 2,
                'category' => 'Design',
                'description' => 'Prinsip desain antarmuka pengguna dan pengalaman pengguna untuk aplikasi dan web.'
            ],
            [
                'title' => 'Cloud Computing Essentials',
                'author' => 'Rahman Ali',
                'isbn' => '978-623-001-010-3',
                'published_year' => 2024,
                'quantity' => 3,
                'available_quantity' => 1,
                'category' => 'Cloud Computing',
                'description' => 'Konsep cloud computing, AWS, Azure, dan Google Cloud Platform untuk pemula.'
            ],
            [
                'title' => 'React.js Complete Guide',
                'author' => 'John Smith',
                'isbn' => '978-623-001-011-0',
                'published_year' => 2024,
                'quantity' => 5,
                'available_quantity' => 4,
                'category' => 'Web Development',
                'description' => 'Panduan lengkap React.js dari dasar hingga advanced dengan hooks dan context API.'
            ],
            [
                'title' => 'Artificial Intelligence Basics',
                'author' => 'Dr. Sarah Johnson',
                'isbn' => '978-623-001-012-7',
                'published_year' => 2024,
                'quantity' => 4,
                'available_quantity' => 3,
                'category' => 'AI/ML',
                'description' => 'Pengenalan kecerdasan buatan, machine learning, dan neural networks untuk siswa.'
            ],
        ];

        $bookModels = [];
        foreach ($books as $book) {
            $bookModels[] = Book::create($book);
        }

        // Create demo loans (active and returned)
        $activeLoans = [
            ['user_id' => $studentUsers[0]->id, 'book_id' => $bookModels[0]->id, 'days_ago' => 5],
            ['user_id' => $studentUsers[1]->id, 'book_id' => $bookModels[1]->id, 'days_ago' => 8],
            ['user_id' => $studentUsers[2]->id, 'book_id' => $bookModels[3]->id, 'days_ago' => 3],
            ['user_id' => $studentUsers[3]->id, 'book_id' => $bookModels[7]->id, 'days_ago' => 10],
            ['user_id' => $studentUsers[0]->id, 'book_id' => $bookModels[8]->id, 'days_ago' => 2],
        ];

        foreach ($activeLoans as $loan) {
            $borrowedDate = now()->subDays($loan['days_ago']);
            Loan::create([
                'user_id' => $loan['user_id'],
                'book_id' => $loan['book_id'],
                'borrowed_date' => $borrowedDate,
                'due_date' => $borrowedDate->copy()->addDays(14),
                'returned_date' => null,
            ]);
        }

        // Create returned loans (history)
        $returnedLoans = [
            ['user_id' => $studentUsers[1]->id, 'book_id' => $bookModels[0]->id, 'borrowed_days_ago' => 25, 'returned_days_ago' => 12],
            ['user_id' => $studentUsers[2]->id, 'book_id' => $bookModels[4]->id, 'borrowed_days_ago' => 30, 'returned_days_ago' => 18],
            ['user_id' => $studentUsers[3]->id, 'book_id' => $bookModels[5]->id, 'borrowed_days_ago' => 20, 'returned_days_ago' => 8],
            ['user_id' => $studentUsers[4]->id, 'book_id' => $bookModels[6]->id, 'borrowed_days_ago' => 35, 'returned_days_ago' => 22],
            ['user_id' => $studentUsers[0]->id, 'book_id' => $bookModels[2]->id, 'borrowed_days_ago' => 28, 'returned_days_ago' => 15],
        ];

        foreach ($returnedLoans as $loan) {
            $borrowedDate = now()->subDays($loan['borrowed_days_ago']);
            $returnedDate = now()->subDays($loan['returned_days_ago']);

            Loan::create([
                'user_id' => $loan['user_id'],
                'book_id' => $loan['book_id'],
                'borrowed_date' => $borrowedDate,
                'due_date' => $borrowedDate->copy()->addDays(14),
                'returned_date' => $returnedDate,
            ]);
        }

        // Create demo notifications
        $notifications = [
            [
                'user_id' => $studentUsers[0]->id,
                'type' => 'loan_created',
                'title' => 'Buku Berhasil Dipinjam',
                'message' => 'Anda telah meminjam buku "Pemrograman Web dengan Laravel 11" hingga ' . now()->addDays(9)->format('d M Y'),
                'data' => json_encode([
                    'loan_id' => 1,
                    'book_title' => 'Pemrograman Web dengan Laravel 11',
                    'due_date' => now()->addDays(9)->toDateString()
                ]),
                'read_at' => null,
            ],
            [
                'user_id' => $studentUsers[1]->id,
                'type' => 'loan_reminder',
                'title' => 'Pengingat Pengembalian Buku',
                'message' => 'Buku "Database MySQL untuk Pemula" akan jatuh tempo pada ' . now()->addDays(6)->format('d M Y'),
                'data' => json_encode([
                    'loan_id' => 2,
                    'book_title' => 'Database MySQL untuk Pemula',
                    'due_date' => now()->addDays(6)->toDateString()
                ]),
                'read_at' => null,
            ],
            [
                'user_id' => $studentUsers[2]->id,
                'type' => 'new_book',
                'title' => 'Buku Baru Tersedia',
                'message' => 'Buku baru "React.js Complete Guide" telah ditambahkan ke perpustakaan',
                'data' => json_encode([
                    'book_id' => $bookModels[10]->id,
                    'book_title' => 'React.js Complete Guide'
                ]),
                'read_at' => now()->subHours(2),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Admin login: admin@perpustakaan.smk.id / admin123');
        $this->command->info('Student login: ahmad.rizki@student.smk.id / student123');
    }
}