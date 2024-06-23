<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject }}</title>
</head>
<body>
<div style="font-family: Helvetica, Arial, sans-serif; min-width: 1000px; overflow: auto; line-height: 2">
  <div style="margin: 50px auto; width: 70%; padding: 20px 0">
    <div style="border-bottom: 1px solid #eee">
      <a href="" style="font-size: 1.4em; color: #00466a; text-decoration: none; font-weight: 600">SugarBloom Bakery</a>
    </div>
    <h1 style="font-size: 1.4em; color: #00466a;">{{ $subject }}</h1>
    <p style="font-size: 1.1em">{{ $content }}</p>
    <p style="font-size: 0.9em;">Regards,<br/>SugarBloom Bakery</p>
    <hr style="border: none; border-top: 1px solid #eee" />
    <div style="float: right; padding: 8px 0; color: #aaa; font-size: 0.8em; line-height: 1; font-weight: 300">
      <p>SugarBloom Bakery Inc</p>
      <p>Sogod, Southern Leyte</p>
      <p>Philippines</p>
    </div>
    <p style="font-size: 0.9em;">Sent by: {{ $fromEmail }}</p>
  </div>
</div>
</body>
</html>
