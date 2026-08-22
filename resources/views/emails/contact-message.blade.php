<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background: #DD6625; color: #ffffff; padding: 20px; text-align: center; font-size: 24px; font-weight: bold; }
        .content { padding: 30px; }
        .field { margin-bottom: 20px; }
        .label { font-weight: bold; color: #555; text-transform: uppercase; font-size: 12px; margin-bottom: 5px; }
        .value { font-size: 16px; color: #111; }
        .message-box { background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee; margin-top: 5px; white-space: pre-wrap; font-size: 15px; }
        .footer { padding: 15px; text-align: center; font-size: 12px; color: #888; background: #fdfdfd; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            New Contact Message
        </div>
        <div class="content">
            <div class="field">
                <div class="label">Name</div>
                <div class="value">{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}</div>
            </div>
            
            <div class="field">
                <div class="label">Email Address</div>
                <div class="value"><a href="mailto:{{ $contactMessage->email }}" style="color: #DD6625;">{{ $contactMessage->email }}</a></div>
            </div>
            
            <div class="field">
                <div class="label">Subject</div>
                <div class="value">{{ $contactMessage->subject }}</div>
            </div>
            
            <div class="field">
                <div class="label">Message</div>
                <div class="message-box">{{ $contactMessage->message }}</div>
            </div>
        </div>
        <div class="footer">
            This email was sent automatically from the TastyDelight Website Contact Form.
        </div>
    </div>
</body>
</html>
