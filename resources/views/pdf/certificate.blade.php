<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Donation Certificate</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0; padding: 0;
            background: #fff;
            color: #1a1a2e;
        }
        .certificate {
            width: 100%;
            min-height: 100vh;
            padding: 50px;
            box-sizing: border-box;
            position: relative;
            background: #fff;
        }

        /* Border decoration */
        .border-frame {
            position: absolute;
            top: 20px; left: 20px; right: 20px; bottom: 20px;
            border: 3px solid #C0152A;
            border-radius: 12px;
        }
        .border-frame::before {
            content: '';
            position: absolute;
            top: 6px; left: 6px; right: 6px; bottom: 6px;
            border: 1px solid #e8c8c8;
            border-radius: 8px;
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 30px 40px;
        }

        .logo {
            width: 60px; height: 60px;
            background: #C0152A;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 15px;
        }

        .org-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }
        .org-name span { color: #C0152A; }

        .cert-title {
            font-size: 32px;
            font-weight: 300;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: #C0152A;
            margin: 30px 0 10px;
        }

        .subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 30px;
        }

        .donor-name {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a2e;
            border-bottom: 2px solid #C0152A;
            display: inline-block;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .message {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .details-grid {
            display: inline-block;
            text-align: left;
            margin: 0 auto 30px;
        }
        .details-grid table {
            border-collapse: collapse;
        }
        .details-grid td {
            padding: 6px 20px;
            font-size: 13px;
        }
        .details-grid td:first-child {
            color: #888;
            font-weight: 500;
            text-align: right;
        }
        .details-grid td:last-child {
            font-weight: 600;
            color: #1a1a2e;
        }

        .blood-badge {
            display: inline-block;
            background: #C0152A;
            color: #fff;
            padding: 4px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
        }

        .footer-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .footer-left, .footer-right {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }
        .footer-left { text-align: left; }
        .footer-right { text-align: right; }

        .signature-line {
            width: 180px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            font-size: 12px;
            color: #888;
        }

        .cert-id {
            font-size: 11px;
            color: #bbb;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-frame"></div>

        <div class="content">
            <div class="logo">♥</div>
            <div class="org-name">Blood<span>Bank</span></div>

            <div class="cert-title">Certificate of Donation</div>
            <div class="subtitle">This is to certify that</div>

            <div class="donor-name">{{ $user->name }}</div>

            <div class="message">
                has voluntarily donated blood and made a significant contribution
                to saving lives. We express our sincere gratitude for this noble act.
            </div>

            <div class="details-grid">
                <table>
                    <tr>
                        <td>Donation ID</td>
                        <td>#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td>Donation Date</td>
                        <td>{{ $donation->donated_at->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td>Blood Group</td>
                        <td><span class="blood-badge">{{ $donor->blood_group }}</span></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                </table>
            </div>

            <div class="footer-section">
                <div class="footer-left">
                    <div class="signature-line">
                        Date: {{ now()->format('F d, Y') }}
                    </div>
                </div>
                <div class="footer-right">
                    <div class="signature-line" style="margin-left:auto">
                        Authorized Signature
                    </div>
                </div>
            </div>

            <div class="cert-id">
                Certificate ID: CERT-{{ strtoupper(substr(md5($donation->id . $user->id), 0, 10)) }}
            </div>
        </div>
    </div>
</body>
</html>
