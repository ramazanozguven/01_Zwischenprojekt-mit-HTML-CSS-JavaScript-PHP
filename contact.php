<?php

// POST verisi üzerinde reCAPTCHA doğrulamasını yapmak için API'yi çağırın.
$recaptcha_response = $_POST['g-recaptcha-response'];
$secret_key = "6LdrVKImAAAAALLzWfrcFvgYM2rQCZRR0idpNeTT";
$ip = $_SERVER['REMOTE_ADDR'];

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => $secret_key,
    'response' => $recaptcha_response,
    'remoteip' => $ip
);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$response_data = json_decode($response);

if ($response_data->success) {
    // reCAPTCHA doğrulandı, formu işleme devam edebilirsiniz.
    // Burada gerçekleştirilecek işlemleri ekleyin.

    // Form verilerini alın
    $salut = $_POST['salut'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Burada form verilerini işlemek için istediğiniz işlemleri yapabilirsiniz
    // Örneğin, veritabanına kaydetmek veya e-posta göndermek gibi

    // Örnek olarak form verilerini ekrana yazdıralım:
    echo "<strong>Salutation:</strong> $salut<br>";
    echo "<strong>Last Name:</strong> $lname<br>";
    echo "<strong>First Name:</strong> $fname<br>";
    echo "<strong>Email:</strong> $email<br>";
    echo "<strong>Phone:</strong> $phone<br>";
    echo "<strong>Message:</strong> $message<br>";

    // E-posta gönderimi
    $to = 'no911@gmx.de';
    $subject = 'Webformular-Nachricht von https://schwandorf-automobile.de/';
    $email_message = "
        <strong>Nachricht über das Website-Kontaktformular:</strong> <br>
        <strong>Absender:</strong> $salut $lname $fname <br>
        <strong>E-Mail:</strong> $email <br>
        <strong>Telefon:</strong> $phone <br> <br>
        <strong>Nachricht:</strong> <br>
        $message";

    $headers = "From: $fname $lname <$email>\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($to, $subject, $email_message, $headers)) {
        echo "E-posta başarıyla gönderildi.";
    } else {
        echo "E-posta gönderilirken bir hata oluştu.";
    }

    echo 'Ihre Nachricht wurde erfolgreich gesendet. Sie werden baldmöglichst eine Antwort erhalten.
    <a href="index.html">Zurück zum Formular...</a>';
} else {
    // reCAPTCHA doğrulanamadı, hata mesajı gösterin veya kullanıcıyı yönlendirin.
    // Burada kullanıcıya hata mesajını gösterebilir veya yönlendirebilirsiniz.
    echo "reCAPTCHA doğrulaması başarısız oldu. Lütfen tekrar deneyin.";
}

?>
