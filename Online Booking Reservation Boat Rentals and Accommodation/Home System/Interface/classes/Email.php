<?php
class Email {
    private $from_email;
    private $from_name;

    public function __construct() {
        $this->from_email = 'carles.tourism@gmail.com';
        $this->from_name = 'Carles Tourism';
    }

    public function send($to, $subject, $body) {
        $headers = array(
            'From: ' . $this->from_name . ' <' . $this->from_email . '>',
            'Reply-To: ' . $this->from_email,
            'X-Mailer: PHP/' . phpversion(),
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8'
        );

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }
} 