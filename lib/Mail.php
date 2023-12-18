<?php

namespace JQuest;

use JQuest\Models\Enquiry;
use JQuest\Models\EnquiryResponse;
use JQuest\Models\Mailer;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Aug 7, 2015
 */
class Mail
{

    const ENQUIRY_RESPONSE_SUBJECT = "お問合せNo.%d - 担当者を指定しました ( %s )";
    const ENQUIRY_RESPONSE_UPLOADED_SUBJECT = "お問合せNo.%d - 対応完了。担当者：%s";

    public static function verifyRecaptcha($token) {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://www.google.com',
            // You can set any number of default request options.
            'timeout'  => 5.0,
        ]);
        $response = $client->post('/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => '6LdRtzUpAAAAALua1AQ3i-hAzX5whYtuCk8Gh1Y8',
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        ]);
        $response_body = json_decode($response->getBody(), true);
        return $response_body['success'];
    }

    public static function submitEnquiryResponseUploaded(Enquiry $enquiry, EnquiryResponse $response)
    {
        $subject = sprintf(static::ENQUIRY_RESPONSE_UPLOADED_SUBJECT, $enquiry->id, $response->responsible_party);
        $content = <<<STRING
お問合せリンク：%s
STRING;

        $content = sprintf($content, BASE_URL . "/{$enquiry->id}");
        static::broadcast($subject, $content);
    }

    public static function submitEnquiryResponse(Enquiry $enquiry, EnquiryResponse $response)
    {
        $subject = sprintf(static::ENQUIRY_RESPONSE_SUBJECT, $enquiry->id, $response->responsible_party);
        $content = <<<STRING
<b>日付：</b> %s <br>
<b>カテゴリ：</b> %s <br>
<b>担当者：</b> %s <br>
<b>メッセージ：</b> <br>
%s
<br><br>
<b>責任者：</b>%s
STRING;
        $content = sprintf(
            $content,
            $response->updated_at->format('Y-m-d H:i:s'),
            $response->category->name,
            $response->responsible_party,
            nl2br($response->message),
            $response->user->screen_name
        );

        foreach (Mailer::all() as $mailer) {
            $to = $mailer->email;
            static::mail($to, $subject, $content);
        }
    }

    public static function submitEnquiry(array $params, $title)
    {
        if (!isset($_POST['submit'])) {
            return false;
        }

        if (!self::verifyRecaptcha($params['g-recaptcha-response'])) {
            throw new \Exception("invalid recaptcha response");
        }

        date_default_timezone_set('Asia/Tokyo');

        $enquiry = (new Enquiry())->fill([
            'store_id' => intval($params['available_store']),
            'opinions_enquiries' => $params['opinions_enquiries'],
            'name' => $params['name'],
            'contact_katakana' => $params['contact_katakana'],
            'postal_code' => $params['postal_code'],
            'state' => $params['state'],
            'city' => $params['city'],
            'building_name' => $params['building_name'],
            'contact_requirement' => intval($params['contact_requirement']) === 0 ? 0 : 1,
            'contact_method' => $params['contact_method'],
            'telephone_number' => $params['telephone_number'],
            'email' => $params['email_id']
        ]);
        $enquiry->saveOrFail();

        if ($enquiry) {
            $availableShop = $enquiry->store->prefecture->name . ' ' . $enquiry->store->name;
            $opinionsEnquiries = $enquiry->opinions_enquiries;
            $link = BASE_URL . "/{$enquiry->id}";

            $subject = "[No." . "$enquiry->id" . "]" . $title;

            $datetime = date('y-m-d H:i:s');
            $message2 = <<<EOD
<html>
<head>

<title>
JQuest Enquiry Ticket
</title>
</head>
<body>

<p>受信日時 : $datetime</p>
<p>ご利用店舗 : $availableShop</p>
<p>お問合せ内容 :</p>
<p>$opinionsEnquiries</p>
<p>お問合せ内容の詳細は下記より。</p>
<p><a href="https://www.j-quest.jp/contact3/enquiry/$enquiry->id" target="_blank">JQコンタクト配信者用ログイン</a></p>

</body>
</html>
EOD;

            static::broadcast($subject, $message2);

            if ($enquiry->email) {
                $subject = "お問い合わせを受け付けました。";
                $email = "no-reply@j-quest.co.jp";
                $message3 = <<<EOD
<html>
<head>

<title>
JQuest Enquiry Ticket
</title>
</head>
<body>
{$enquiry->name} 様<br>
この度は、ENEOSジェイクエストのホームページよりお問合せいただき、誠にありがとうございます。<br>
お客様へのご回答は、内容を確認後、お客様が選択された回答方法により、担当部署より改めてご連絡申し上げます。<br>
<br>
なお、弊社対応時間（9:00～18：00）外、もしくはお問合せ内容によっては回答にお時間を頂く場合がございます。あらかじめご了承ください。<br>
<br>
株式会社ENEOSジェイクエスト<br>
</body>
</html>
EOD;
                static::mail($enquiry->email, $subject, $message3, $email);
            }
        }
    }

  public static function submitCorpEnquiry(array $params)
  {
    if (!isset($_POST['submit'])) {
      return false;
    }

      if (!self::verifyRecaptcha($params['g-recaptcha-response'])) {
          throw new \Exception("invalid recaptcha response");
      }

        $opinions_enquiries = nl2br(htmlspecialchars($params['opinions_enquiries']));
        $corp_name = htmlspecialchars($params['corp_name']);
        $contact_name = htmlspecialchars($params['contact_name']);
        $postal_code = htmlspecialchars($params['postal_code']);
        $state = htmlspecialchars($params['state']);
        $city = htmlspecialchars($params['city']);
        $building_name = htmlspecialchars($params['building_name']);
        $telephone_number = htmlspecialchars($params['telephone_number']);
        $email = htmlspecialchars($params['email_id']);

        $subject = "JQコンタクト（企業様・業者様）";
        $datetime = htmlspecialchars(date('y-m-d H:i:s'));
        $message = <<<EOD
<html>
<head>

<title>
$subject
</title>
<style>td{
  border: solid 1px black;
  witdh: 100%;
}</style>
</head>
<body>
ホームページからお問合せがありました。
(企業様・業者様を選択)

<table>
  <tr>
    <td>受信日時</td>
    <td>$datetime</td>
  </tr>
  <tr>
    <td>お問合せ内容</td>
    <td>$opinions_enquiries</td>
  </tr>
  <tr>
    <td>会社名</td>
    <td>$corp_name</td>
  </tr>
  <tr>
    <td>お名前</td>
    <td>$contact_name</td>
  </tr>
  <tr>
    <td>郵便番号</td>
    <td>$postal_code</td>
  </tr>
  <tr>
    <td>都道府県</td>
    <td>$state</td>
  </tr>
  <tr>
    <td>市区郡町番地</td>
    <td>$city</td>
  </tr>
  <tr>
    <td>建物名</td>
    <td>$building_name</td>
  </tr>
  <tr>
    <td>電話番号</td>
    <td>$telephone_number</td>
  </tr>
  <tr>
    <td>メールアドレス</td>
    <td>$email</td>
  </tr>
</table>
</body>
</html>
EOD;

    $corp_enquiry_mail = trim(file_get_contents(__DIR__ . '/../corp_enquiry_mail.txt'));
    static::mail($corp_enquiry_mail, $subject, $message);
  }

    private static function broadcast($subject, $message)
    {
        foreach (Mailer::all() as $mailer) {
            static::mail($mailer->email, $subject, $message, "jq-contact-2012@ml.kagoya.net");
        }
    }

    private static function mail($to, $subject, $message, $from = null)
    {
        $config = Config::load('mail');
        $from = $from === null ? $config['from'] : $from;

        $subject = '=?utf-8?B?'.base64_encode($subject).'?=';
        $headers = "From: $from\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        if ($config['pretend'] || array_key_exists('test', $_REQUEST)) {
            Log::info(sprintf("pretend send email %s to %s\n%s", $subject, $to, $message));
        } else {
            mail($to, $subject, $message, $headers);
        }
    }
}
