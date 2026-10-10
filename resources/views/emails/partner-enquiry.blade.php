<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Partner Enquiry</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px; color: #1e293b;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <div style="background-color: #6A0406; padding: 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 700;">Dzuels Educational Foundation</h1>
            <p style="margin: 4px 0 0; font-size: 14px; color: #F9DEC9;">New Partnership Enquiry Received</p>
        </div>

        <div style="padding: 24px;">
            <p style="font-size: 15px; line-height: 1.5; margin-top: 0;">
                A new institutional or corporate partnership enquiry has been submitted through the public website.
            </p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 16px; margin-bottom: 24px;">
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; width: 35%; color: #475569;">Organization:</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">{{ $enquiry->organization_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569;">Contact Name:</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #0f172a;">{{ $enquiry->contact_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569;">Email Address:</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #0f172a;">
                        <a href="mailto:{{ $enquiry->email }}" style="color: #6A0406; text-decoration: none;">{{ $enquiry->email }}</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569;">Phone Number:</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #0f172a;">{{ $enquiry->phone ?: 'Not provided' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569;">Partnership Type:</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #0f172a;">{{ $enquiry->partnership_type_label }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569; vertical-align: top;">Proposal / Message:</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #0f172a; white-space: pre-wrap;">{{ $enquiry->message }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; font-weight: 600; color: #475569;">Submitted At:</td>
                    <td style="padding: 10px 12px; color: #0f172a;">{{ $enquiry->created_at?->format('F j, Y, g:i a') ?? 'Just now' }}</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ url('/admin/inquiries') }}" style="display: inline-block; background-color: #6A0406; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                    View in Admin Inbox
                </a>
            </div>
        </div>

        <div style="background-color: #f1f5f9; padding: 16px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
            Dzuels Educational Foundation &bull; Empowering the Next Generation
        </div>
    </div>
</body>
</html>
