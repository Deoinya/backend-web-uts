<?php

namespace App\Controllers;

use App\Helpers\JsonStorage;
use App\Models\EBook;
use App\Models\Member;
use App\Models\PremiumRentalRule;
use App\Models\RegularRentalRule;
use App\Models\Transaction;

class TransactionController
{
    private JsonStorage $bookStorage;
    private JsonStorage $transactionStorage;

    public function __construct(JsonStorage $bookStorage, JsonStorage $transactionStorage)
    {
        $this->bookStorage = $bookStorage;
        $this->transactionStorage = $transactionStorage;
    }

    public function checkout(array $input): string
    {
        $rawBookIds = $input['book_ids'] ?? '';
        if (is_array($rawBookIds)) {
            $bookIds = $rawBookIds;
        } else {
            $bookIds = array_filter(array_map('trim', explode(',', (string)$rawBookIds)));
        }

        $rentalDays = (int)($input['rental_days'] ?? $input['durasi'] ?? 7);
        if ($rentalDays < 1) {
            $rentalDays = 7;
        }

        $startDate = $input['tanggal_pinjam'] ?? date('Y-m-d');
        $memberType = $input['member_type'] ?? 'Reguler';

        if (empty($bookIds)) {
            http_response_code(400);
            return json_encode([
                'status' => 'error',
                'message' => 'Keranjang peminjaman kosong. Pilih minimal 1 e-book.'
            ]);
        }

        $allBooks = $this->bookStorage->readAll();
        $selectedBooks = [];

        foreach ($allBooks as &$book) {
            if (in_array((string)$book['id'], array_map('strval', $bookIds), true)) {
                if (($book['status'] ?? 'available') !== 'available') {
                    http_response_code(400);
                    return json_encode([
                        'status' => 'error',
                        'message' => 'Buku "' . $book['title'] . '" sedang dipinjam.'
                    ]);
                }
                $selectedBooks[] = $book;
                $book['status'] = 'borrowed';
            }
        }
        unset($book);

        if (empty($selectedBooks)) {
            http_response_code(404);
            return json_encode([
                'status' => 'error',
                'message' => 'Data buku tidak ditemukan di katalog.'
            ]);
        }

        $member = new Member('M-GUEST', 'Anggota Perpustakaan', 'anggota@perpustakaan.test', $memberType);
        $rule = strcasecmp($memberType, 'Premium') === 0
            ? new PremiumRentalRule()
            : new RegularRentalRule();

        $grandTotal = 0.0;
        $processedItems = [];

        foreach ($selectedBooks as $item) {
            $trxId = 'TRX-' . strtoupper(substr(uniqid(), -6));
            $ebookObj = new EBook(
                (string)$item['id'],
                $item['title'],
                $item['author'],
                (float)($item['price'] ?? 0)
            );

            $transaction = new Transaction($trxId, $member, $ebookObj, $rentalDays, $rule);
            $res = json_decode($transaction->processTransaction(), true);
            $cost = (float)($res['data']['total_cost'] ?? 0);
            $grandTotal += $cost;

            $processedItems[] = [
                'transaction_id' => $trxId,
                'book_id' => $item['id'],
                'title' => $item['title'],
                'cost' => $cost
            ];
        }

        // Update status buku menjadi borrowed & simpan riwayat transaksi ke JSON
        $this->bookStorage->writeAll($allBooks);
        $this->transactionStorage->append([
            'id' => 'ORD-' . time(),
            'user_id' => $input['user_id'] ?? 'M-GUEST',
            'user_email' => $input['user_email'] ?? '',
            'tanggal_pinjam' => $startDate,
            'tanggal_kembali' => date('Y-m-d', strtotime($startDate . " +{$rentalDays} days")),
            'rental_days' => $rentalDays,
            'items' => $processedItems,
            'total_cost' => $grandTotal
        ]);

        return json_encode([
            'status' => 'success',
            'message' => 'Peminjaman berhasil dikonfirmasi!',
            'data' => [
                'total_cost' => $grandTotal,
                'rental_days' => $rentalDays,
                'items' => $processedItems
            ]
        ]);
    }
    public function getHistory(?string $email = null): string
    {
        $allTransactions = $this->transactionStorage->readAll();
        $history = [];

        foreach ($allTransactions as $trx) {
            // Jika user sedang login (email dikirim), filter riwayat milik user tersebut
            if ($email && !empty($trx['user_email']) && strcasecmp($trx['user_email'], $email) !== 0) {
                continue;
            }

            $startDate = $trx['tanggal_pinjam'] ?? '-';
            $dueDate = $trx['tanggal_kembali'] ?? '-';
            $status = ($dueDate !== '-' && strtotime($dueDate) < strtotime(date('Y-m-d')))
                ? 'Selesai'
                : 'Sedang dipinjam';

            foreach (($trx['items'] ?? []) as $item) {
                $history[] = [
                    'transaction_id' => $item['transaction_id'] ?? ($trx['id'] ?? '-'),
                    'title' => $item['title'] ?? 'Tanpa Judul',
                    'tanggal_pinjam' => $startDate,
                    'tanggal_kembali' => $dueDate,
                    'status' => $status
                ];
            }
        }

        // Urutkan transaksi terbaru di paling atas
        $history = array_reverse($history);

        return json_encode([
            'status' => 'success',
            'data' => $history
        ]);
    }
}