<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Password — Project RED AI</title>
    <!--[if mso]>
    <noscript>
        <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
    </noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 48px 16px;
        }

        .email-container {
            max-width: 520px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 10px 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, #0f172a 0%, #312e81 50%, #0f172a 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: -0.5px;
        }

        .brand-text { text-align: left; }
        .brand-name { font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .brand-sub  { font-size: 9px; font-weight: 600; color: #4f46e5; text-transform: uppercase; letter-spacing: 0.12em; }

        /* Card */
        .card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 1px 4px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        /* Card top gradient bar */
        .card-top-bar {
            height: 4px;
            background: linear-gradient(90deg, #4f46e5 0%, #818cf8 50%, #38bdf8 100%);
        }

        .card-body {
            padding: 40px 40px 36px;
        }

        /* Icon */
        .icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #ede9fe, #dbeafe);
            border: 1px solid #c7d2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .icon-wrap svg { width: 26px; height: 26px; color: #4f46e5; }

        /* Heading */
        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        /* Greeting */
        .greeting {
            font-size: 14px;
            color: #334155;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .greeting strong { color: #0f172a; font-weight: 600; }

        /* CTA Button */
        .cta-wrapper {
            text-align: center;
            margin: 28px 0;
        }

        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.025em;
            padding: 14px 36px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.35);
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 28px 0;
        }

        /* Expiry note */
        .expiry-note {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* URL fallback */
        .url-fallback {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 16px;
        }

        .url-fallback p {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .url-fallback a {
            font-size: 11px;
            color: #4f46e5;
            word-break: break-all;
            text-decoration: none;
        }

        /* Security notice */
        .security-box {
            background: #fefce8;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 20px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .security-box svg { width: 16px; height: 16px; color: #d97706; flex-shrink: 0; margin-top: 1px; }

        .security-box p {
            font-size: 12px;
            color: #92400e;
            line-height: 1.5;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.7;
        }

        .footer a {
            color: #64748b;
            text-decoration: none;
        }

        /* Mobile */
        @media (max-width: 560px) {
            .card-body { padding: 28px 24px; }
            h1 { font-size: 20px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        <!-- Header -->
        <div class="header">
            <div class="brand-badge">
                <div class="brand-icon">R</div>
                <div class="brand-text">
                    <div class="brand-name">Project RED</div>
                    <div class="brand-sub">Enterprise AI</div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-top-bar"></div>
            <div class="card-body">

                <!-- Lock icon -->
                <div class="icon-wrap">
                    <svg fill="none" viewBox="0 0 24 24" stroke="#4f46e5" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <h1>Reset your password</h1>
                <p class="subtitle">We received a request to reset the password for your account.</p>

                <p class="greeting">
                    Hi <strong>{{ $name }}</strong>,<br><br>
                    Click the button below to choose a new password for your
                    <strong>Project RED AI</strong> account. This link is valid for
                    <strong>{{ $expireIn }} minutes</strong>.
                </p>

                <!-- CTA -->
                <div class="cta-wrapper">
                    <a href="{{ $url }}" class="cta-btn" target="_blank" rel="noopener noreferrer">
                        Reset My Password →
                    </a>
                </div>

                <hr class="divider">

                <!-- Expiry note -->
                <p class="expiry-note">
                    If you did not request a password reset, you can safely ignore this email.
                    Your password will remain unchanged.
                </p>

                <!-- URL fallback -->
                <div class="url-fallback">
                    <p>If the button above doesn't work, paste this link into your browser:</p>
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $url }}</a>
                </div>

                <!-- Security notice -->
                <div class="security-box">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p>
                        <strong>Security tip:</strong> Never share this link with anyone.
                        Project RED AI staff will never ask for your password.
                    </p>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                © {{ date('Y') }} Project RED AI. All rights reserved.<br>
                This email was sent automatically — please do not reply.
            </p>
        </div>

    </div>
</div>
</body>
</html>
