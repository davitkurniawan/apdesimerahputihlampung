<?php
/**
 * XOSID SAAS BILLING PDF GENERATOR
 * Uses HTML2PDF to create Invoices and Receipts
 */

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Spipu\Html2Pdf\Html2Pdf;

if (!function_exists('xosid_terbilang')) {
    function xosid_terbilang($angka) {
        $angka = abs((int)$angka);
        $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $terbilang = "";
        if ($angka < 12) {
            $terbilang = " " . $baca[$angka];
        } else if ($angka < 20) {
            $terbilang = xosid_terbilang($angka - 10) . " Belas";
        } else if ($angka < 100) {
            $terbilang = xosid_terbilang($angka / 10) . " Puluh" . xosid_terbilang($angka % 10);
        } else if ($angka < 200) {
            $terbilang = " Seratus" . xosid_terbilang($angka - 100);
        } else if ($angka < 1000) {
            $terbilang = xosid_terbilang($angka / 100) . " Ratus" . xosid_terbilang($angka % 100);
        } else if ($angka < 2000) {
            $terbilang = " Seribu" . xosid_terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $terbilang = xosid_terbilang($angka / 1000) . " Ribu" . xosid_terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $terbilang = xosid_terbilang($angka / 1000000) . " Juta" . xosid_terbilang($angka % 1000000);
        }
        return $terbilang;
    }
}

if (!function_exists('xosid_generate_invoice_pdf')) {
    function xosid_generate_invoice_pdf($invoice_id, $data_invoice, $data_tenant) {
        $storage_dir = __DIR__ . '/storage/invoices/';
        if (!is_dir($storage_dir)) {
            @mkdir($storage_dir, 0755, true);
        }

        $filename = 'Invoice_' . $data_invoice['nomor_invoice'] . '.pdf';
        $dest_path = $storage_dir . $filename;

        // FETCH BUSINESS SETTINGS
        $biz_brand = 'XOSID';
        $biz_legal = 'PT. Neural Nusantara';
        $biz_logo = '';
        $biz_npwp = '-';
        $biz_npwp_name = '-';
        $biz_website = '-';
        $biz_email = '-';
        $biz_address = '-';
        $biz_phone = '-';
        $biz_support = '-';
        $biz_banks = [];

        try {
            $conn = new mysqli("db", "root", "password", "xosid");
            if (!$conn->connect_error) {
                $res = $conn->query("SELECT setting_key, setting_value FROM xosid_settings WHERE category = 'business'");
                if ($res) {
                    while($r = $res->fetch_assoc()) {
                        if($r['setting_key'] == 'biz_brand') $biz_brand = $r['setting_value'];
                        if($r['setting_key'] == 'biz_legal') $biz_legal = $r['setting_value'];
                        if($r['setting_key'] == 'biz_logo') $biz_logo = $r['setting_value'];
                        if($r['setting_key'] == 'biz_npwp') $biz_npwp = $r['setting_value'];
                        if($r['setting_key'] == 'biz_npwp_name') $biz_npwp_name = $r['setting_value'];
                        if($r['setting_key'] == 'biz_website') $biz_website = $r['setting_value'];
                        if($r['setting_key'] == 'biz_email') $biz_email = $r['setting_value'];
                        if($r['setting_key'] == 'biz_address') $biz_address = $r['setting_value'];
                        if($r['setting_key'] == 'biz_phone') $biz_phone = $r['setting_value'];
                        if($r['setting_key'] == 'biz_support') $biz_support = $r['setting_value'];
                        if($r['setting_key'] == 'biz_banks') {
                            $biz_banks_arr = json_decode($r['setting_value'], true);
                            if(is_array($biz_banks_arr)) $biz_banks = $biz_banks_arr;
                        }
                    }
                }
                $conn->close();
            }
        } catch(Exception $e) {}

        $rp_total = number_format($data_invoice['total_tagihan'], 0, ',', '.');
        $tgl_dibuat = date('d F Y', strtotime($data_invoice['tanggal_dibuat']));
        $tgl_jatuhtempo = date('d F Y', strtotime($data_invoice['tanggal_dibuat'] . ' + 1 days'));

        $html = '
        <style>
            page { background-color: #ffffff; color: #1e293b; font-family: Arial, sans-serif; }
            .header { border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-bottom: 20px; }
            .title { font-size: 24px; font-weight: bold; color: #0f172a; }
            .subtitle { font-size: 12px; color: #64748b; }
            .info-table { width: 100%; margin-bottom: 30px; }
            .info-table td { vertical-align: top; padding: 5px 0; font-size: 13px; }
            .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
            .items-table th { background-color: #f1f5f9; padding: 10px; text-align: left; font-size: 13px; border-bottom: 2px solid #cbd5e1; }
            .items-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
            .total-row td { font-weight: bold; font-size: 15px; color: #0f172a; }
            .payment-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 5px; }
            .payment-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #3b82f6; }
            .footer { font-size: 11px; text-align: center; color: #94a3b8; margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        </style>
        <page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
            <div class="header">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <h1 class="title">INVOICE</h1>
                            <div class="subtitle">Sistem Informasi Desa & Tata Kelola ' . htmlspecialchars($biz_brand) . ' SaaS</div>
                        </td>
                        <td style="width: 50%; text-align: right;">
                            ' . ($biz_logo ? '<img src="' . __DIR__ . '/storage/uploads/' . htmlspecialchars($biz_logo) . '" style="height: 40px; margin-bottom: 5px;">' : '<div style="font-size: 20px; font-weight: bold; color: #3b82f6;">' . htmlspecialchars($biz_brand) . '</div>') . '
                            <div class="subtitle">' . htmlspecialchars($biz_legal) . '</div>
                            <div class="subtitle" style="font-size:10px; margin-top:2px;">' . nl2br(htmlspecialchars($biz_address)) . '</div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <table class="info-table">
                <tr>
                    <td style="width: 50%;">
                        <strong>DITAGIHKAN KEPADA:</strong><br>
                        Desa ' . htmlspecialchars($data_tenant['nama_desa']) . '<br>
                        Kec. ' . htmlspecialchars($data_tenant['kecamatan']) . '<br>
                        Kab. ' . htmlspecialchars($data_tenant['kabupaten']) . '<br>
                        Operator: ' . htmlspecialchars($data_tenant['nama_operator']) . ' (' . htmlspecialchars($data_tenant['telepon_operator']) . ')
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <strong>Nomor Invoice:</strong> ' . htmlspecialchars($data_invoice['nomor_invoice']) . '<br>
                        <strong>Tanggal Diterbitkan:</strong> ' . $tgl_dibuat . '<br>
                        <strong style="color: #ef4444;">Batas Pembayaran:</strong> ' . $tgl_jatuhtempo . ' (24 Jam)<br>
                        <strong>Status:</strong> BELUM DIBAYAR
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <tr>
                    <th style="width: 60%;">Deskripsi Layanan</th>
                    <th style="width: 15%; text-align: center;">Bulan</th>
                    <th style="width: 25%; text-align: right;">Jumlah</th>
                </tr>
                <tr>
                    <td>Langganan Paket ' . htmlspecialchars($data_invoice['paket_tujuan']) . ' (XOSID SaaS)</td>
                    <td style="text-align: center;">' . htmlspecialchars($data_invoice['periode_bulan']) . '</td>
                    <td style="text-align: right;">Rp ' . $rp_total . '</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">TOTAL TAGIHAN</td>
                    <td style="text-align: right;">Rp ' . $rp_total . '</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 10px;">
                        <div style="font-style: italic; font-size: 12px; font-weight: bold;">
                            Terbilang: ' . trim(xosid_terbilang($data_invoice['total_tagihan'])) . ' Rupiah
                        </div>
                        <div style="font-size: 10px; color: #64748b; margin-top: 5px;">
                            *Harga belum/tidak termasuk Pajak PPN
                        </div>
                    </td>
                </tr>
            </table>';

            $bank_html = '';
            if (empty($biz_banks)) {
                $bank_html = '<strong>Bank Mandiri:</strong> 123-456-7890 a.n ' . htmlspecialchars($biz_legal) . '<br>
                              <strong>BCA:</strong> 098-765-4321 a.n ' . htmlspecialchars($biz_legal) . '<br><br>';
            } else {
                foreach($biz_banks as $b) {
                    $bank_html .= '<strong>' . htmlspecialchars($b['bank_name'] ?? '') . ':</strong> ' . htmlspecialchars($b['bank_account'] ?? '') . ' a.n ' . htmlspecialchars($b['bank_owner'] ?? '') . '<br>';
                }
                $bank_html .= '<br>';
            }

            $html .= '<div class="payment-box">
                <div class="payment-title">Instruksi Pembayaran:</div>
                <p style="font-size: 12px; line-height: 1.5; margin: 0;">
                    Silakan lakukan transfer sebesar <strong>Rp ' . $rp_total . '</strong> ke salah satu rekening berikut sebelum batas waktu habis:<br><br>
                    ' . $bank_html . '
                    Setelah melakukan transfer, silakan unggah bukti transfer pada Dasbor Member Anda. Invoice akan kadaluarsa secara otomatis jika melewati batas 24 Jam.
                </p>
            </div>
            
            <div class="footer">
                <table style="width:100%; border-top:1px solid #e2e8f0; padding-top:10px; text-align:center; font-size:10px; color:#64748b;">
                    <tr>
                        <td style="width:33%;"><strong>Email:</strong> ' . htmlspecialchars($biz_email) . '</td>
                        <td style="width:34%;"><strong>Website:</strong> ' . htmlspecialchars($biz_website) . '</td>
                        <td style="width:33%;"><strong>Layanan Pelanggan:</strong> ' . htmlspecialchars($biz_support) . '</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top:10px; font-size:9px;">
                            <strong>NPWP:</strong> ' . htmlspecialchars($biz_npwp) . ' | <strong>Nama NPWP:</strong> ' . htmlspecialchars($biz_npwp_name) . '<br>
                            Dokumen ini diterbitkan secara otomatis oleh sistem ' . htmlspecialchars($biz_brand) . ' dan sah tanpa tanda tangan fisik.
                        </td>
                    </tr>
                </table>
            </div>
        </page>';

        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
            $html2pdf->writeHTML($html);
            $html2pdf->output($dest_path, 'F');
            return $dest_path;
        } catch (Exception $e) {
            error_log("HTML2PDF Invoice Error: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('xosid_generate_receipt_pdf')) {
    function xosid_generate_receipt_pdf($invoice_id, $data_invoice, $data_tenant) {
        $storage_dir = __DIR__ . '/storage/invoices/';
        if (!is_dir($storage_dir)) {
            @mkdir($storage_dir, 0755, true);
        }

        $filename = 'Receipt_' . $data_invoice['nomor_invoice'] . '.pdf';
        $dest_path = $storage_dir . $filename;

        $rp_total = number_format($data_invoice['total_tagihan'], 0, ',', '.');
        $tgl_dibuat = date('d F Y', strtotime($data_invoice['tanggal_dibuat']));
        $tgl_dibayar = date('d F Y', strtotime($data_invoice['tanggal_dibayar'] ?? date('Y-m-d H:i:s')));

        $html = '
        <style>
            page { background-color: #ffffff; color: #1e293b; font-family: Arial, sans-serif; }
            .header { border-bottom: 2px solid #10b981; padding-bottom: 10px; margin-bottom: 20px; }
            .title { font-size: 24px; font-weight: bold; color: #0f172a; }
            .subtitle { font-size: 12px; color: #64748b; }
            .info-table { width: 100%; margin-bottom: 30px; }
            .info-table td { vertical-align: top; padding: 5px 0; font-size: 13px; }
            .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
            .items-table th { background-color: #f1f5f9; padding: 10px 0; text-align: left; font-size: 13px; border-bottom: 2px solid #cbd5e1; }
            .items-table td { padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
            .total-row td { font-weight: bold; font-size: 15px; color: #0f172a; }
            .stamp { text-align: center; border: 4px solid #10b981; color: #10b981; font-size: 28px; font-weight: bold; padding: 10px; width: 150px; margin-top: 20px; margin-bottom: 20px; transform: rotate(-10deg); border-radius: 8px; opacity: 0.8; }
            .footer { font-size: 11px; text-align: center; color: #94a3b8; margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        </style>
        <page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
            <div class="header">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <h1 class="title">TANDA TERIMA (RECEIPT)</h1>
                            <div class="subtitle">Sistem Informasi Desa & Tata Kelola XOSID SaaS</div>
                        </td>
                        <td style="width: 50%; text-align: right;">
                            ' . ($biz_logo ? '<img src="' . __DIR__ . '/storage/uploads/' . htmlspecialchars($biz_logo) . '" style="height: 40px; margin-bottom: 5px;">' : '<div style="font-size: 20px; font-weight: bold; color: #10b981;">' . htmlspecialchars($biz_brand) . '</div>') . '
                            <div class="subtitle">' . htmlspecialchars($biz_legal) . '</div>
                            <div class="subtitle" style="font-size:10px; margin-top:2px;">' . nl2br(htmlspecialchars($biz_address)) . '</div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <table class="info-table">
                <tr>
                    <td style="width: 50%;">
                        <strong>DITERIMA DARI:</strong><br>
                        Desa ' . htmlspecialchars($data_tenant['nama_desa']) . '<br>
                        Kec. ' . htmlspecialchars($data_tenant['kecamatan']) . '<br>
                        Kab. ' . htmlspecialchars($data_tenant['kabupaten']) . '<br>
                        Operator: ' . htmlspecialchars($data_tenant['nama_operator']) . ' (' . htmlspecialchars($data_tenant['telepon_operator']) . ')
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <strong>Nomor Invoice:</strong> ' . htmlspecialchars($data_invoice['nomor_invoice']) . '<br>
                        <strong>Tanggal Diterbitkan:</strong> ' . $tgl_dibuat . '<br>
                        <strong>Tanggal Pelunasan:</strong> ' . $tgl_dibayar . '<br>
                        <strong style="color: #10b981;">Status:</strong> LUNAS
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <tr>
                    <th style="width: 60%;">Deskripsi Layanan</th>
                    <th style="width: 15%; text-align: center;">Bulan</th>
                    <th style="width: 25%; text-align: right; padding-right: 0;">Jumlah</th>
                </tr>
                <tr>
                    <td>Langganan Paket ' . htmlspecialchars($data_invoice['paket_tujuan']) . ' (XOSID SaaS)</td>
                    <td style="text-align: center;">' . htmlspecialchars($data_invoice['periode_bulan']) . '</td>
                    <td style="text-align: right; padding-right: 0;">Rp ' . $rp_total . '</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">TOTAL DIBAYAR</td>
                    <td style="text-align: right; color: #10b981; padding-right: 0;">Rp ' . $rp_total . '</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 10px;">
                        <div style="font-style: italic; font-size: 12px; font-weight: bold;">
                            Terbilang: ' . trim(xosid_terbilang($data_invoice['total_tagihan'])) . ' Rupiah
                        </div>
                        <div style="font-size: 10px; color: #64748b; margin-top: 5px;">
                            *Harga belum/tidak termasuk Pajak PPN
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: none; padding-top: 15px; padding-right: 0;">
                        <table align="right" style="margin-right: 0;">
                            <tr>
                                <td style="text-align: center; border: none; padding: 0;">
                                    <table style="border: 4px solid #10b981; border-radius: 8px; margin: 0 auto;">
                                        <tr>
                                            <td style="color: #10b981; font-size: 20px; font-weight: bold; padding: 5px 15px; text-align: center; border: none;">LUNAS</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; border: none; padding: 10px 0 0 0;">
                                    <qrcode value="https://xosid.id/verify/' . htmlspecialchars($data_invoice['nomor_invoice']) . '" ec="H" style="width: 22mm; border: none;"></qrcode>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #475569;">
                Terima kasih telah berlangganan layanan XOSID. Layanan desa Anda telah aktif sesuai dengan paket yang dipilih.
            </div>

            <div class="footer">
                Tanda terima ini sah dan diakui sebagai bukti pembayaran resmi XOSID.
            </div>
        </page>';

        try {
            $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
            $html2pdf->writeHTML($html);
            $html2pdf->output($dest_path, 'F');
            return $dest_path;
        } catch (Exception $e) {
            error_log("HTML2PDF Receipt Error: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('xosid_stamp_bkp_qr')) {
    function xosid_stamp_bkp_qr($source_pdf_path, $invoice_no) {
        if (!file_exists($source_pdf_path)) return false;

        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode('https://xosid.id/verify_bkp/' . $invoice_no);
        $qr_img = file_get_contents($qr_url);
        if (!$qr_img) return false;

        $qr_tmp_path = sys_get_temp_dir() . '/qr_' . time() . '.png';
        file_put_contents($qr_tmp_path, $qr_img);

        // Fetch biz_logo dynamically from settings database
        global $conn;
        $biz_logo = '';
        if ($conn) {
            $q = $conn->query("SELECT setting_value FROM xosid_settings WHERE setting_key = 'biz_logo' LIMIT 1");
            if ($q && $r = $q->fetch_assoc()) {
                $biz_logo = $r['setting_value'];
            }
        }
        if (empty($biz_logo)) {
            $biz_logo = 'xosid_logo_1782312777.png'; // local fallback
        }

        if (!class_exists('setasign\Fpdi\Tcpdf\Fpdi')) {
            if (file_exists(__DIR__ . '/vendor/setasign/fpdi/src/autoload.php')) {
                require_once __DIR__ . '/vendor/setasign/fpdi/src/autoload.php';
            }
        }

        try {
            $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pageCount = $pdf->setSourceFile($source_pdf_path);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L', [$size['width'], $size['height']]);
                } else {
                    $pdf->AddPage('P', [$size['width'], $size['height']]);
                }

                $pdf->useTemplate($templateId);

                // Stamp only on the first page
                if ($pageNo === 1) {
                    // Position exactly in the middle between "Yang Memberi" and "Yang Menerima"
                    $qr_size = 18; // slightly smaller to fit perfectly
                    // Geser ke kanan total 60mm untuk menggantikan tanda tangan fisik "Yang Menerima"
                    $x = ($size['width'] / 2) - ($qr_size / 2) + 60; 
                    $y = 132; // 132mm from top (below the text "Yang Menerima")
                    
                    // Stamp Company Logo / Business Stamp first (behind or next to the QR Code)
                    if (!empty($biz_logo)) {
                        $logo_path = __DIR__ . '/storage/uploads/' . $biz_logo;
                        if (file_exists($logo_path)) {
                            // Scale stamp to about 28mm width
                            $stamp_w = 28;
                            $stamp_h = 0; // auto
                            // Place it slightly overlapping the QR code (shifted 22mm left) to look like a physical stamp
                            $logo_x = $x - 22;
                            $logo_y = $y - 3;
                            $pdf->Image($logo_path, $logo_x, $logo_y, $stamp_w, $stamp_h);
                        }
                    }
                    
                    $pdf->Image($qr_tmp_path, $x, $y, $qr_size, $qr_size, 'PNG');
                    
                    // Add some text under QR
                    $pdf->SetFont('helvetica', 'B', 8);
                    $pdf->SetTextColor(71, 85, 105); // Slate 600
                    $pdf->SetXY($x - 8.5, $y + $qr_size + 1);
                    $pdf->Cell(35, 4, 'BKP VERIFIED', 0, 0, 'C');

                    // Add Disclaimer Text way below the kwitansi box to avoid barcode (approx 175mm from top)
                    $pdf->SetFont('helvetica', 'I', 8);
                    $pdf->SetXY(10, 175);
                    $pdf->Cell($size['width'] - 20, 4, 'QRCode dalam dokumen BKP ini diterbitkan secara otomatis oleh sistem xoSID Supradesa dan sah tanpa tanda tangan fisik.', 0, 0, 'C');
                }
            }

            $pdf->Output($source_pdf_path, 'F');
            @unlink($qr_tmp_path);
            
            return true;
        } catch (\Exception $e) {
            error_log("FPDI Error: " . $e->getMessage());
            @unlink($qr_tmp_path);
            return false;
        }
    }
}
