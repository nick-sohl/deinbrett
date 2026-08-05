<?php
/**
 * All email templates render inside this layout.
 * The inner template is passed as `$file` via renderTemplate() context — but simpler:
 * templates are self-contained; the layout provides the HTML shell around them.
 */
$innerFile = __DIR__ . '/' . $name . '.php';
ob_start();
include $innerFile;
$content = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"></head>
<body style="font-family: -apple-system, 'Helvetica Neue', Arial, sans-serif; background:#f6f6f6; margin:0; padding:24px;">
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden;">
    <tr>
      <td style="padding:24px 28px; background:#111; color:#fff;">
        <div style="font-size:18px; font-weight:600; letter-spacing:-0.01em;">DeinBrett</div>
      </td>
    </tr>
    <tr>
      <td style="padding:28px; color:#222; line-height:1.55; font-size:15px;">
        <?= $content ?>
      </td>
    </tr>
    <tr>
      <td style="padding:16px 28px; background:#fafafa; color:#888; font-size:12px; text-align:center;">
        DeinBrett · Handgefertigt in der Schweiz
      </td>
    </tr>
  </table>
</body>
</html>
